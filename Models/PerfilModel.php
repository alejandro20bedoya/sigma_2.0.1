<?php
class PerfilModel extends Mysql
{
    private $intIdeUsuario;
    private $strIdentificacionUsuario;
    private $strNombresUsuario;  /// sonas de cambio 
    private $strApellidosUsuario;
    private $strCelularUsuario;
    private $strCorreoUsuario;
    private $strPassword;
    private $strRolUsuario;
    private $strFotoUsuario;
    private $strStatusUsuario;

    public function __construct()
    {
        parent::__construct();
    }

    public function insertPerfil(
        string $fotoUsuario,
        
    ) {
        $this->strFotoUsuario = $fotoUsuario;

        // Consulta directamente para insertar, sin verificar duplicados
        $query_insert = "INSERT INTO tbl_usuarios(imgperfil) VALUES(?)";

        $arrData = array(
            $this->strFotoUsuario // Ahora se guarda la foto
        );

        $request_insert = $this->insert($query_insert, $arrData);
        return $request_insert; // Devuelve el ID del usuario insertado o false si falla
    }

    // LISTADO DE LA TABLA
    public function selectPerfil()
    {
        $idUser = $_SESSION['idUser'];

        $sql = "SELECT u.ideusuario,u.identificacion,u.nombres,u.apellidos,u.celular,u.correo,u.rolid,u.status,r.idrol,r.nombrerol   
            FROM tbl_usuarios u 
            INNER JOIN rol r ON u.rolid = r.idrol
            WHERE u.ideusuario = $idUser";

        $request = $this->select_all($sql);
        return $request;
    }



    public function selectUsuario(int $ideusuario)
    {
        $this->intIdeUsuario = $ideusuario;
        $sql = "SELECT  u.ideusuario,u.identificacion,u.nombres,u.apellidos,u.celular,u.correo,u.rolid,u.status,r.idrol,r.nombrerol
                FROM tbl_usuarios u
                INNER JOIN rol r
                ON u.rolid = r.idrol
                WHERE u.ideusuario = $this->intIdeUsuario";
        $request = $this->select($sql);
        return $request;
    }

    //ACTUALIZAR USUARIO
    public function updateUsuario(
        int $ideusuario,
        string $identificacion,
        string $nombres,
        string $apellidos,
        string $celular,
        string $correo,
        string $rol,
        string $status
    ) {

        $this->intIdeUsuario = $ideusuario;
        $this->strIdentificacionUsuario = $identificacion;
        $this->strNombresUsuario = $nombres;
        $this->strApellidosUsuario = $apellidos;
        $this->strCelularUsuario = $celular;
        $this->strCorreoUsuario = $correo;
        $this->strRolUsuario = $rol;
        $this->strStatus = $status;

        $sql = "SELECT * FROM tbl_usuarios WHERE (identificacion = '{$this->strIdentificacionUsuario}' AND ideusuario != $this->intIdeUsuario)
        OR (nombres = '{$this->strNombresUsuario}' AND ideusuario != $this->intIdeUsuario)
        OR (apellidos = '{$this->strApellidosUsuario}' AND ideusuario != $this->intIdeUsuario)
        OR (celular = '{$this->strCelularUsuario}' AND ideusuario != $this->intIdeUsuario)
        OR (correo = '{$this->strCorreoUsuario}' AND ideusuario != $this->intIdeUsuario)
        OR (rolid = '{$this->strRolUsuario}' AND ideusuario != $this->intIdeUsuario)";
        $request != $this->select_all($sql);

        if (empty($request)) {
            // TODO PENDIENTE LA VALIDACIÓN SI EL CODIGO ES IGUAL QUE EL CODIGO DE OTRO USUARIO
            if (($this->strIdentificacionUsuario != "" or $this->strIdentificacionUsuario !=  $this->strIdentificacionUsuario)) {

                $sql = "UPDATE tbl_usuarios SET identificacion=?,nombres=?,apellidos=?,celular=?,correo=?, rolid=?, status=?
						WHERE ideusuario = $this->intIdeUsuario ";

                $arrData = array(
                    $this->strIdentificacionUsuario,
                    $this->strNombresUsuario,
                    $this->strApellidosUsuario,
                    $this->strCelularUsuario,
                    $this->strCorreoUsuario,
                    $this->strRolUsuario,
                    $this->strStatus
                );
            }
            $request = $this->update($sql, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }
    //eliminar usuario
    public function deleteUsuario(int $intIdeUsuario)
    {
        $this->intIdeUsuario = intval($intIdeUsuario); // seguridad

        // Consulta DELETE
        $sql = "DELETE FROM tbl_usuarios WHERE ideusuario = $this->intIdeUsuario";

        // Ejecutamos la consulta
        $request = $this->delete($sql, []); // ⚡ dependiendo de tu clase, pasa array vacío si exige 2 parámetros

        return $request;
    }

    public function selectRoles()
    {
        $sql = "SELECT idrol, nombrerol, descripcion, status 
            FROM rol 
            WHERE status != 0";
        $request = $this->select_all($sql);
        return $request;
    }
}
