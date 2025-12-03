<?php
class ProgramasModel extends Mysql
{
    private $intIdePrograma;
    private $strCodigoPrograma;
    private $strNivelPrograma;
    private $strNombrePrograma;
    private $strHorasPrograma;
    private $strStatus;


    public function __construct()
    {
        parent::__construct();
    }

    public function insertPrograma(
        string $codigo,
        string $nivel,
        string $nombreprograma,
        string $horasprograma,
        String $status
    ) {
        $this->strCodigoPrograma = $codigo;
        $this->strNivelPrograma = $nivel;
        $this->strNombrePrograma = $nombreprograma;
        $this->strHorasPrograma = $horasprograma;
        $this->strStatus = $status;

        $return = 0;
        $sql = "SELECT * FROM tbl_programas WHERE
				codigoprograma = '{$this->strCodigoPrograma}'";
        $request = $this->select_all($sql);

        if (empty($request)) {

            $query_insert = "INSERT INTO tbl_programas(codigoprograma,nivelprograma,nombreprograma,horasprograma,status)
            VALUES(?,?,?,?,?)";

            $arrData = array(
                $this->strCodigoPrograma,
                $this->strNivelPrograma,
                $this->strNombrePrograma,
                $this->strHorasPrograma,
                $this->strStatus
            );

            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    // LISTADO DE LA TABLA
    public function selectProgramas()
    {
        $sql = "SELECT 
                ideprograma,
                codigoprograma,
                nivelprograma,
                nombreprograma,
                horasprograma,
                status AS status_ficha
            FROM tbl_programas";
        $request = $this->select_all($sql);
        return $request;
    }

    //VISTA INFORMACIÓN PROGRAMA
    public function selectPrograma(int $ideprograma)
    {
        $this->intIdePrograma = $ideprograma;
        $sql = "SELECT *
    			FROM tbl_programas
    			WHERE ideprograma = $this->intIdePrograma";
        $request = $this->select($sql);
        return $request;
    }

    //ACTUALIZAR PROGRAMA
    public function updatePrograma(
        int $idePrograma,
        string $codigo,
        string $nivel,
        string $nombreprograma,
        string $horasprograma,
        String $status
    ) {

        $this->intIdePrograma = $idePrograma;
        $this->strCodigoPrograma = $codigo;
        $this->strNivelPrograma = $nivel;
        $this->strNombrePrograma = $nombreprograma;
        $this->strHorasPrograma = $horasprograma;
        $this->strStatus = $status;

        $sql = "SELECT * FROM tbl_programas WHERE (codigoprograma = '{$this->strCodigoPrograma}' AND ideprograma != $this->intIdePrograma)
        OR (nombreprograma = '{$this->strNombrePrograma}' AND ideprograma != $this->intIdePrograma)";

        $request = $this->select_all($sql);

        if (empty($request)) {
            // TODO PENDIENTE LA VALIDACIÓN SI EL CODIGO ES IGUAL QUE EL CODIGO DE OTRO PROGRAMA
            if (($this->strCodigoPrograma != "" or $this->strCodigoPrograma !=  $this->strCodigoPrograma)) {

                $sql = "UPDATE tbl_programas SET codigoprograma=?, nivelprograma=?, nombreprograma=?, horasprograma=?, status=?
						WHERE ideprograma = $this->intIdePrograma ";

                $arrData = array(
                    $this->strCodigoPrograma,
                    $this->strNivelPrograma,
                    $this->strNombrePrograma,
                    $this->strHorasPrograma,
                    $this->strStatus
                );
            }
            $request = $this->update($sql, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }

    public function deletePrograma(int $intIdePrograma)
    {
        $this->intIdePrograma = $intIdePrograma;
        $sql = "DELETE FROM tbl_programas WHERE ideprograma = $this->intIdePrograma";
        $request = $this->delete($sql);
        return $request;
    }
}
