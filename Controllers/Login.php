<?php

class Login extends Controllers
{
    public function __construct()
    {
        session_start();
        if (isset($_SESSION['login'])) {
            header('Location: ' . base_url() . '/dashboard');
            die();
        }
        parent::__construct();
    }

    public function login()
    {
        $data['page_tag'] = "Iniciar Sesión - Sigma";
        $data['page_title'] = "Sigma";
        $data['page_name'] = "login";
        $data['page_functions_js'] = "functions_login.js";
        $this->views->getView($this, "login", $data);
    }

    public function loginUser()
    {
        // dep($_POST);
        if ($_POST) {
            if (empty($_POST['txtIdentificacion']) || empty($_POST['txtPassword'])) {
                $arrResponse = array('status' => false, 'msg' => 'Error de datos');
            } else {
                $strIdentificacion = strtolower(strClean($_POST['txtIdentificacion']));
                $strPassword = hash("SHA256", $_POST['txtPassword']);
                $requestUser = $this->model->loginUser($strIdentificacion, $strPassword);
                if (empty($requestUser)) {
                    $arrResponse = array('status' => false, 'msg' => 'La identificación y/o contraseña es incorrecta');
                } else {
                    $arrData = $requestUser;
                    if ($arrData['status'] == 1) {
                        $_SESSION['idUser'] = $arrData['ideusuario'];
                        $_SESSION['login'] = true;

                        $arrData = $this->model->sessionLogin($_SESSION['idUser']);
                        sessionUser($_SESSION['idUser']);
                        $arrResponse = array('status' => true, 'msg' => 'ok');
                    } else {
                        $arrResponse = array('status' => false, 'msg' => 'Usuario inactivo');
                    }
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

}