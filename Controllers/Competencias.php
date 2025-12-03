<?php

class Competencias extends Controllers
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
        getPermisos(MCOMPETENCIAS);
    }

    // TODO SELECCIONAR PROGRAMAS
    public function getSelectProgramas()
    {
        switch ($_GET['op']) {
            case "combo":
                $arrData = $this->model->selectProgramas();
                if (count($arrData) > 0) {
                    $htmlOptions = "<select class='form-control' id='ListadoProgramas' name='ListadoProgramas'>
                       <option >Selecciona el Programa de Formación</option>
                       </select>";
                    foreach ($arrData as $row) {
                        $htmlOptions .= "<option value='" . $row['codigoprograma'] . "'>" . $row['nombreprograma'] . "</option>";
                    }
                    echo $htmlOptions;
                    die();
                }
                break;
        }
    }

    public function getSelectProgramasEditar()
    {
        $htmlOptionss = "";
        switch ($_GET['op']) {
            case "combo":
                $arrData = $this->model->selectProgramasEditar($_POST["codigoprograma"]);
                if (count($arrData) > 0) {
                    $htmlOptionss = "<select class='form-control' id='ListadoProgramas' name='ListadoProgramas'>
                       <option >Selecciona el Programa de Formación</option>
                       </select>";
                    foreach ($arrData as $row) {
                        $htmlOptionss .= "<option value='" . $row['codigoprograma'] . "'>" . $row['nombreprograma'] . "</option>";
                    }
                }
                echo $htmlOptionss;
                die();

                break;
        }
    }


    public function Competencias()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Competencias";
        $data['page_title'] = "Competencias";
        $data['page_name'] = "competencias";
        $data['page_functions_js'] = "functions_competencias.js";
        $this->views->getView($this, "competencias", $data);
    }

    /// Funcion para registrar competencias
    public function setCompetencia()
    {
        error_reporting(0);
        if ($_POST) {
            // var_dump($_POST);
            // die();

            if (empty($_POST['txtCodigoCompetencia'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                $intIdeCompetencia = intval($_POST['ideCompetencia']);
                $intIFicha = intval($_POST['txtFicha']);
                $strCodigoCompetencia = strClean($_POST['txtCodigoCompetencia']);
                $strNombreCompetencia = strClean($_POST['txtNombreCompetencia']);
                $strTipocompetencia = strClean($_POST['txtTipoCompetencia']);
                $strHorasCompetencia = strClean($_POST['txtHorasCompetencia']);
                $strCodigoPrograma = strClean($_POST['txtCodigoPrograma']);


                // Buscar ID de la ficha
                $NumeroFicha = addslashes($intIFicha);
                $sqlFicha = "SELECT ideficha FROM tbl_fichas WHERE numeroficha = '$NumeroFicha'";
                $ficha = $this->model->select($sqlFicha);

                if (empty($ficha)) {
                    echo json_encode(['status' => false, 'msg' => 'Ficha no encontrada.'], JSON_UNESCAPED_UNICODE);
                    die();
                }

                $intFicha = $ficha['ideficha'];


                $request_user = "";
                if ($intIdeCompetencia == 0) {
                    $option = 1;
                    if ($_SESSION['permisosMod']['w']) {
                        $request_user = $this->model->insertCompetencia(
                            $strCodigoCompetencia,
                            $intFicha,
                            $strNombreCompetencia,
                            $strTipocompetencia,
                            $strHorasCompetencia,
                            $strCodigoPrograma

                        );
                    }
                } else {
                    $option = 2;
                    if ($_SESSION['permisosMod']['u']) {
                        $request_user = $this->model->updateCompetencia(
                            $intIdeCompetencia,
                            $intFicha,
                            $strCodigoCompetencia,
                            $strTipocompetencia,
                            $strNombreCompetencia,
                            $strHorasCompetencia,
                            $strCodigoPrograma
                        );
                    }
                }
                if ($request_user > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 1);
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 0);
                    }
                } else if ($request_user == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! el código de la Competencia ya existe, ingrese otra');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }

                header('Content-Type: application/json');
                echo json_encode($arrResponse);
            }
            // echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    /// VISTA DE LA COMPETENCIA TABLA DE COMPETENCIAS
    public function getCompetencias()
    {
        if ($_SESSION['permisosMod']['r']) {
            $arrData = $this->model->selectCompetencias();

            for ($i = 0; $i < count($arrData); $i++) {

                // --- Cálculo del progreso ---
                $horasCompetencia = floatval($arrData[$i]['horascompetencia']); // total original
                $horasRestantes   = isset($arrData[$i]['totalhoras']) ? floatval($arrData[$i]['totalhoras']) : $horasCompetencia;

                // Evitar negativos o null
                if ($horasRestantes < 0) {
                    $horasRestantes = 0;
                }

                // Calcular horas avanzadas
                $horasAvanzadas = $horasCompetencia - $horasRestantes;

                // Calcular porcentaje (seguro)
                if ($horasCompetencia > 0) {
                    $porcentajeReal = ($horasAvanzadas / $horasCompetencia) * 100;
                } else {
                    $porcentajeReal = 0;
                }

                // Limitar al 100%
                $porcentajeMostrar = min($porcentajeReal, 100);

                // Color según avance
                if ($porcentajeReal < 40) {
                    $color = 'bg-danger';
                } elseif ($porcentajeReal < 70) {
                    $color = 'bg-warning';
                } else {
                    $color = 'bg-success';
                }

                // Mensaje debajo
                if ($horasRestantes <= 0) {
                    $mensajeEspecial = '<small class="text-success ms-1"><i class="bi bi-check-circle"></i> Completado ✅</small>';
                } elseif ($porcentajeReal >= 50 && $porcentajeReal < 70) {
                    $mensajeEspecial = '<small class="text-warning ms-1"><i class="bi bi-hourglass-split"></i> Mitad alcanzada</small>';
                } else {
                    $mensajeEspecial = '';
                }

                // --- Barra de progreso ---
                $arrData[$i]['progreso'] = '
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar ' . $color . '" 
                        role="progressbar" 
                        aria-valuenow="' . round($porcentajeMostrar, 2) . '" 
                        aria-valuemin="0" 
                        aria-valuemax="100" 
                        style="width: ' . round($porcentajeMostrar, 2) . '%;">
                    </div>
                </div>
                ' . $mensajeEspecial;

                // --- Estado ---
                $arrData[$i]['status_competencia'] = ($arrData[$i]['status_competencia'] == 1)
                    ? '<span class="badge bg-success">Activo</span>'
                    : '<span class="badge bg-danger">Inactivo</span>';

                // --- Botones ---
                $btnView = $btnEdit = $btnDelete = '';

                if ($_SESSION['permisosMod']['r']) {
                    $btnView = '<button class="btn btn-info" onClick="fntViewInfo(' . $arrData[$i]['idecompetencia'] . ')" title="Ver Competencia"><i class="bi bi-eye"></i></button>';
                }
                if ($_SESSION['permisosMod']['u']) {
                    $btnEdit = '<button class="btn btn-warning" onClick="fntEditInfo(this,' . $arrData[$i]['idecompetencia'] . ')" title="Editar Competencia"><i class="bi bi-pencil"></i></button>';
                }
                if ($_SESSION['permisosMod']['d']) {
                    $btnDelete = '<button class="btn btn-danger" onClick="fntDelInfo(' . $arrData[$i]['idecompetencia'] . ')" title="Eliminar Competencia"><i class="bi bi-trash3"></i></button>';
                }

                $arrData[$i]['options'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnDelete . '</div>';
            }

            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    /// ATUALIZAR COMPETENCIA
    public function getCompetencia(int $idecompetencia)
    {
        if ($_SESSION['permisosMod']['r']) {
            $intIdCompetencia = intval(strClean($idecompetencia));
            $request = $this->model->selectCompetencia($intIdCompetencia);

            if (empty($request)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $request);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    
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

    public function delCompetencia()
    {
        if ($_POST) {


            if ($_SESSION['permisosMod']['d']) {
                $intIdeCompetencia = intval($_POST['ideCompetencia']); // 👈 nombre correcto
                $requestDelete = $this->model->deleteCompetencia($intIdeCompetencia);

                if ($requestDelete) {
                    $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado la Competencia');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al eliminar la Competencia.');
                }

                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }
}
