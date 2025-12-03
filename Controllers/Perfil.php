<?php

class Perfil extends Controllers
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


    public function Perfil()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Perfil";
        $data['page_title'] = "Configuracion de Perfil";
        $data['page_name'] = "perfil";
        $data['page_functions_js'] = "functions_perfil.js";
        $this->views->getView($this, "perfil", $data);
    }

    // funcione para agregar 
    public function setPerfil()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        if ($_POST || $_FILES) {

            if (!isset($_FILES['fotoUsuario']) || $_FILES['fotoUsuario']['error'] != 0) {
                echo json_encode(["status" => false, "msg" => "Debe seleccionar una foto."], JSON_UNESCAPED_UNICODE);
                die();
            }

            // ID del usuario logueado
            $intIdeUsuario = $_SESSION['idUser'];

            $fotoUsuario = $_FILES['fotoUsuario'];
            $nombreArchivo = uniqid() . "_" . $fotoUsuario['name'];

            // Subir imagen
            uploadImage($fotoUsuario, $nombreArchivo);

            // Actualizar SOLO foto
            $request = $this->model->updateFotoPerfil($intIdeUsuario, $nombreArchivo);

            if ($request > 0) {
                $arrResponse = ['status' => true, 'msg' => 'Foto actualizada correctamente', 'foto' => $nombreArchivo];
            } else {
                $arrResponse = ['status' => false, 'msg' => 'No se pudo actualizar la foto'];
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }

        die();
    }


    // lista de la tabla usuarios
    public function getPerfiles()
    {
        if ($_SESSION['permisosMod']['r']) {

            header('Content-Type: application/json; charset=utf-8');

            $arrData = $this->model->selectPerfil();

            for ($i = 0; $i < count($arrData); $i++) {

                $btnView = '';
                $btnEdit = '';
                $btnDelete = '';

                // STATUS
                if ($arrData[$i]['status'] == 1) {
                    $arrData[$i]['status'] = '<span class="badge text-bg-success">Activo</span>';
                } else {
                    $arrData[$i]['status'] = '<span class="badge text-bg-danger">Inactivo</span>';
                }

                // BOTONES
                if ($_SESSION['permisosMod']['r']) {
                    $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo('
                        . $arrData[$i]['ideusuario'] . ')" title="Ver Usuario">
                <i class="far fa-eye"></i></button>';
                }

                if ($_SESSION['permisosMod']['u']) {
                    $btnEdit = '<button class="btn btn-warning btn-sm" onClick="fntEditInfo(this,'
                        . $arrData[$i]['ideusuario'] . ')" title="Editar Usuario">
                <i class="fas fa-pencil-alt"></i></button>';
                }

                if ($_SESSION['permisosMod']['d']) {
                    $btnDelete = '<button class="btn btn-danger btn-sm btnDelRol" onClick="fntDelInfo('
                        . $arrData[$i]['ideusuario'] . ')" title="Eliminar Usuario">
                <i class="bi bi-trash3"></i></button>';
                }

                // OPCIONES
                $arrData[$i]['options'] = htmlspecialchars_decode(
                    '<div class="text-center">'
                        . $btnView . ' ' . $btnEdit . ' ' . $btnDelete .
                        '</div>'
                );
            }

            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    // editar usuario
    public function getUsuario($ideusuario)
    {
        // if ($_SESSION['permisosMod']['r']) {
        //     $ideusuario = intval($ideusuario);
        //     if ($ideusuario > 0) {
        //         $arrData = $this->model->selectUsuario($ideusuario);
        //         if (empty($arrData)) {
        //             $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        //         } else {
        //             $arrResponse = array('status' => true, 'data' => $arrData);
        //         }
        //         echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        //     }
        // }
        // die();
    }
    // ELIMINAR USUARIO
    public function delUsuario()
    {
        // if ($_POST) {
        //     if ($_SESSION['permisosMod']['d']) {
        //         $intIdeUsuario = intval($_POST['ideUsuario']);
        //         $requestDelete = $this->model->deleteUsuario($intIdeUsuario);
        //         if ($requestDelete) {
        //             $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Usuario');
        //         } else {
        //             $arrResponse = array('status' => false, 'msg' => 'Error al eliminar al Usuario.');
        //         }
        //         echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        //     }
        // }
        // die();
    }
}
