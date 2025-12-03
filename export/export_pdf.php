<?php
require_once "../Config/Config.php";
require_once "../vendor/autoload.php"; // Carga automática de mPDF

use Mpdf\Mpdf;

// ==========================
// 🔹 CONEXIÓN A MYSQL
// ==========================
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

$tipo = $_GET['tipo'];

if ($tipo === 'asignaciones') {

  // ==========================
  // 🔹 CONSULTA DE DATOS
  // ==========================
  $sql = "SELECT 
            df.idedetalleficha AS ID,
            df.programaficha AS Programa,
            u.nombres AS Instructor,
            df.idecompetencia AS Competencia,
            df.horasrealizadas AS Horas_Realizadas,
            df.totalhoras AS Total_Horas,
            df.mes AS Mes,
            CASE WHEN df.status = 1 THEN 'Activo' ELSE 'Inactivo' END AS Estado,
            df.created_at AS Fecha
        FROM tbl_detalle_fichas AS df
        LEFT JOIN tbl_usuarios AS u ON u.ideusuario = df.ideinstructor";


  $result = $conexion->query($sql);

  // 🔹 CREAR INSTANCIA DE MPDF 
  $mpdf = new Mpdf([
    'margin_top' => 5,     // casi sin espacio superior
    'margin_bottom' => 15, // margen inferior normal
    'margin_left' => 10,
    'margin_right' => 10
  ]);


  // ==========================
  // 🔹 CONTENIDO DEL PDF
  // ==========================
  $html = '
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
      font-size: 11px;
    }
    th, td {
      border: 1px solid #000;
      padding: 6px;
      text-align: center;
      vertical-align: middle;
    }
    th {
      background-color: #41B300;
      color: #000000;
    }
    td.fecha, td.hora {
      white-space: nowrap !important;
    }
  </style>

  <h3 style="text-align:center; color:#000; margin-top:0;">LISTADO DE ASIGNACIONES</h3>
  <table border="1" width="100%" cellspacing="0" cellpadding="6"
        style="border-collapse:collapse; font-size:11px; color:#000;">
      <thead>
          <tr style="background-color:#41B300; color:#000000; text-align:center; font-weight:bold;">
              <th width="5%">ID</th>
              <th width="15%">Programa</th>
              <th width="15%">Instructor</th>
              <th width="15%">Competencia</th>
              <th width="10%">Horas Realizadas</th>
              <th width="10%">Total Horas</th>
              <th width="10%">Mes</th>
              <th width="11%">Fecha</th>
              <th width="10%">Hora</th>
          </tr>
      </thead>
    <tbody>';

  // ==========================
  // 🔹 LLENAR LA TABLA CON LOS DATOS
  // ==========================
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $html .= '
        <tr style="text-align:center;">
            <td>' . htmlspecialchars($row['ID']) . '</td>
            <td>' . htmlspecialchars($row['Ficha']) . '</td>
            <td>' . htmlspecialchars($row['Instructor']) . '</td>
            <td>' . htmlspecialchars($row['Competencia']) . '</td>
            <td>' . htmlspecialchars($row['Horas_Realizadas']) . '</td>
            <td>' . htmlspecialchars($row['Total_Horas']) . '</td>
            <td>' . htmlspecialchars($row['Mes']) . '</td>
            <td class="fecha">' . date('Y-m-d', strtotime($row['Fecha'])) . '</td>
            <td class="hora">' . date('H:i', strtotime($row['Fecha'])) . '</td>

        </tr>';
    }
  } else {
    $html .= '
        <tr>
            <td colspan="9" style="text-align:center; color:red;">No se encontraron registros.</td>
        </tr>';
  }

  $html .= '
    </tbody>
  </table>';

  // ==========================
  // 🔹 PIE DE PÁGINA
  // ==========================
  $mpdf->SetHTMLFooter('
  <hr>
  <div style="text-align:center; font-size:10px; color:#555;">
    SIGMA © ' . date('Y') . ' — Página {PAGENO}
  </div>
  ');

  // ==========================
  // 🔹 GENERAR Y DESCARGAR PDF
  // ==========================
  $mpdf->WriteHTML($html);
  $mpdf->Output('reporte_asignaciones.pdf', 'D');
  exit;
} else if ($tipo === 'programas') {

  // ==========================
  // 🔹 CONSULTA DE DATOS
  // ==========================
  $sql = "SELECT ideprograma AS ID, codigoprograma AS Código, nivelprograma AS Nivel, nombreprograma AS Nombre, horasprograma AS Horas, 
            CASE WHEN status = 1 THEN 'Activo' ELSE 'Inactivo' END AS Estado FROM tbl_programas";


  $result = $conexion->query($sql);

  // 🔹 CREAR INSTANCIA DE MPDF 
  $mpdf = new Mpdf([
    'margin_top' => 5,     // casi sin espacio superior
    'margin_bottom' => 15, // margen inferior normal
    'margin_left' => 10,
    'margin_right' => 10
  ]);


  // ==========================
  // 🔹 CONTENIDO DEL PDF
  // ==========================
  $html = '
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
      font-size: 11px;
    }
    th, td {
      border: 1px solid #000;
      padding: 6px;
      text-align: center;
      vertical-align: middle;
    }
    th {
      background-color: #41B300;
      color: #000000;
    }
    td.fecha, td.hora {
      white-space: nowrap !important;
    }
  </style>

  <h3 style="text-align:center; color:#000; margin-top:0;">LISTADO DE PROGRAMAS</h3>
  <table border="1" width="100%" cellspacing="0" cellpadding="6"
        style="border-collapse:collapse; font-size:11px; color:#000;">
      <thead>
          <tr style="background-color:#41B300; color:#000000; text-align:center; font-weight:bold;">
              <th width="15%">Código</th>
              <th width="15%">Nivel</th>
              <th width="25%">Nombre</th>
              <th width="10%">Horas</th>
              <th width="10%">Estado</th>
          </tr>
      </thead>
    <tbody>';

  // ==========================
  // 🔹 LLENAR LA TABLA CON LOS DATOS
  // ==========================
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $html .= '
        <tr style="text-align:center;">
            <td>' . htmlspecialchars($row['Código']) . '</td>
            <td>' . htmlspecialchars($row['Nivel']) . '</td>
            <td>' . htmlspecialchars($row['Nombre']) . '</td>
            <td>' . htmlspecialchars($row['Horas']) . '</td>
            <td>' . htmlspecialchars($row['Estado']) . '</td>
        </tr>';
    }
  } else {
    $html .= '
        <tr>
            <td colspan="9" style="text-align:center; color:red;">No se encontraron registros.</td>
        </tr>';
  }

  $html .= '
    </tbody>
  </table>';

  // ==========================
  // 🔹 PIE DE PÁGINA
  // ==========================
  $mpdf->SetHTMLFooter('
  <hr>
  <div style="text-align:center; font-size:10px; color:#555;">
    SIGMA © ' . date('Y') . ' — Página {PAGENO}
  </div>
  ');

  // ==========================
  // 🔹 GENERAR Y DESCARGAR PDF
  // ==========================
  $mpdf->WriteHTML($html);
  $mpdf->Output('reporte_asignaciones.pdf', 'D');
  exit;
} else if ($tipo === 'competencias') {

  // ==========================
  // 🔹 CONSULTA DE DATOS
  // ==========================
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

  $result = $conexion->query($sql);

  // 🔹 CREAR INSTANCIA DE MPDF 
  $mpdf = new Mpdf([
    'margin_top' => 5,     // casi sin espacio superior
    'margin_bottom' => 15, // margen inferior normal
    'margin_left' => 10,
    'margin_right' => 10
  ]);


  // ==========================
  // 🔹 CONTENIDO DEL PDF
  // ==========================
  $html = '
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
      font-size: 11px;
    }
    th, td {
      border: 1px solid #000;
      padding: 6px;
      text-align: center;
      vertical-align: middle;
    }
    th {
      background-color: #41B300;
      color: #000000;
    }
    td.fecha, td.hora {
      white-space: nowrap !important;
    }
  </style>

  <h3 style="text-align:center; color:#000; margin-top:0;">LISTADO DE COMPETENCIAS</h3>
  <table border="1" width="100%" cellspacing="0" cellpadding="6"
        style="border-collapse:collapse; font-size:11px; color:#000;">
      <thead>
          <tr style="background-color:#41B300; color:#000000; text-align:center; font-weight:bold;">
              <th width="15%">Código</th>
              <th width="15%">Numero de Ficha</th>
              <th width="25%">Nombre de la Competencia</th>
              <th width="10%">Horas</th>
              <th width="10%">codigo programa</th>
          </tr>
      </thead>
    <tbody>';

  // ==========================
  // 🔹 LLENAR LA TABLA CON LOS DATOS
  // ==========================
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $html .= '
        <tr style="text-align:center;">
            <td>' . htmlspecialchars($row['Codigo']) . '</td>
            <td>' . htmlspecialchars($row['NumeroFicha']) . '</td>
            <td>' . htmlspecialchars($row['TipoCompetencia']) . '</td>
            <td>' . htmlspecialchars($row['Horas']) . '</td>
            <td>' . htmlspecialchars($row['CodigoPrograma']) . '</td>
        </tr>';
    }
  } else {
    $html .= '
        <tr>
            <td colspan="9" style="text-align:center; color:red;">No se encontraron registros.</td>
        </tr>';
  }

  $html .= '
    </tbody>
  </table>';

  // ==========================
  // 🔹 PIE DE PÁGINA
  // ==========================
  $mpdf->SetHTMLFooter('
  <hr>
  <div style="text-align:center; font-size:10px; color:#555;">
    SIGMA © ' . date('Y') . ' — Página {PAGENO}
  </div>
  ');

  // ==========================
  // 🔹 GENERAR Y DESCARGAR PDF
  // ==========================
  $mpdf->WriteHTML($html);
  $mpdf->Output('reporte_asignaciones.pdf', 'D');
  exit;
} else if ($tipo === 'fichas') {

    $sql = "SELECT 
                tf.numeroficha,
                tp.nombreprograma,
                tu.nombres
            FROM tbl_fichas tf
            LEFT JOIN tbl_programas tp ON tp.ideprograma = tf.programaide
            LEFT JOIN tbl_usuarios tu  ON tu.ideusuario = tf.usuarioide";

  $result = $conexion->query($sql);

  // 🔹 CREAR INSTANCIA DE MPDF 
  $mpdf = new Mpdf([
    'margin_top' => 5,     // casi sin espacio superior
    'margin_bottom' => 15, // margen inferior normal
    'margin_left' => 10,
    'margin_right' => 10
  ]);


  // ==========================
  // 🔹 CONTENIDO DEL PDF
  // ==========================
  $html = '
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
      font-size: 11px;
    }
    th, td {
      border: 1px solid #000;
      padding: 6px;
      text-align: center;
      vertical-align: middle;
    }
    th {
      background-color: #41B300;
      color: #000000;
    }
    td.fecha, td.hora {
      white-space: nowrap !important;
    }
  </style>

  <h3 style="text-align:center; color:#000; margin-top:0;">LISTADO DE FICHAS</h3>
  <table border="1" width="100%" cellspacing="0" cellpadding="6"
        style="border-collapse:collapse; font-size:11px; color:#000;">
      <thead>
          <tr style="background-color:#41B300; color:#000000; text-align:center; font-weight:bold;">
              <th width="15%">Ficha</th>
              <th width="25%">Nombre del Programa</th>
              <th width="10%">Instructor</th>
          </tr>
      </thead>
    <tbody>';

  // ==========================
  // 🔹 LLENAR LA TABLA CON LOS DATOS
  // ==========================
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $html .= '
        <tr style="text-align:center;">
            <td>' . htmlspecialchars($row['numeroficha']) . '</td>
            <td>' . htmlspecialchars($row['nombreprograma']) . '</td>
            <td>' . htmlspecialchars($row['nombres']) . '</td>
        </tr>';
    }
  } else {
    $html .= '
        <tr>
            <td colspan="9" style="text-align:center; color:red;">No se encontraron registros.</td>
        </tr>';
  }

  $html .= '
    </tbody>
  </table>';

  // ==========================
  // 🔹 PIE DE PÁGINA
  // ==========================
  $mpdf->SetHTMLFooter('
  <hr>
  <div style="text-align:center; font-size:10px; color:#555;">
    SIGMA © ' . date('Y') . ' — Página {PAGENO}
  </div>
  ');

  // ==========================
  // 🔹 GENERAR Y DESCARGAR PDF
  // ==========================
  $mpdf->WriteHTML($html);
  $mpdf->Output('reporte_asignaciones.pdf', 'D');
  exit;
} else if ($tipo === 'usuarios') {
  
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
  $result = $conexion->query($sql);

  // 🔹 CREAR INSTANCIA DE MPDF 
  $mpdf = new Mpdf([
    'margin_top' => 5,     // casi sin espacio superior
    'margin_bottom' => 15, // margen inferior normal
    'margin_left' => 10,
    'margin_right' => 10
  ]);


  // ==========================
  // 🔹 CONTENIDO DEL PDF
  // ==========================
  $html = '
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
      font-size: 11px;
    }
    th, td {
      border: 1px solid #000;
      padding: 6px;
      text-align: center;
      vertical-align: middle;
    }
    th {
      background-color: #41B300;
      color: #000000;
    }
    td.fecha, td.hora {
      white-space: nowrap !important;
    }
  </style>

  <h3 style="text-align:center; color:#000; margin-top:0;">LISTADO DE USUARIOS</h3>
  <table border="1" width="100%" cellspacing="0" cellpadding="6"
        style="border-collapse:collapse; font-size:11px; color:#000;">
      <thead>
          <tr style="background-color:#41B300; color:#000000; text-align:center; font-weight:bold;">
              <th width="10%">ID</th>
              <th width="15%">Identificación</th>
              <th width="15%">Nombres</th>
              <th width="15%">Apellidos</th>
              <th width="10%">Celular</th>
              <th width="15%">Correo</th>
              <th width="15%">Rol</th>
          </tr>
      </thead>
    <tbody>';

  // ==========================
  // 🔹 LLENAR LA TABLA CON LOS DATOS
  // ==========================
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $html .= '
        <tr style="text-align:center;">
            <td>' . htmlspecialchars($row['ideusuario']) . '</td>
            <td>' . htmlspecialchars($row['identificacion']) . '</td> 
            <td>' . htmlspecialchars($row['nombres']) . '</td>  
            <td>' . htmlspecialchars($row['apellidos']) . '</td>
            <td>' . htmlspecialchars($row['celular']) . '</td>
            <td>' . htmlspecialchars($row['correo']) . '</td>
            <td>' . htmlspecialchars($row['nombrerol']) . '</td>
        </tr>';
    }
  } else {
    $html .= '
        <tr>
            <td colspan="9" style="text-align:center; color:red;">No se encontraron registros.</td>
        </tr>';
  }

  $html .= '
    </tbody>
  </table>';

  // ==========================
  // 🔹 PIE DE PÁGINA
  // ==========================
  $mpdf->SetHTMLFooter('
  <hr>
  <div style="text-align:center; font-size:10px; color:#555;">
    SIGMA © ' . date('Y') . ' — Página {PAGENO}
  </div>
  ');

  // ==========================
  // 🔹 GENERAR Y DESCARGAR PDF
  // ==========================
  $mpdf->WriteHTML($html);
  $mpdf->Output('reporte_asignaciones.pdf', 'D');
  exit;
}
