<?php

class Perfil extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        session_regenerate_id(true);
        // if (empty($_SESSION['login'])) {
        //     header('Location: ' . base_url() . '/login');
        //     die();
        // }
        getPermisos(MUSUARIOS);
    }


    public function Perfil()
    {
        // if (empty($_SESSION['permisosMod']['r'])) {
        //     header("Location:" . base_url() . '/dashboard');
        // }
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

        if ($_POST) {

            // 1️⃣ ID OBLIGATORIO
            if (empty($_POST['ideUsuarioperfil'])) {
                echo json_encode([
                    'status' => false,
                    'msg' => 'ID de usuario no válido'
                ], JSON_UNESCAPED_UNICODE);
                die();
            }

            $idUsuario = intval($_POST['ideUsuarioperfil']);

            // 2️⃣ DATOS
            $identificacion = $_POST['txtIdentificacionUsuario'];
            $nombres         = $_POST['txtNombresUsuario'];
            $apellidos       = $_POST['txtApellidosUsuario'];
            $celular         = $_POST['txtCelularUsuario'];
            $correo          = $_POST['txtCorreoUsuario'];

            // 3️⃣ FOTO (OPCIONAL)
            $base64 = null;

            if (isset($_FILES['fotoUsuario']) && $_FILES['fotoUsuario']['error'] == 0) {

                $fotoTmp = $_FILES['fotoUsuario']['tmp_name'];
                $tipo    = mime_content_type($fotoTmp);
                $contenido = file_get_contents($fotoTmp);

                $base64 = "data:" . $tipo . ";base64," . base64_encode($contenido);
            }

            // 4️⃣ ACTUALIZAR PERFIL
            $request = $this->model->updatePerfil(
                $idUsuario,
                $identificacion,
                $nombres,
                $apellidos,
                $celular,
                $correo,
                $base64
            );

            // 5️⃣ RESPUESTA
            if ($request > 0) {

                // Si es el usuario logueado, actualiza sesión
                if ($idUsuario == $_SESSION['idUser'] && $base64 != null) {
                    $_SESSION['userData']['imgperfil'] = $base64;
                }

                $arrResponse = [
                    'status' => true,
                    'msg' => 'Perfil actualizado correctamente'
                ];
            } else {
                $arrResponse = [
                    'status' => false,
                    'msg' => 'No se pudo actualizar el perfil'
                ];
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            die();
        }
    }


    public function setEditperfil()
    {
        // Mostrar errores en caso de que existan (para desarrollo)
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        if ($_POST) {

            // Recibir datos del formulario
            $idUsuario      = intval($_POST['ideUsuarioperfil']);
            $identificacion = strClean($_POST['txtIdentificacionUsuario']);
            $nombres        = strClean($_POST['txtNombresUsuario']);
            $apellidos      = strClean($_POST['txtApellidosUsuario']);
            $celular        = strClean($_POST['txtCelularUsuario']);
            $correo         = strClean($_POST['txtCorreoUsuario']);
            $status         = isset($_POST['listStatus']) ? intval($_POST['listStatus']) : 1;

            // Llamar al modelo para actualizar (sin foto)
            $request = $this->model->updateUsuario(
                $idUsuario,
                $identificacion,
                $nombres,
                $apellidos,
                $celular,
                $correo,
                $status,
                null // foto = null porque no se actualiza aquí
            );

            // Preparar la respuesta
            if ($request) {
                $arrResponse = [
                    'status' => true,
                    'msg'    => 'Usuario actualizado correctamente',
                    'data'   => [
                        'ideusuario'     => $idUsuario,
                        'identificacion' => $identificacion,
                        'nombres'        => $nombres,
                        'apellidos'      => $apellidos,
                        'celular'        => $celular,
                        'correo'         => $correo
                    ]
                ];
            } else {
                $arrResponse = [
                    'status' => false,
                    'msg'    => 'No se pudo actualizar el usuario'
                ];
            }

            // Devolver JSON limpio
            header('Content-Type: application/json');
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }

        die();
    }


    // lista de la tabla usuarios
    public function getPerfiles()
    {
        // if ($_SESSION['permisosMod']['r']) {

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
                // if ($_SESSION['permisosMod']['r']) {
                    $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo('
                        . $arrData[$i]['ideusuario'] . ')" title="Ver Usuario">
                <i class="far fa-eye"></i></button>';
                // }

                // if ($_SESSION['permisosMod']['u']) {
                    $btnEdit = '<button class="btn btn-warning btn-sm" onClick="fntEditInfo(this,'
                        . $arrData[$i]['ideusuario'] . ')" title="Editar Usuario">
                <i class="fas fa-pencil-alt"></i></button>';
                // }

                // if ($_SESSION['permisosMod']['d']) {
                //     $btnDelete = '<button class="btn btn-danger btn-sm btnDelRol" onClick="fntDelInfo('
                //         . $arrData[$i]['ideusuario'] . ')" title="Eliminar Usuario">
                // <i class="bi bi-trash3"></i></button>';
                // }

                // OPCIONES
                $arrData[$i]['options'] = htmlspecialchars_decode(
                    '<div class="text-center">'
                        . $btnView . ' ' . $btnEdit . ' ' . $btnDelete .
                        '</div>'
                );
            }

            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        // }
        die();
    }

    // editar usuario
    public function getUsuarioperfil($ideusuario)
    {
        // if ($_SESSION['permisosMod']['r']) {
            $ideusuario = intval($ideusuario);
            if ($ideusuario > 0) {
                $arrData = $this->model->selectUsuarioperfil($ideusuario);
                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                } else {
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        // }
        die();
    }
    // editar usuario
    public function getUsuario($ideusuario)
    {
        // if ($_SESSION['permisosMod']['r']) {
            $ideusuario = intval($ideusuario);
            if ($ideusuario > 0) {
                $arrData = $this->model->selectUsuarioperfil($ideusuario);
                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                } else {
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        // }
        die();
    }

}
