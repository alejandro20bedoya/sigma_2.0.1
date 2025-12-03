<?php
class DashboardModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cantUsuarios()
    {
        $sql = "SELECT COUNT(*) as total FROM tbl_usuarios WHERE status != 0 AND rolid !=0";
        $request = $this->select($sql);
        $total = $request['total'];
        return $total;
    }
    public function cantProgramas()
    {
        $sql = "SELECT COUNT(*) as total FROM tbl_programas WHERE status != 0 ";
        $request = $this->select($sql);
        $total = $request['total'];
        return $total;
    }

    public function progresoAsignaciones($programaFicha = null)
    {
        // Si no se pasa ficha, intentar obtener alguna activa
        if (empty($programaFicha)) {
            $sqlFicha = "SELECT programaficha 
                     FROM tbl_detalle_fichas 
                     WHERE status = 1 
                     ORDER BY created_at DESC 
                     LIMIT 1";
            $ficha = $this->select($sqlFicha);
            if (!$ficha) {
                return 0; // No hay registros
            }
            $programaFicha = $ficha['programaficha'];
        }

        // Consulta principal
        $sql = "SELECT 
                c.horascompetencia AS HorasTotales,
                (
                    SELECT f.totalhoras 
                    FROM tbl_detalle_fichas AS f
                    WHERE f.programaficha = '{$programaFicha}' 
                    ORDER BY f.created_at DESC 
                    LIMIT 1
                ) AS HorasRestantes
            FROM tbl_detalle_fichas AS df
            INNER JOIN tbl_competencias AS c 
                ON c.codigocompetencia = df.idecompetencia
            WHERE df.programaficha = '{$programaFicha}'
            LIMIT 1";

        $request = $this->select($sql);

        $horasTotales = isset($request['HorasTotales']) ? (float)$request['HorasTotales'] : 0;
        $horasRestantes = isset($request['HorasRestantes']) ? (float)$request['HorasRestantes'] : 0;

        if ($horasTotales <= 0) {
            return 0;
        }

        // Calcular porcentaje
        $horasCompletadas = $horasTotales - $horasRestantes;
        $progreso = ($horasCompletadas / $horasTotales) * 100;

        if ($progreso < 0) $progreso = 0;
        if ($progreso > 100) $progreso = 100;

        return round($progreso, 0);
    }

    // Obtener fichas activas
    public function getProgramasFichas()
    {
        $sql = "SELECT DISTINCT programaficha 
            FROM tbl_detalle_fichas 
            WHERE status = 1";
        return $this->select_all($sql);
    }

    public function Competeciaficha()
    {
        $sql = "
        SELECT idecompetencia,
               codigocompetencia,
               horascompetencia,
               totalhoras
        FROM (
            SELECT 
                c.idecompetencia,
                c.codigocompetencia, 
                c.horascompetencia,
                df.totalhoras,
                ROW_NUMBER() OVER (
                    PARTITION BY c.codigocompetencia 
                    ORDER BY c.idecompetencia ASC
                ) AS rn
            FROM tbl_competencias AS c 
            LEFT JOIN tbl_detalle_fichas AS df 
                ON c.codigocompetencia = df.idecompetencia
        ) AS x
        WHERE rn = 1";

        $competencias = $this->select_all($sql);    
        return $competencias;
    }
}
