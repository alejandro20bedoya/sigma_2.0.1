<?php
require_once "../Config/Config.php";       // Configuración de conexión
require_once "../vendor/autoload.php";     // Carga automática de PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

// ==========================
// 🔹 CONEXIÓN A MYSQL
// ==========================
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// ==========================
// 🔹 PARÁMETRO DEL REPORTE
// ==========================
$tipo = $_GET['tipo'];

// ==========================
// 🔹 CREAR HOJA DE EXCEL
// ==========================
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// ==========================
// 🔹 CONFIGURACIÓN SEGÚN EL TIPO
// ==========================
if ($tipo === 'asignaciones') {
    $titulo = 'Reporte de Asignaciones';
    $encabezados = [
        'ID',
        'Programa',
        'Instructor',
        'Competencia',
        'Horas Realizadas',
        'Total Horas',
        'Mes',
        'Estado',
        'Fecha'
    ];

    // Consulta SQL
    $sql = "SELECT 
                idedetalleficha AS ID,
                programaficha AS Programa,
                ideinstructor AS Instructor,
                idecompetencia AS Competencia,
                horasrealizadas AS Horas_Realizadas,
                totalhoras AS Total_Horas,
                mes AS Mes,
                CASE WHEN status = 1 THEN 'Activo' ELSE 'Inactivo' END AS Estado,
                created_at AS Fecha
            FROM tbl_detalle_fichas";


    // 🔸 Título (unir de C1 a K2)
    $sheet->mergeCells('C2:K3');
    $sheet->setCellValue('C2', $titulo);
    $sheet->getStyle('C2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('FF003366');
    $sheet->getStyle('C2:K3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    $sheet->getStyle('C2:K2')->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setRGB('E0E0E0'); /// color gris para el fondo de la celda

    //  Espacio entre título y encabezado (fila vacía 3)
    $sheet->setCellValue('C3', ''); // fila vacía solo para espacio visual

    //  Encabezados (fila 4, desde C)
    $sheet->fromArray($encabezados, NULL, 'C4');

    // Estilo del encabezado
    $sheet->getStyle('C4:K4')->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '41B300']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    // ==========================
    // 🔹 CONSULTA Y LLENADO DE DATOS
    // ==========================
    $result = $conexion->query($sql);
    $fila = 5; // ⬅️ Comienza una fila más abajo (debajo del encabezado)

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $columna = 'C'; // Inicia en C
            foreach ($row as $valor) {
                $sheet->setCellValue($columna . $fila, $valor);
                $columna++;
            }
            $fila++;
        }
    } else {
        $sheet->setCellValue('C5', 'No se encontraron registros');
        $sheet->mergeCells('C5:K5');
        $sheet->getStyle('C5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    // ==========================
    // 🔹 ESTILOS FINALES
    // ==========================

    // Ajustar ancho automático (C → K)
    foreach (range('C', 'K') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Bordes para toda la tabla
    $ultimaFila = $fila - 1;
    $sheet->getStyle("C4:K$ultimaFila")->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    // Centrar el texto de los datos
    $sheet->getStyle("C5:K$ultimaFila")
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // ==========================
    // 🔹 DESCARGA DEL ARCHIVO
    // ==========================
    $nombreArchivo = $tipo . ".xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"$nombreArchivo\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
} else if ($tipo === 'programas') {
    $titulo = 'Reporte de Programas';
    $encabezados = ['ID', 'Código', 'Nivel', 'Nombre', 'Horas', 'Estado'];

    $sql = "SELECT ideprograma AS ID, codigoprograma AS Código, nivelprograma AS Nivel, nombreprograma AS Nombre, horasprograma AS Horas, 
            CASE WHEN status = 1 THEN 'Activo' ELSE 'Inactivo' END AS Estado FROM tbl_programas";


    // 🔸 Título (unir de C1 a K2)
    $sheet->mergeCells('C2:H3');
    $sheet->setCellValue('C2', $titulo);
    $sheet->getStyle('C2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('FF003366');
    $sheet->getStyle('C2:H3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    $sheet->getStyle('C2:H2')->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setRGB('E0E0E0'); /// color gris para el fondo de la celda

    //  Espacio entre título y encabezado (fila vacía 3)
    $sheet->setCellValue('C3', ''); // fila vacía solo para espacio visual

    //  Encabezados (fila 4, desde C)
    $sheet->fromArray($encabezados, NULL, 'C4');

    // Estilo del encabezado
    $sheet->getStyle('C4:H4')->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '41B300']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    // ==========================
    // 🔹 CONSULTA Y LLENADO DE DATOS
    // ==========================
    $result = $conexion->query($sql);
    $fila = 5; // ⬅️ Comienza una fila más abajo (debajo del encabezado)

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $columna = 'C'; // Inicia en C
            foreach ($row as $valor) {
                $sheet->setCellValue($columna . $fila, $valor);
                $columna++;
            }
            $fila++;
        }
    } else {
        $sheet->setCellValue('C5', 'No se encontraron registros');
        $sheet->mergeCells('C5:H5');
        $sheet->getStyle('C5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    // ==========================
    // 🔹 ESTILOS FINALES
    // ==========================

    // Ajustar ancho automático (C → K)
    foreach (range('C', 'H') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Bordes para toda la tabla
    $ultimaFila = $fila - 1;
    $sheet->getStyle("C4:H$ultimaFila")->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    // Centrar el texto de los datos
    $sheet->getStyle("C5:H$ultimaFila")
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // ==========================
    // 🔹 DESCARGA DEL ARCHIVO
    // ==========================
    $nombreArchivo = $tipo . ".xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"$nombreArchivo\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
} else if ($tipo === 'competencias') {
    $titulo = 'Reporte de Competencias';
    $encabezados = ['Codigo', 'Numero Ficha', 'tipo competenciad', 'Nombre de la competencia', 'Horas', 'codigo programa'];

    // consulta SQL
    $sql = "SELECT 
    tc.codigocompetencia AS Codigo,
    tf.numeroficha AS NumeroFicha,
    tc.tipocompetencia AS TipoCompetencia,
    tc.nombrecompetencia AS NombreCompetencia,
    tc.horascompetencia AS Horas,
    tc.programacodigo AS CodigoPrograma
    FROM tbl_competencias tc
    LEFT JOIN tbl_fichas tf 
        ON tf.ideficha = tc.fichaide
    WHERE tc.status != 0
    ORDER BY tc.codigocompetencia ASC";

    // 🔸 Título (unir de C1 a K2)
    $sheet->mergeCells('C2:H3');
    $sheet->setCellValue('C2', $titulo);
    $sheet->getStyle('C2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('FF003366');
    $sheet->getStyle('C2:H3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    $sheet->getStyle('C2:H2')->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setRGB('E0E0E0'); /// color gris para el fondo de la celda

    //  Espacio entre título y encabezado (fila vacía 3)
    $sheet->setCellValue('C3', ''); // fila vacía solo para espacio visual

    //  Encabezados (fila 4, desde C)
    $sheet->fromArray($encabezados, NULL, 'C4');

    // Estilo del encabezado
    $sheet->getStyle('C4:H4')->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '41B300']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    // ==========================
    // 🔹 CONSULTA Y LLENADO DE DATOS
    // ==========================
    $result = $conexion->query($sql);
    $fila = 5; // ⬅️ Comienza una fila más abajo (debajo del encabezado)

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $columna = 'C'; // Inicia en C
            foreach ($row as $valor) {
                $sheet->setCellValue($columna . $fila, $valor);
                $columna++;
            }
            $fila++;
        }
    } else {
        $sheet->setCellValue('C5', 'No se encontraron registros');
        $sheet->mergeCells('C5:H5');
        $sheet->getStyle('C5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    // ==========================
    // 🔹 ESTILOS FINALES
    // ==========================

    // Ajustar ancho automático (C → K)
    foreach (range('C', 'H') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Bordes para toda la tabla
    $ultimaFila = $fila - 1;
    $sheet->getStyle("C4:H$ultimaFila")->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    // Centrar el texto de los datos
    $sheet->getStyle("C5:H$ultimaFila")
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // ==========================
    // 🔹 DESCARGA DEL ARCHIVO
    // ==========================
    $nombreArchivo = $tipo . ".xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"$nombreArchivo\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
} else if ($tipo === 'fichas') {
    $titulo = 'Reporte de Fichas';
    $encabezados = ['Ficha', 'Programa', 'Instructor'];

    // consulta SQL
    $sql = "SELECT 
                tf.numeroficha,
                tp.nombreprograma,
                tu.nombres
            FROM tbl_fichas tf
            LEFT JOIN tbl_programas tp ON tp.ideprograma = tf.programaide
            LEFT JOIN tbl_usuarios tu  ON tu.ideusuario = tf.usuarioide";

    // 🔸 Título (unir de C1 a K2)
    $sheet->mergeCells('C2:E3');
    $sheet->setCellValue('C2', $titulo);
    $sheet->getStyle('C2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('FF003366');
    $sheet->getStyle('C2:E3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    $sheet->getStyle('C2:E2')->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setRGB('E0E0E0'); /// color gris para el fondo de la celda

    //  Espacio entre título y encabezado (fila vacía 3)
    $sheet->setCellValue('C3', ''); // fila vacía solo para espacio visual

    //  Encabezados (fila 4, desde C)
    $sheet->fromArray($encabezados, NULL, 'C4');

    // Estilo del encabezado
    $sheet->getStyle('C4:E4')->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '41B300']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    // ==========================
    // 🔹 CONSULTA Y LLENADO DE DATOS
    // ==========================
    $result = $conexion->query($sql);
    $fila = 5; // ⬅️ Comienza una fila más abajo (debajo del encabezado)

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $columna = 'C'; // Inicia en C
            foreach ($row as $valor) {
                $sheet->setCellValue($columna . $fila, $valor);
                $columna++;
            }
            $fila++;
        }
    } else {
        $sheet->setCellValue('C5', 'No se encontraron registros');
        $sheet->mergeCells('C5:E5');
        $sheet->getStyle('C5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    // ==========================
    // 🔹 ESTILOS FINALES
    // ==========================

    // Ajustar ancho automático (C → K)
    foreach (range('C', 'E') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Bordes para toda la tabla
    $ultimaFila = $fila - 1;
    $sheet->getStyle("C4:E$ultimaFila")->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    // Centrar el texto de los datos
    $sheet->getStyle("C5:E$ultimaFila")
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // ==========================
    // 🔹 DESCARGA DEL ARCHIVO
    // ==========================
    $nombreArchivo = $tipo . ".xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"$nombreArchivo\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
} else if ($tipo == 'usuarios') {
    $titulo = 'Reporte de Usuarios';
    $encabezados = ['ID', 'Identificación', 'Nombres', 'Apellidos', 'Celular', 'Correo', 'Rol'];

    $sql = "SELECT 
            u.ideusuario,
            u.identificacion,
            u.nombres,
            u.apellidos,
            u.celular,
            u.correo,
            r.nombrerol
        FROM tbl_usuarios AS u
        INNER JOIN rol AS r 
        ON u.rolid = r.idrol";

    // 🔸 Título (unir de C1 a K2)
    $sheet->mergeCells('C2:I3');
    $sheet->setCellValue('C2', $titulo);
    $sheet->getStyle('C2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('FF003366');
    $sheet->getStyle('C2:I3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    $sheet->getStyle('C2:I2')->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setRGB('E0E0E0'); /// color gris para el fondo de la celda

    //  Espacio entre título y encabezado (fila vacía 3)
    $sheet->setCellValue('C3', ''); // fila vacía solo para espacio visual

    //  Encabezados (fila 4, desde C)
    $sheet->fromArray($encabezados, NULL, 'C4');

    // Estilo del encabezado
    $sheet->getStyle('C4:I4')->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '41B300']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    // ==========================
    // 🔹 CONSULTA Y LLENADO DE DATOS
    // ==========================
    $result = $conexion->query($sql);
    $fila = 5; // ⬅️ Comienza una fila más abajo (debajo del encabezado)

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $columna = 'C'; // Inicia en C
            foreach ($row as $valor) {
                $sheet->setCellValue($columna . $fila, $valor);
                $columna++;
            }
            $fila++;
        }
    } else {
        $sheet->setCellValue('C5', 'No se encontraron registros');
        $sheet->mergeCells('C5:I5');
        $sheet->getStyle('C5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    // ==========================
    // 🔹 ESTILOS FINALES
    // ==========================

    // Ajustar ancho automático (C → K)
    foreach (range('C', 'I') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Bordes para toda la tabla
    $ultimaFila = $fila - 1;
    $sheet->getStyle("C4:I$ultimaFila")->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    // Centrar el texto de los datos
    $sheet->getStyle("C5:I$ultimaFila")
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // ==========================
    // 🔹 DESCARGA DEL ARCHIVO
    // ==========================
    $nombreArchivo = $tipo . ".xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"$nombreArchivo\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
