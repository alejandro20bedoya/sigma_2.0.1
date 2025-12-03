<?php
class CompetenciasModel extends Mysql
{
    private $intIdeCompetencia;
    private $strCodigoCompetencia;
    private $strNombreCompetencia;
    private $strHorasCompetencia;
    private $strCodigoPrograma;
    private $strTipocompetencia;
    private $strFicha;
    private $strStatus;


    public function __construct()
    {
        parent::__construct();
    }

    // // TODO CONSULTA DE LOS PROGRAMAS DE FORMACIÓN
    public function selectProgramas()
    {
        $sql = "SELECT * FROM tbl_programas
            WHERE status != 0 ORDER BY nombreprograma ASC";

        $request = $this->select_all($sql);
        return $request;
    }

    public function selectProgramasEditar($codigoprograma)
    {
        $sql = "SELECT * FROM tbl_programas as m, tbl_competencias as d
        WHERE m.codigoprograma = d.programacodigo ";

        $request = $this->select_all($sql);
        return $request;
    }

    /// INSERTAR COMPETENCIAS EN LAS TABLAS LO QUE BIENEN DEL FORMULARIO
    public function insertCompetencia(string $codigocompetencia, string $ficha, string $nombrecompetencia, string $tipocompetencia, string $horascompetencia, string $ideprograma)
    {
        $this->strCodigoCompetencia = $codigocompetencia;
        $this->strFicha             = $ficha;               // aquí debería llegar el ideficha
        $this->strNombreCompetencia = $nombrecompetencia;
        $this->strTipocompetencia   = $tipocompetencia;
        $this->strHorasCompetencia  = $horascompetencia;
        $this->strCodigoPrograma    = $ideprograma;         // ojo: este es codigoprograma, no ideprograma

        $return = 0;

        // Verificar si la competencia ya existe por código
        $sql1 = "SELECT * FROM tbl_competencias WHERE codigocompetencia = '{$this->strCodigoCompetencia}'";
        $request1 = $this->select_all($sql1);

        // Verificar si el programa existe
        $sql2 = "SELECT * FROM tbl_programas WHERE codigoprograma = '{$this->strCodigoPrograma}'";
        $request2 = $this->select_all($sql2);

        if (empty($request1) && !empty($request2)) {
            $query_insert = "INSERT INTO tbl_competencias
            (codigocompetencia, tipocompetencia, nombrecompetencia, horascompetencia, fichaide, programacodigo, status)
            VALUES (?, ?, ?, ?, ?, ?, 1)";

            $arrData = array(
                $this->strCodigoCompetencia,
                $this->strTipocompetencia,
                $this->strNombreCompetencia,
                $this->strHorasCompetencia,
                $this->strFicha,          // aquí se guarda el ideficha
                $this->strCodigoPrograma  // aquí se guarda el codigoprograma
            );

            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function selectCompetencias()
    {
            $sql = "SELECT 
            tc.idecompetencia,
            tc.codigocompetencia,
            tc.tipocompetencia,
            tc.nombrecompetencia,
            tc.horascompetencia,
            tc.fichaide,
            tf.numeroficha, 
            tc.programacodigo,
            tc.status AS status_competencia,
            tp.ideprograma,
            tp.codigoprograma,
            tp.nivelprograma,
            tp.nombreprograma,
            tp.horasprograma,
            td.totalhoras,
            tp.status AS status_programa
        FROM tbl_competencias tc
        LEFT JOIN tbl_programas tp 
            ON tp.codigoprograma = tc.programacodigo
        LEFT JOIN tbl_fichas tf
            ON tf.ideficha = tc.fichaide
        LEFT JOIN (
            SELECT td1.*
            FROM tbl_detalle_fichas td1
            INNER JOIN (
                SELECT idecompetencia, MAX(created_at) AS max_fecha
                FROM tbl_detalle_fichas
                GROUP BY idecompetencia
            ) td2 
            ON td1.idecompetencia = td2.idecompetencia 
            AND td1.created_at = td2.max_fecha
        ) td ON td.idecompetencia = tc.codigocompetencia
        WHERE tc.status != 0
        ORDER BY tc.codigocompetencia ASC";

        $request = $this->select_all($sql);

        if (!$request) {
            error_log('DEBUG - SQL falló: ' . $sql);
        } else {
            error_log('DEBUG - Resultado selectCompetencias: ' . print_r($request, true));
        }

        return $request;
    }


    //VISTA DE IEDETAR COMPETENCIA BUSCANDO CON EL ID DE COMPETENCIA
    public function selectCompetencia(int $idecompetencia)
    {
        $sql = "SELECT 
            tc.idecompetencia,
            tc.codigocompetencia,
            tc.tipocompetencia,
            tc.nombrecompetencia,
            tc.horascompetencia,
            tc.fichaide,
            tf.numeroficha, 
            tc.programacodigo,       -- 👈 este sí existe en tbl_competencias
            tp.codigoprograma,       -- 👈 este viene de tbl_programas
            tp.nombreprograma
            FROM tbl_competencias tc
            LEFT JOIN tbl_fichas tf 
                ON tf.ideficha = tc.fichaide
            LEFT JOIN tbl_programas tp 
                ON tp.codigoprograma = tc.programacodigo
            WHERE tc.idecompetencia = $idecompetencia";

        return $this->select($sql);
    }

    //ACTUALIZAR Competencia
    public function updateCompetencia(int $ideCompetencia, string $codigocompetencia, string $ficha, string $nombrecompetencia, string $tipocompetencia, string $horascompetencia, string $ideprograma)
    {

        $this->intIdeCompetencia = $ideCompetencia;
        $this->strCodigoCompetencia = $codigocompetencia;
        $this->strFicha             = $ficha;               // aquí debería llegar el ideficha
        $this->strNombreCompetencia = $nombrecompetencia;
        $this->strTipocompetencia   = $tipocompetencia;
        $this->strHorasCompetencia  = $horascompetencia;
        $this->strCodigoPrograma    = $ideprograma;

        $sql = "SELECT * FROM tbl_competencias WHERE (codigocompetencia = '{$this->strCodigoCompetencia}' AND idecompetencia != $this->intIdeCompetencia)";
        $request = $this->select_all($sql);

        if (empty($request)) {
            // TODO PENDIENTE LA VALIDACIÓN SI EL CODIGO ES IGUAL QUE EL CODIGO DE OTRO PROGRAMA DE FORMACIÓN
            if (($this->strCodigoCompetencia != "" or $this->strCodigoCompetencia !=  $this->strCodigoCompetencia)) {

                $sql = "UPDATE tbl_competencias SET codigocompetencia=?, nombrecompetencia=?, horascompetencia=?, programacodigo=?
						WHERE idecompetencia = $this->intIdeCompetencia";

                $arrData = array(
                    $this->strCodigoCompetencia,
                    $this->strNombreCompetencia,
                    $this->strHorasCompetencia,
                    $this->strCodigoPrograma
                );
            }

            $request = $this->update($sql, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }

    public function deleteCompetencia(int $intIdeCompetencia)
    {
        $this->intIdeCompetencia = $intIdeCompetencia; // seguridad

        // Consulta DELETE
        $sql = "DELETE FROM tbl_competencias WHERE idecompetencia = $this->intIdeCompetencia";

        // Ejecutamos la consulta
        $request = $this->delete($sql, []); // ⚡ dependiendo de tu clase, pasa array vacío si exige 2 parámetros

        return $request;
    }

    //VISTA INFORMACIÓN PROGRAMA
    public function selectPrograma(int $codprograma)
    {
        $this->strCodigoPrograma = $codprograma;

        $sql = "SELECT *
          FROM tbl_programas
            WHERE codigoprograma = '{$this->strCodigoPrograma}'";

        $request = $this->select($sql);

        return $request;
    }

}
