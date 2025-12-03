<?php

class Fichas extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        session_regenerate_id(true);
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
        getPermisos(MFICHAS);
    }

    public function Fichas()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Fichas";
        $data['page_title'] = "Fichas";
        $data['page_name'] = "fichas";
        $data['page_functions_js'] = "functions_fichas.js";
        $this->views->getView($this, "fichas", $data);
    }
    // CONTROLADOOR DE INSERCION DE FICHAS
    public function setFicha()
    {

        // MONITOR DE ERRORES
        error_reporting(E_ALL);
        ini_set('display_errors', 0);

        if ($_POST) {

            // Validación básica de campos obligatorios
            if (empty($_POST['txtCodigoPrograma']) || empty($_POST['txtFichaPrograma']) || empty($_POST['txtNombreInstructor'])) {
                echo json_encode(["status" => false, "msg" => 'Datos incorrectos.'], JSON_UNESCAPED_UNICODE);
                die();
            }

            // Datos del formulario
            $intIdeFicha     = intval($_POST['ideFicha'] ?? 0); // 0 = nuevo registro
            $CodigoPrograma  = strClean($_POST['txtCodigoPrograma']);
            $FichaPrograma   = strClean($_POST['txtFichaPrograma']);
            $IdeInstructor   = strClean($_POST['txtIdeInstructor']);
            $intStatus       = intval($_POST['listStatus'] ?? 1); // 1 = activo por defecto

            // Buscar ID del programa
            $CodigoProgramaEscaped = addslashes($CodigoPrograma);
            $sqlPrograma = "SELECT ideprograma FROM tbl_programas WHERE codigoprograma = '$CodigoProgramaEscaped'";
            $programa = $this->model->select($sqlPrograma);

            if (empty($programa)) {
                echo json_encode(['status' => false, 'msg' => 'Programa no encontrado.'], JSON_UNESCAPED_UNICODE);
                die();
            }
            $intPrograma = $programa['ideprograma'];

            // Buscar ID del instructor
            $IdeInstructorEscaped = addslashes($IdeInstructor);
            $sqlUsuario = "SELECT ideusuario FROM tbl_usuarios WHERE identificacion = '$IdeInstructorEscaped'";
            $usuario = $this->model->select($sqlUsuario);

            if (empty($usuario)) {
                echo json_encode(['status' => false, 'msg' => 'Instructor no encontrado.'], JSON_UNESCAPED_UNICODE);
                die();
            }
            $intInstructor = $usuario['ideusuario'];

            // Insert o Update
            $request_user = 0;
            $msg = '';

            if ($intIdeFicha == 0) {
                // Insertar
                if (!empty($_SESSION['permisosMod']['w'])) {
                    $request_user = $this->model->insertFicha($intPrograma, $FichaPrograma, $intInstructor, $intStatus);

                    if ($request_user === "exist") {
                        echo json_encode([
                            'status' => 'exist',
                            'msg' => 'La ficha ya está registrada.'
                        ], JSON_UNESCAPED_UNICODE);
                        die();
                    }

                    $msg = 'Ficha creada correctamente.';
                }
            } else if ($intIdeFicha > 0) {
                // Actualizar
                if (!empty($_SESSION['permisosMod']['u'])) {
                    $request_user = $this->model->updateFicha($intIdeFicha, $intPrograma, $FichaPrograma, $intInstructor, $intStatus);
                    $msg = 'Ficha actualizada correctamente.';
                }
            }

            // Respuesta JSON general
            echo json_encode([
                'status' => $request_user > 0,
                'msg' => $request_user > 0 ? $msg : 'No se pudo procesar la ficha.'
            ], JSON_UNESCAPED_UNICODE);
            die();
        }
    }


    // mostrar lo de la tabla toda
    public function getFichas()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!empty($_SESSION['permisosMod']['r'])) {
            $arrData = $this->model->selectFichas();

            foreach ($arrData as &$row) {
                $row['status_ficha'] = ($row['status_ficha'] == 1)
                    ? '<span class="badge bg-success">Activo</span>'
                    : '<span class="badge bg-danger">Inactivo</span>';

                $btnView = $btnEdit = $btnDelete = '';

                if (!empty($_SESSION['permisosMod']['r'])) {
                    $btnView = '<button class="btn btn-info" 
                        onClick="fntViewInfo(' . $row['ideficha'] . ')" 
                        title="Ver Ficha">
                        <i class="bi bi-eye"></i>
                    </button>';
                }

                if (!empty($_SESSION['permisosMod']['u'])) {
                    $btnEdit = '<button class="btn btn-warning" 
                        onClick="fntEditInfo(this,' . $row['ideficha'] . ')" 
                        title="Editar Ficha">
                        <i class="bi bi-pencil"></i>
                    </button>';
                }

                if (!empty($_SESSION['permisosMod']['d'])) {
                    $btnDelete = '<button class="btn btn-danger btnDelRol" 
                          onClick="fntDelInfo(' . $row['ideficha'] . ')" 
                          title="Eliminar Ficha">
                          <i class="bi bi-trash3"></i>
                      </button>';
                }

                $row['options'] = '<div class="text-center">'
                    . $btnView . ' ' . $btnEdit . ' ' . $btnDelete .
                    '</div>';
            }

            // 🔍 Ver qué se está devolviendo
            error_log('JSON enviado: ' . json_encode($arrData, JSON_UNESCAPED_UNICODE));

            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([], JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    ///pruebas de consulta 
    // public function getFichas()
    // {
    //     // Forzar JSON
    //     header('Content-Type: application/json; charset=utf-8');

    //     // DEBUG: ver si la sesión y permisos existen
    //     error_log('DEBUG - session permisosMod: ' . print_r($_SESSION['permisosMod'] ?? 'NO_EXISTE', true));

    //     // Traer datos directamente del modelo (sin chequear permisos para debug)
    //     $arrData = $this->model->selectFichas();

    //     // DEBUG: registrar lo que retorna el modelo
    //     error_log('DEBUG - selectFichas result: ' . print_r($arrData, true));

    //     // Si está vacío, devolver mensaje claro para que lo veas en Network -> Response
    //     if (empty($arrData)) {
    //         echo json_encode(['debug' => 'empty', 'msg' => 'selectFichas devolvió vacío o hubo error'], JSON_UNESCAPED_UNICODE);
    //         die();
    //     }

    //     // Envío crudo para ver exactamente qué llega
    //     echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    //     die();
    // }


    // MOSTRAR EN EL FORMULARIO DE EDICION
    public function getFicha($ideficha)
    {
        if ($_SESSION['permisosMod']['r']) {
            $ideficha = intval($ideficha);
            if ($ideficha > 0) {
                $arrData = $this->model->selectFicha($ideficha);
                $arrResponse = empty($arrData)
                    ? ['status' => false, 'msg' => 'Datos no encontrados.']
                    : ['status' => true, 'data' => $arrData];
                header('Content-Type: application/json');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }


    //funcion para eliminar
    public function delFicha()
    {
        if ($_POST) {
            // Validar permisos
            if ($_SESSION['permisosMod']['d']) {
                $intIdeFicha = intval($_POST['ideficha']);
                $requestDelete = $this->model->deleteFicha($intIdeFicha);
                $arrResponse = $requestDelete
                    ? array('status' => true, 'msg' => 'Se ha eliminado la Ficha')
                    : array('status' => false, 'msg' => 'Error al eliminar la Ficha.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }

    /// funcion para mostrar el programa EN EL FORMULARIO
    public function getPrograma($codprograma)
    {
        // Validar permisos
        if (!isset($_SESSION['permisosMod']['r']) || $_SESSION['permisosMod']['r'] != 1) {
            echo json_encode(['status' => false, 'msg' => 'Sin permisos.'], JSON_UNESCAPED_UNICODE);
            die();
        }

        // Limpiar código (puede ser numérico o alfanumérico)
        $codprograma = strClean($codprograma);

        if ($codprograma != '') {
            $arrData = $this->model->selectPrograma($codprograma);
            $arrResponse = empty($arrData)
                ? ['status' => false, 'msg' => 'Datos no encontrados.']
                : ['status' => true, 'data' => $arrData];

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['status' => false, 'msg' => 'Código vacío.'], JSON_UNESCAPED_UNICODE);
        }

        die();
    }

    // MOSTRAR EL INSTRUTOR EN EL FORMULARIO
    public function getInstructor($identificacion)
    {

        if ($_SESSION['permisosMod']['r']) {
            $identificacion = strClean($identificacion); // 🔹 antes estaba intval()
            if (!empty($identificacion)) {
                $arrData = $this->model->selectInstructor($identificacion);
                $arrResponse = empty($arrData)
                    ? array('status' => false, 'msg' => 'Datos no encontrados.')
                    : array('status' => true, 'data' => $arrData);
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }
}
