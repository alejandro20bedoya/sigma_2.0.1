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

    public function updateFotoPerfil(int $idUser, string $foto)
    {
        $sql = "UPDATE tbl_usuarios SET imgperfil=? WHERE ideusuario = $idUser";
        $arrData = array($foto);
        return $this->update($sql, $arrData);
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

    public function selectUsuarioperfil(int $ideusuario)
    {
        $this->intIdeUsuario = $ideusuario;
        $sql = "SELECT u.ideusuario,u.identificacion,u.nombres,u.apellidos,u.celular,u.correo,u.rolid,u.status,r.idrol,r.nombrerol
                FROM tbl_usuarios u
                INNER JOIN rol r
                ON u.rolid = r.idrol
                WHERE u.ideusuario = $this->intIdeUsuario";
        $request = $this->select($sql);
        return $request;
    }

    //ACTUALIZAR USUARIO
    public function updatePerfil(
        int $ideusuario,
        string $identificacion,
        string $nombres,
        string $apellidos,
        string $celular,
        string $correo,
        ?string $foto = null
    ) {
        // VALIDAR DUPLICADOS
        $sql = "SELECT ideusuario FROM tbl_usuarios
            WHERE ideusuario != ?
            AND (identificacion = ? OR correo = ? OR celular = ?)";

        $arrData = [
            $ideusuario,
            $identificacion,
            $correo,
            $celular
        ];

        $request = $this->select_all($sql, $arrData);

        if (!empty($request)) {
            return "exist";
        }

        // ACTUALIZAR
        if ($foto != null) {
            $sql = "UPDATE tbl_usuarios 
                SET identificacion = ?, 
                    nombres = ?, 
                    apellidos = ?, 
                    celular = ?, 
                    correo = ?, 
                    imgperfil = ?
                WHERE ideusuario = ?";
            $arrData = [
                $identificacion,
                $nombres,
                $apellidos,
                $celular,
                $correo,
                $foto,
                $ideusuario
            ];
        } else {
            $sql = "UPDATE tbl_usuarios 
                SET identificacion = ?, 
                    nombres = ?, 
                    apellidos = ?, 
                    celular = ?, 
                    correo = ?
                WHERE ideusuario = ?";
            $arrData = [
                $identificacion,
                $nombres,
                $apellidos,
                $celular,
                $correo,
                $ideusuario
            ];
        }

        return $this->update($sql, $arrData);
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
