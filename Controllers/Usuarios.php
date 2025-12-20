<?php

class Usuarios extends Controllers
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
        getPermisos(MUSUARIOS);
    }

    public function Usuarios()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Usuarios";
        $data['page_title'] = "Usuarios";
        $data['page_name'] = "usuarios";
        $data['page_functions_js'] = "functions_usuarios.js";
        $this->views->getView($this, "usuarios", $data);
    }

    // funcione para agregar 
    public function setUsuario()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        if ($_POST) {

            if (empty($_POST['txtIdentificacionUsuario'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                $intIdeUsuario = intval($_POST['ideUsuario']);
                $strIdentificacionUsuario = strClean($_POST['txtIdentificacionUsuario']);
                $strNombresUsuario = strClean($_POST['txtNombresUsuario']);  /// sonas de cambio 
                $strApellidosUsuario = strClean($_POST['txtApellidosUsuario']);
                $strCelularUsuario = strClean($_POST['txtCelularUsuario']);
                $strCorreoUsuario = strClean($_POST['txtCorreoUsuario']);
                $strRolUsuario = intval(strClean($_POST['listRol']));
                $intStatus = intval(strClean($_POST['listStatus']));

                // $intTipoId = 5;
                $request_user = "";
                if ($intIdeUsuario == 0) {
                    $option = 1;
                    $strPassword =  empty($_POST['txtIdentificacionUsuario']) ? hash("SHA256", passGenerator()) : hash("SHA256", $_POST['txtIdentificacionUsuario']);
                    if ($_SESSION['permisosMod']['w']) {
                        $request_user = $this->model->insertUsuario(
                            $strIdentificacionUsuario,
                            $strNombresUsuario,
                            $strApellidosUsuario, /// sonas de cambio 
                            $strCelularUsuario, /// sonas de cambio 
                            $strCorreoUsuario, /// sonas de cambio 
                            $strPassword,
                            $strRolUsuario,
                            $intStatus

                        );
                    }
                } else {
                    $option = 2;
                    $strPassword =  empty($_POST['txtIdentificacionUsuario']) ? hash("SHA256", passGenerator()) : hash("SHA256", $_POST['txtIdentificacionUsuario']);
                    if ($_SESSION['permisosMod']['u']) {
                        $request_user = $this->model->updateUsuario(
                            $intIdeUsuario,
                            $strIdentificacionUsuario,
                            $strNombresUsuario,
                            $strApellidosUsuario, /// sonas de cambio 
                            $strCelularUsuario,
                            $strCorreoUsuario,
                            $strRolUsuario,
                            $intStatus,
                        );
                    }
                }
                if ($request_user > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Usuario guardado correctamente');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Usuario actualizado correctamente');
                    }
                } else if ($request_user == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! la identificación del Usuario ya existe, ingrese otro');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    // lista de la tabla usuarios
    public function getUsuarios()
    {
        if ($_SESSION['permisosMod']['r']) {
            $arrData = $this->model->selectUsuarios();

            for ($i = 0; $i < count($arrData); $i++) {
                $btnView = '';
                $btnEdit = '';
                $btnDelete = '';

                if ($arrData[$i]['status'] == 1) {
                    $arrData[$i]['status'] = '<span class="badge text-bg-success">Activo</span>';
                } else {
                    $arrData[$i]['status'] = '<span class="badge text-bg-danger">Inactivo</span>';
                }

                if ($_SESSION['permisosMod']['r']) {
                    $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $arrData[$i]['ideusuario'] . ')" title="Ver Usuario"><i class="far fa-eye"></i></button>';
                }
                if ($_SESSION['permisosMod']['u']) {
                    $btnEdit = '<button class="btn btn-warning  btn-sm" onClick="fntEditInfo(this,' . $arrData[$i]['ideusuario'] . ')" title="Editar Usuario"><i class="fas fa-pencil-alt"></i></button>';
                }
                if ($_SESSION['permisosMod']['d']) {
                    $btnDelete = '<button class="btn btn-danger btn-sm btnDelRol" onClick="fntDelInfo(' . $arrData[$i]['ideusuario'] . ')" title="Eliminar Usuario"><i class="bi bi-trash3"></i></button>';
                }

                $arrData[$i]['options'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnDelete . '</div>';
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    // editar usuario
    public function getUsuario($ideusuario)
    {
        if ($_SESSION['permisosMod']['r']) {
            $ideusuario = intval($ideusuario);
            if ($ideusuario > 0) {
                $arrData = $this->model->selectUsuario($ideusuario);
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

    // editar usuario
    public function getUsuarioperfil($ideusuario)
    {
        if ($_SESSION['permisosMod']['r']) {
            $ideusuario = intval($ideusuario);
            if ($ideusuario > 0) {
                $arrData = $this->model->selectUsuario($ideusuario);
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


    // ELIMINAR USUARIO
    public function delUsuario()
    {
        if ($_POST) {
            if ($_SESSION['permisosMod']['d']) {
                $intIdeUsuario = intval($_POST['ideUsuario']);
                $requestDelete = $this->model->deleteUsuario($intIdeUsuario);
                if ($requestDelete) {
                    $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Usuario');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al eliminar al Usuario.');
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }
}
