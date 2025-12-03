<?php

class Asignaciones extends Controllers
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
        getPermisos(MASIGNACIONES);
    }

    public function Asignaciones()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Asignaciones";
        $data['page_title'] = "Asignaciones";
        $data['page_name'] = "asignaciones";
        $data['page_functions_js'] = "functions_asignaciones.js";
        $this->views->getView($this, "asignaciones", $data);
    }

    // enviar la informacion al modelo
    public function setAsignaciones()
    {
        error_reporting(0);

        if ($_POST) {

            if (empty($_POST['txtNumeroFicha'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incompletos.');
            } else {

                $intIdeFicha = intval($_POST['ideDetalleFicha']);
                $strNumeroFicha = intval(strClean($_POST['txtNumeroFicha']));
                $strNombreFicha = strClean($_POST['txtNombreFicha']);
                $strIdeInstructor = strClean($_POST['txtIdeInstructor']);
                $strNombreInstructor = strClean($_POST['txtNombreInstructor']);
                $strCodigoCompetencia = intval(strClean($_POST['txtCodigoCompetencia']));
                $strNombreCompetencia = strClean($_POST['txtNombreCompetencia']);
                $strNumeroHoras = intval(strClean($_POST['txtNumeroHoras']));
                $Horaspendiente = intval(strClean($_POST['txtHorasPendienteCompetencia']));
                $strListadoMeses = strClean($_POST['listadoMeses']);

                // Buscar ID del instructor
                $sqlUsuario = "SELECT ideusuario FROM tbl_usuarios WHERE identificacion = '$strIdeInstructor'";
                $usuario = $this->model->select($sqlUsuario);

                if (empty($usuario)) {
                    echo json_encode(['status' => false, 'msg' => 'Instructor no encontrado.'], JSON_UNESCAPED_UNICODE);
                    die();
                }

                $intInstructor = $usuario['ideusuario'];


                $request_user = "";
                if ($intIdeFicha == 0) {
                    $option = 1;
                    if ($_SESSION['permisosMod']['w']) {
                        $request_user = $this->model->insertFicha(
                            $strNumeroFicha,
                            $intInstructor,
                            $strCodigoCompetencia,
                            $strNumeroHoras,
                            $Horaspendiente,
                            $strListadoMeses
                        );
                    }
                } else if ($intIdeFicha > 0) {
                    $request_user = $this->model->updateFicha(
                        $strNumeroFicha,
                        $intInstructor,
                        $strCodigoCompetencia,
                        $strNumeroHoras,
                        $Horaspendiente,
                        $strListadoMeses
                    );
                    $msg = 'Ficha actualizada correctamente.';
                }

                if ($request_user > 0) {
                    $msg = ($option == 1) ? 'Guardada correctamente' : 'Actualizada correctamente';
                    $arrResponse = array('status' => true, 'msg' => $msg);
                } else if ($request_user == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! la asignación ya existe');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getFichas()
    {
        if ($_SESSION['permisosMod']['r']) {
            $arrData = $this->model->selectFichas();

            for ($i = 0; $i < count($arrData); $i++) {

                $horasCompetencia = floatval($arrData[$i]['horascompetencia']); // total original
                $horasRestantes   = floatval($arrData[$i]['totalhoras']);       // quedan

                // Calcular avance
                if ($horasCompetencia > 0) {
                    $horasAvanzadas = $horasCompetencia - $horasRestantes;
                    $porcentajeReal = ($horasAvanzadas / $horasCompetencia) * 100;
                } else {
                    $porcentajeReal = 0;
                }

                // Limitar al 100%
                $porcentajeMostrar = min($porcentajeReal, 100);

                // Calcular mitad
                $mitad = $horasCompetencia / 2;
                $mitadRedondeada = round($mitad);

                // Mensaje debajo de la barra
                $mensajeEspecial = '';
                if ($horasRestantes == 0) {
                    $mensajeEspecial = '<small class="text-success ms-1"><i class="bi bi-check-circle"></i>Completado</small>';
                } elseif ($horasRestantes <= ($mitadRedondeada + 1) && $horasRestantes >= ($mitadRedondeada - 1)) {
                    $mensajeEspecial = '<small class="text-warning ms-1"><i class="bi bi-hourglass-split"></i> Mitad alcanzada</small>';
                } elseif ($horasRestantes < $mitadRedondeada) {
                    $mensajeEspecial = '<small class="text-info ms-1"><i class="bi bi-arrow-up-right-circle"></i>Más del 50%</small>';
                }

                // Color según porcentaje
                if ($porcentajeReal < 40) {
                    $color = 'bg-danger';
                } elseif ($porcentajeReal < 70) {
                    $color = 'bg-warning';
                } else {
                    $color = 'bg-success';
                }

                // Barra sin texto
                $arrData[$i]['progreso'] = '
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar ' . $color . '" 
                        role="progressbar" 
                        aria-valuenow="' . $porcentajeMostrar . '" 
                        aria-valuemin="0" 
                        aria-valuemax="100" 
                        style="width: ' . $porcentajeMostrar . '%;">
                    </div>
                </div>
                ' . $mensajeEspecial . '
            ';

                // Estado
                $arrData[$i]['status'] = ($arrData[$i]['status'] == 1)
                    ? '<span class="badge bg-success">Activo</span>'
                    : '<span class="badge bg-danger">Inactivo</span>';

                // Botones
                $btnView = $btnEdit = $btnDelete = '';
                if ($_SESSION['permisosMod']['r']) {
                    $btnView = '<button class="btn btn-info" onClick="fntViewInfo(' . $arrData[$i]['idedetalleficha'] . ')" title="Ver Ficha"><i class="bi bi-eye"></i></button>';
                }
                if ($_SESSION['permisosMod']['u']) {
                    $btnEdit = '<button class="btn btn-success" onClick="fntEditInfo(this,' . $arrData[$i]['idedetalleficha'] . ')" title="Editar Ficha"><i class="bi bi-check2-circle"></i></button>';
                }
                if ($_SESSION['permisosMod']['d']) {
                    $btnDelete = '<button class="btn btn-danger" onClick="fntDelInfo(' . $arrData[$i]['idedetalleficha'] . ')" title="Eliminar Ficha"><i class="bi bi-trash3"></i></button>';
                }

                $arrData[$i]['options'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnDelete . '</div>';
            }

            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }


    public function getFicha($idedetalleficha)
    {

        if ($_SESSION['permisosMod']['r']) {
            $idedetalleficha = intval($idedetalleficha);
            $htmlOptions = "";
            if ($idedetalleficha > 0) {
                $arrData = $this->model->selectFicha($idedetalleficha);
                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                } else {
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }

    // eliminar ficha
    public function delFicha()
    {
        if ($_POST) {
            $idDetalleFicha = intval($_POST['idedetalleficha']);

            // Verifica que llegue correctamente
            if ($idDetalleFicha <= 0) {
                $arrResponse = array('status' => false, 'msg' => 'ID de ficha inválido');
            } else {
                $requestDelete = $this->model->deleteFicha($idDetalleFicha);
                if ($requestDelete) {
                    $arrResponse = array('status' => true, 'msg' => 'Ficha eliminada correctamente');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No se pudo eliminar la ficha');
                }
            }

            // 🔹 IMPORTANTE: devolver un JSON limpio
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getIdeFicha($fichaprograma)
    {
        if ($_SESSION['permisosMod']['r']) {
            $fichaprograma = intval($fichaprograma);
            $htmlOptions = "";

            if ($fichaprograma > 0) {
                $arrData = $this->model->selectIdeFicha($fichaprograma);

                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                } else {
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }

    public function getCompetencia($codigocompetencia)
    {
        if ($_SESSION['permisosMod']['r']) {
            $codigocompetencia = intval($codigocompetencia);
            $htmlOptions = "";
            if ($codigocompetencia > 0) {
                $arrData = $this->model->selectCompetencia($codigocompetencia);
                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                } else {
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }

    public function getInstructor($identificacion)
    {
        if ($_SESSION['permisosMod']['r']) {
            $identificacion = intval($identificacion);
            $htmlOptions = "";
            if ($identificacion > 0) {
                $arrData = $this->model->selectInstructor($identificacion);
                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                } else {
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }
}
