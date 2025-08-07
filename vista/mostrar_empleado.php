<?php
include("../conexion/cn.php");
$cedula = isset($_GET['cedula']) ? $conexion->real_escape_string($_GET['cedula']) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados de Empleado</title>
    <style>
        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 180px;
        }
        body {
            position: relative;
            font-family: 'Segoe UI', Tahoma, Verdana, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 100px 40px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        body::before {
            content: "";
            position: fixed;
            top: 50%;
            left: 50%;
            width: 60vw;
            height: 60vh;
            background: url('../imagenes/ejercito.png') no-repeat center;
            background-size: contain;
            opacity: 0.08;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: -1;
        }
        h2, h3 {
            color: #AF1415;
            margin: 0.5em 0;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            margin-top: 20px;
            background-color: rgba(255,255,255,0.95);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #AF1415;
            color: white;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .back-btn {
            margin-top: 20px;
            text-decoration: none;
            background-color: #AF1415;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color .3s;
        }
        .back-btn:hover {
            background-color: #8c0f10;
        }
        .btn-doc {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 3px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            background-color: #556B2F;
            color: #fff;
            transition: opacity .3s;
        }
        .btn-doc:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

<img src="../imagenes/logo4.png" alt="Logo Empresa" class="logo">

<?php if ($cedula === ''): ?>
    <h2>No se recibió ninguna cédula.</h2>
<?php else: ?>
    <h2>Resultados para la cédula: <u><?= htmlspecialchars($cedula) ?></u></h2>

    <?php
    $sql = "
        SELECT
            nombre_completo_contratista,
            profesion,
            documento_identidad, no_contrato,
            fecha_inicio_contrato, fecha_terminacion_contrato
        FROM contrato_detallado
        WHERE documento_identidad = '$cedula'
    ";
    $res = $conexion->query($sql);
    ?>

    <?php if ($res && $res->num_rows > 0): ?>
        <table>
            <tr>
                <th>Nombre Completo</th>
                <th>Profesión</th>
                <th>Cédula</th>
                <th>contrato</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Opciones</th>
            </tr>
<?php
while ($fila = $res->fetch_assoc()):
    $numeroContratoCompleto = $fila['no_contrato'];
    $numeroContrato = strtok($numeroContratoCompleto, '-'); // Extrae '188' si es '188-DMMED-BAS15-2025'


$carpetaContrato = __DIR__ . "/contratos_firmados/";
$archivoContrato = null;
foreach (['docx', 'doc', 'pdf'] as $ext) {
    $coincide = glob($carpetaContrato . "*{$numeroContrato}*.{$ext}");
    if (!empty($coincide)) {
        $archivoContrato = basename($coincide[0]);
        break;
    }
}
$urlContrato = $archivoContrato ? "contratos_firmados/" . $archivoContrato : null;

// === POLIZA ===
$carpetaPoliza = __DIR__ . "/contratos_poliza_firmados/";
$archivoPoliza = null;
foreach (['docx', 'doc', 'pdf'] as $ext) {
    $coincide = glob($carpetaPoliza . "*{$numeroContrato}*.{$ext}");
    if (!empty($coincide)) {
        $archivoPoliza = basename($coincide[0]);
        break;
    }
}
$urlPoliza = $archivoPoliza ? "contratos_poliza_firmados/" . $archivoPoliza : null;

// === POLIZA SIN FIRMAR ===
$carpetaPolizaNoFirmada = __DIR__ . "/contratos_poliza/";
$archivoPolizaSinFirmar = null;
foreach (['docx', 'doc', 'pdf'] as $ext) {
    $coincide = glob($carpetaPolizaNoFirmada . "*{$numeroContrato}*.{$ext}");
    if (!empty($coincide)) {
        $archivoPolizaSinFirmar = basename($coincide[0]);
        break;
    }
}

?>
<tr>
    <td><?= htmlspecialchars($fila['nombre_completo_contratista']) ?></td>
    <td><?= htmlspecialchars($fila['profesion']) ?></td>
    <td><?= htmlspecialchars($fila['documento_identidad']) ?></td>
    <td><?= htmlspecialchars($numeroContratoCompleto) ?></td>
    <td><?= htmlspecialchars($fila['fecha_inicio_contrato']) ?></td>
    <td><?= htmlspecialchars($fila['fecha_terminacion_contrato']) ?></td>
    <td>
  <!-- Icono CONTRATO o enlace para subir -->
  <?php if ($urlContrato): ?>
    <a href="<?= $urlContrato ?>" download title="Descargar contrato">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="#2B579A">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <path d="M14 2v6h6" fill="#2B579A"/>
        <text x="7" y="18" font-family="Arial" font-size="12" fill="white">C</text>
      </svg>
    </a>
  <?php else: ?>
    <a href="subir_contrato.php?cedula=<?= urlencode($cedula) ?>" class="btn-doc" title="Contrato pendiente por firmar">
      📤 Pendiente firma del contrato
    </a>
  <?php endif; ?>

  <!-- Espacio entre íconos -->
  &nbsp;&nbsp;

  <!-- Icono POLIZA o enlace para subir -->
<!-- Icono POLIZA o enlace para subir -->
<?php if ($urlPoliza): ?>
    <!-- Si la póliza está firmada, permite descarga -->
    <a href="<?= $urlPoliza ?>" download title="Descargar póliza">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="#2B579A">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <path d="M14 2v6h6" fill="#2B579A"/>
        <text x="7" y="18" font-family="Arial" font-size="12" fill="white">P</text>
      </svg>
    </a>
<?php elseif ($archivoPolizaSinFirmar): ?>
    <!-- Si hay póliza sin firmar -->
    <a href="subir_poliza.php?cedula=<?= urlencode($cedula) ?>" class="btn-doc" title="Hay póliza generada sin firmar">
      📤 Pendiente firma de póliza
    </a>
<?php else: ?>
    <!-- Si no hay ninguna póliza -->
    <a href="formulario_poliza.php?cedula=<?= urlencode($cedula) ?>" class="btn-doc" title="Falta formulario de póliza">
      ❌ Pendiente contrato de póliza
    </a>
<?php endif; ?>
&nbsp;&nbsp;

<a href="formulario_acta.php?cedula=<?= urlencode($cedula) ?>" class="btn-doc" title="Registrar acta">
  📝 Pendiente Acta de incio
</a>

</td>
</tr>
<?php endwhile; ?>
</table>

<!-- ✅ Botones al final de la tabla -->
<div style="margin-top: 30px; text-align: center;">
    <a href="renovacion.php?cedula=<?= urlencode($cedula) ?>" class="back-btn">✍ Extender contrato</a> <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <a href="formulario_busqueda.php" class="back-btn">← Nueva búsqueda</a>
    <a href="../menu1.php" class="back-btn">Ir al menú principal</a>
</div>

<?php else: ?> <!-- cierra if ($res && $res->num_rows > 0) -->
    <h2>No se encontraron registros.</h2>
    <div style="margin-top: 30px; text-align: center;">
        <a href="formulario_busqueda.php" class="back-btn">← Nueva búsqueda</a>
    </div>
<?php endif; ?> <!-- cierra if ($res && $res->num_rows > 0) -->
<?php endif; ?> <!-- cierra if ($cedula === '') -->

<?php $conexion->close(); ?>
</body>
</html>
