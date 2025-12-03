<?php

class Dashboard extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        //session_regenerate_id(true);
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
        getPermisos(RADMINISTRADOR);
    }
    // Dashboard principal 
    public function dashboard()
    {
        $data['page_id'] = 2;
        $data['page_tag'] = "Administrador";
        $data['page_title'] = " Administrador";
        $data['page_name'] = "Administrador";
        $data['page_functions_js'] = "functions_dashboard.js";
        $data['usuarios'] = $this->model->cantUsuarios();
        $data['programas'] = $this->model->cantProgramas();
        $data['progresoAsignaciones'] = $this->model->progresoAsignaciones();
        $data['fichas'] = $this->model->getProgramasFichas();
        $data['competencias'] = $this->model->Competeciaficha();


        if ($_SESSION['userData']['idrol'] == RCOORDINADOR) {
            $this->views->getView($this, "dashboardCoordinador", $data);
        } else {
            $this->views->getView($this, "dashboard", $data);
        }
    }

    public function getcompetenciasficha()
    {
        $competencias = $this->model->Competeciaficha();

        foreach ($competencias as &$c) {
            $c->estado = ($c->totalhoras == 0) ? "Completado" : "En progreso";
        }

        echo json_encode($competencias, JSON_UNESCAPED_UNICODE);
    }


    // Progreso de asignaciones
    public function getProgresoAsignacion($programaFicha)
    {
        $programaFicha = strClean($programaFicha);
        $progreso = $this->model->progresoAsignaciones($programaFicha);
        echo json_encode(["progreso" => $progreso], JSON_UNESCAPED_UNICODE);
        die();
    }
}
