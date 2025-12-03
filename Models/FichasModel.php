<?php
class FichasModel extends Mysql
{
    private $intIdeFicha;       // ideficha (PK)
    private $intProgramaIde;    // programaide (FK a tbl_programas)
    private $intNumeroFicha;    // numeroficha
    private $intUsuarioIde;     // usuarioide (FK a tbl_usuarios → instructor/líder)
    private $intStatus;
    private $intCodPrograma;
    private $intIdentificacion;

    public function __construct()
    {
        parent::__construct();
    }


    // lo que se va a ver en la tabla de fichas 
    public function selectFichas()
    {
        $sql = "SELECT 
                tf.ideficha,
                tf.numeroficha,
                tf.status AS status_ficha,
                tp.nombreprograma,
                tu.nombres
            FROM tbl_fichas tf
            LEFT JOIN tbl_programas tp ON tp.ideprograma = tf.programaide
            LEFT JOIN tbl_usuarios tu  ON tu.ideusuario = tf.usuarioide";

        // Ejecutar y debug
        $request = $this->select_all($sql);
        error_log('DEBUG - SQL ejecutado selectFichas: ' . $sql);
        error_log('DEBUG - Resultado selectFichas: ' . print_r($request, true));

        return $request;
    }

    //VISTA INFORMACIÓN PROGRAMA
    public function selectFicha(int $ideficha)
    {
        $this->intIdeFicha = $ideficha;

        $sql = "SELECT 
            tf.ideficha,
            tf.numeroficha AS numeroficha,   -- 👈 alias obligatorio
            tf.usuarioide,
            tf.programaide,
            tf.status AS status_ficha,
            tp.ideprograma,
            tp.codigoprograma,
            tp.nivelprograma,
            tp.nombreprograma,
            tp.horasprograma,
            tp.status AS status_programa,
            tu.ideusuario,
            tu.identificacion AS identificacion,  -- 👈 alias obligatorio
            tu.nombres,
            tu.apellidos,
            tu.imgperfil,
            tu.rolid,
            tu.status AS status_usuario
        FROM tbl_fichas tf
        INNER JOIN tbl_programas tp 
            ON tp.ideprograma = tf.programaide
        INNER JOIN tbl_usuarios tu 
            ON tu.ideusuario = tf.usuarioide
        WHERE tf.ideficha = ?";


        $request = $this->select($sql, [$this->intIdeFicha]);
        return $request;
    }


    /// insertar ficha A LA BASE DE DATOS 
    public function insertFicha(int $programaide, int $numeroficha, int $usuarioide)
    {
        $this->intProgramaIde = $programaide;
        $this->intNumeroFicha = $numeroficha;
        $this->intUsuarioIde  = $usuarioide;

        $return = 0;

        // Escapar valores
        $numeroEscaped   = addslashes($this->intNumeroFicha);
        $programaEscaped = addslashes($this->intProgramaIde);
        $usuarioEscaped  = addslashes($this->intUsuarioIde);

        // Validar si ya existe la ficha
        $sql = "SELECT ideFicha FROM tbl_fichas WHERE numeroficha = '$numeroEscaped' LIMIT 1";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $query_insert = "INSERT INTO tbl_fichas(programaide, numeroficha, usuarioide) 
                         VALUES('$programaEscaped', '$numeroEscaped', '$usuarioEscaped')";

            $request_insert = $this->insert($query_insert, []); 
            // array vacío porque tu método exige 2 argumentos
            $return = $request_insert;
        } else {
            $return = "exist";
        }

        return $return;
    }

    //ACTUALIZAR FICHA
    public function updateFicha(int $ideficha, int $codigoprograma, int $codigoficha, int $ideinstructor, int $status)
    {

        $this->intIdeFicha = $ideficha;
        $this->intProgramaIde = $codigoprograma;
        $this->intNumeroFicha = $codigoficha;
        $this->intUsuarioIde = $ideinstructor;
        $this->intStatus = $status;

        $sql = "UPDATE tbl_fichas SET programaide = ?, numeroficha = ?, usuarioide = ?, status = ? WHERE ideficha = ? ";
        $arrData = [$this->intProgramaIde, $this->intNumeroFicha, $this->intUsuarioIde, $this->intStatus, $this->intIdeFicha,];
        $request = $this->update($sql, $arrData);

        return $request;
    }

    //eliminar ficha
    public function deleteFicha(int $intIdeFicha)
    {
        $this->intIdeFicha = intval($intIdeFicha); // seguridad

        // Consulta DELETE
        $sql = "DELETE FROM tbl_fichas WHERE ideficha = $this->intIdeFicha";

        // Ejecutamos la consulta
        $request = $this->delete($sql, []); // ⚡ dependiendo de tu clase, pasa array vacío si exige 2 parámetros

        return $request;
    }



    //VISTA INFORMACIÓN PROGRAMA
    public function selectPrograma(string $codprograma)
    {
        $this->intCodPrograma = $codprograma;
        $sql = "SELECT *
            FROM tbl_programas
            WHERE codigoprograma = '{$this->intCodPrograma}'";
        $request = $this->select($sql);
        return $request;
    }

    //VISTA INFORMACIÓN PROGRAMA
    public function selectInstructor(string $identificacion)
    {
        $sql = "SELECT identificacion, nombres
            FROM tbl_usuarios
            WHERE identificacion = $identificacion 
              AND rolid = 4";

        $request = $this->select($sql); // ✅ ejecuta la consulta
        return $request;
    }
}
