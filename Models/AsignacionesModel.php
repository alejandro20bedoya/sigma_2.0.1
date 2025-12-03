<?php
class AsignacionesModel extends Mysql
{
    private $intIdeFicha;
    private $strNumeroFicha;
    private $intIdentificacion;
    private $intCodCompetencia;
    private $strNumeroHoras;
    private $strListadoMeses;
    private $intFichaPrograma;
    private $strHorasPendiente;

    private $strStatus;

    public function __construct()
    {
        parent::__construct();
    }

    // insertar ficha A LA BASE DE DATOS o guardar la ficha
    public function insertFicha(string $numeroficha, string $ideinstructor, string $codigocompetencia, string $numerohoras, string $Horaspendiente, string $listadomeses)
    {
        $this->strNumeroFicha = $numeroficha;
        $this->strIdeInstructor = $ideinstructor;
        $this->strCodigoCompetencia = $codigocompetencia;
        $this->strNumeroHoras = $numerohoras;
        $this->strHorasPendiente = $Horaspendiente;
        $this->strListadoMeses = $listadomeses;
        $return = 0;

        $sql = "SELECT 
            *
            FROM tbl_detalle_fichas 
            WHERE idecompetencia = '{$this->strCodigoCompetencia}' 
            AND mes = '{$this->strListadoMeses}'";

        $request = $this->select_all($sql);

        if (empty($request)) {
            $query_insert = "INSERT INTO tbl_detalle_fichas(programaficha, ideinstructor, idecompetencia, horasrealizadas, mes, totalhoras)
            VALUES(?,?,?,?,?,?)";
            $arrData = array(
                $this->strNumeroFicha,
                $this->strIdeInstructor,
                $this->strCodigoCompetencia,
                $this->strNumeroHoras,
                $this->strListadoMeses,
                $this->strHorasPendiente

            );
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }

        return $return;
    }

    // LISTADO DE LA TABLA
    public function selectFichas()
    {
        $sql = "SELECT 
                td.idedetalleficha,
                td.programaficha,
                td.ideinstructor,
                u.nombres,
                td.idecompetencia,
                c.nombrecompetencia, 
                c.horascompetencia,
                td.horasrealizadas,
                td.mes,
                td.totalhoras,
                td.status
            FROM tbl_detalle_fichas AS td
            left JOIN tbl_usuarios AS u
                ON td.ideinstructor = u.ideusuario
            left JOIN tbl_competencias AS c   
                ON td.idecompetencia = c.codigocompetencia
            WHERE td.status != 0";

        $request = $this->select_all($sql);
        return $request;
    }


    //informacion para asignar y ingresar nuevas horas
    public function selectFicha(int $idedetalleficha)
    {

        $sql = "SELECT 
                td.idedetalleficha,
                td.programaficha,
                td.ideinstructor,
                u.nombres,
                u.identificacion,
                td.idecompetencia,
                c.nombrecompetencia,
                c.horascompetencia,
                td.horasrealizadas,
                td.mes,
                td.totalhoras,  
                td.status,
                f.numeroficha,
                p.nombreprograma
            FROM tbl_detalle_fichas AS td
            LEFT JOIN tbl_usuarios AS u 
                ON td.ideinstructor = u.ideusuario
            LEFT JOIN tbl_competencias AS c 
                ON td.idecompetencia = c.codigocompetencia
            LEFT JOIN tbl_fichas AS f 
                ON td.programaficha = f.numeroficha
            LEFT JOIN tbl_programas AS p 
                ON f.programaide = p.ideprograma
            WHERE td.idedetalleficha = ?";


        $request = $this->select($sql, [$idedetalleficha]);
        return $request;
    }


    //ACTUALIZAR FICHA
    public function updateFicha(
        string $strNumeroFicha,   // código o número de ficha
        int $intInstructor,       // id del instructor
        string $strCodigoCompetencia,
        int $strNumeroHoras,
        int $Horaspendiente,
        string $strListadoMeses
    ) {
        // Asignar propiedades
        $this->strProgramaFicha = $strNumeroFicha;
        $this->intIdeInstructor = $intInstructor;
        $this->strIdeCompetencia = $strCodigoCompetencia;
        $this->intHorasRealizadas = $strNumeroHoras;
        $this->intHorasPendientes = $Horaspendiente ?? 0; // Si viene null, lo pone en 0
        $this->strMes = $strListadoMeses;


        // 🔍 Verificar si el instructor ya tiene una asignación para ese mes
        $sql = "SELECT * FROM tbl_detalle_fichas 
            WHERE ideinstructor = '{$this->intIdeInstructor}'
            AND status != 0";

        $data = $this->select_all($sql);

        $sql = "INSERT INTO tbl_detalle_fichas (programaficha, ideinstructor, idecompetencia, horasrealizadas, mes, totalhoras)
                VALUES (?, ?, ?, ?, ?, ?)";

        $arrData = array(
            $this->strProgramaFicha,
            $this->intIdeInstructor,
            $this->strIdeCompetencia,
            $this->intHorasRealizadas,
            $this->strMes,
            $this->intHorasPendientes,
        );
        $return = $this->insert($sql, $arrData);

        return $return;
    }

    // eliminar ficha
    public function deleteFicha(int $intIdeFicha)
    {
        $this->intIdeFicha = intval($intIdeFicha);

        $sql = " DELETE FROM tbl_detalle_fichas WHERE idedetalleficha = $this->intIdeFicha ";

        $request = $this->delete($sql, []);
        return $request;
    }

    // VISTA INFORMACIÓN FICHA
    public function selectIdeFicha($fichaprograma)
    {
        $this->intFichaPrograma = $fichaprograma;
        $sql = "SELECT
            f.ideficha, 
            f.numeroficha,
            f.programaide,
            f.usuarioide,
            f.status,
            p.nombreprograma
        FROM tbl_fichas AS f
        INNER JOIN tbl_programas AS p 
            ON p.ideprograma = f.programaide
        WHERE f.numeroficha = {$this->intFichaPrograma}          
        AND f.status != 0";

        $request = $this->select($sql);
        return $request;
    }

    // VISTA INFORMACIÓN INSTRUCTORES
    public function selectInstructor(int $identificacion)
    {
        $this->intIdentificacion = $identificacion;

        $sql = "SELECT 
                u.ideusuario,
                u.identificacion,
                u.nombres
            FROM tbl_usuarios AS u
            WHERE u.identificacion = $this->intIdentificacion 
            AND u.rolid = 4";

        $request = $this->select($sql);
        return $request;
    }


    //VISTA INFORMACIÓN COMPETENCIAS
    public function selectCompetencia(int $codigocompetencia)
    {

        $this->intCodCompetencia = $codigocompetencia;

        $sql = "SELECT 
            c.idecompetencia,
            c.codigocompetencia,
            c.nombrecompetencia,
            c.horascompetencia,
            td.horasrealizadas,
            c.status
        FROM tbl_competencias AS c
        LEFT  JOIN tbl_detalle_fichas AS td 
            ON td.idecompetencia = c.idecompetencia
        WHERE c.codigocompetencia = $this->intCodCompetencia 
        AND c.status != 0";

        $request = $this->select($sql);
        return $request;
    }
}
