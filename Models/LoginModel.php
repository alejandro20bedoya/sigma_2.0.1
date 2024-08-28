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

    public function loginUser(string $identificacion, string $password)
    {
        $this->strIdentificacion = $identificacion;
        $this->strPassword = $password;
        $sql = "SELECT ideusuario,status FROM tbl_usuarios WHERE
					identificacion = '$this->strIdentificacion' and
					password = '$this->strPassword' and
					status != 0 ";
        $request = $this->select($sql);
        return $request;
    }

    public function sessionLogin(int $iduser)
    {
        $this->intIdUsuario = $iduser;
        //BUSCAR ROL
        $sql = "SELECT tu.ideusuario,
							tu.identificacion,
							tu.imgperfil,
							r.idrol,
                            r.nombrerol,
							tu.status
					FROM tbl_usuarios tu
					INNER JOIN rol r
					ON tu.rolid = r.idrol
					WHERE tu.ideusuario = $this->intIdUsuario";
        $request = $this->select($sql);
        $_SESSION['userData'] = $request;
        return $request;
    }


}