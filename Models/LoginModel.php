<?php

class LoginModel extends Mysql
{
    private $intIdUsuario;
    private $strIdentificacion;
    private $strPassword;

    public function __construct()
    {
        parent::__construct();
    }

    // ===========================
    //   LOGIN USUARIO
    // ===========================
    public function loginUser(string $identificacion, string $password)
    {
        $this->strIdentificacion = $identificacion;
        $this->strPassword = $password;

        // Consulta segura
        $sql = "SELECT ideusuario, status 
                FROM tbl_usuarios 
                WHERE identificacion = ? 
                AND password = ? 
                AND status != 0";

        $request = $this->select($sql, array($this->strIdentificacion, $this->strPassword));

        return $request;
    }

    // ===========================
    //   CARGAR DATOS DE SESIÓN
    // ===========================
    public function sessionLogin(int $iduser)
    {
        $this->intIdUsuario = $iduser;

        $sql = "SELECT 
                    tu.ideusuario,
                    tu.identificacion,
                    tu.nombres,
                    tu.imgperfil,
                    r.idrol,
                    r.nombrerol,
                    tu.status
                FROM tbl_usuarios tu
                INNER JOIN rol r ON tu.rolid = r.idrol
                WHERE tu.ideusuario = ?";

        $request = $this->select($sql, array($this->intIdUsuario));

        // Validar que la consulta tenga datos
        if (!empty($request)) {
            $_SESSION['userData'] = $request;
        }

        return $request;
    }
}
