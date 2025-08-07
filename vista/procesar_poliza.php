<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Bogota');
include('../bd/cn.php');

require __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

// Datos desde formulario
$documento = $_POST['cedula'] ?? $_GET['cedula'] ?? '';

// ORDENADOR DEL GASTO
$nombre_ordenador = isset($_POST['crear_ordenador']) 
  ? $_POST['nombre_ordenador'] 
  : $_POST['ordenador_gasto'];
$grado_ordenador = trim($_POST['grado_ordenador'] ?? '');
$cedula_ordenador = trim($_POST['cedula_ordenador'] ?? '');
$lugar_expedicion_ordenador = trim($_POST['lugar_expedicion_ordenador'] ?? '');
$nuevo_nombre_ordenador = trim($_POST['nombre_ordenador'] ?? '');
$nuevo_grado_ordenador = trim($_POST['grado_ordenador'] ?? '');
$nueva_cedula_ordenador = trim($_POST['cedula_ordenador'] ?? '');
$nuevo_lugar_expedicion_ordenador = trim($_POST['lugar_expedicion_ordenador'] ?? '');

// JEFE DE CONTRATOS
$nombre_jefe = trim($_POST['jefe_contratos'] ?? '');
$nuevo_nombre_jefe = trim($_POST['nombre_jefe'] ?? '');
$nueva_cedula_jefe = trim($_POST['cedula_jefe'] ?? '');

// ASESOR JURÍDICO
$nombre_asesor = trim($_POST['asesor_juridico'] ?? '');
$nuevo_nombre_asesor = trim($_POST['nombre_asesor'] ?? '');
$nueva_cedula_asesor = trim($_POST['cedula_asesor'] ?? '');

$fecha_aprobacion_poliza = $_POST['fecha_inicio_poliza'] ?? '';

$no_poliza_cumplimiento = $_POST['numero_poliza'] ?? '';
$fecha_inicio_poliza_cumplimiento = $_POST['fecha_inicio_poliza'] ?? null;
$fecha_terminacion_poliza_cumplimiento = $_POST['fecha_fin_poliza'] ?? null;
$valor_cumplimiento = $_POST['precio_poliza_cumplimiento'] ?? '';

$no_poliza_rcp = $_POST['numero_poliza_responsabilidad'] ?? '';
$fecha_inicio_poliza_rcp = $_POST['fecha_inicio_poliza_responsabilidad'] ?? null;
$fecha_terminacion_poliza_rcp = $_POST['fecha_fin_poliza_responsabilidad'] ?? null;
$valor_rcp = $_POST['valor_poliza_responsabilidad'] ?? '';
$fecha_aprobacion_poliza2 = $fecha_aprobacion_poliza;
$valor_calidad = $_POST['precio_poliza_calidad'] ?? '';
$objeto_nuevo = trim($_POST['nuevo_objeto'] ?? '');
$objeto_seleccionado = trim($_POST['objeto'] ?? '');

$aseguradora_nueva = trim($_POST['nueva_aseguradora'] ?? '');
$nit_aseguradora = trim($_POST['nit_aseguradora'] ?? '');
$aseguradora_seleccionada = trim($_POST['nombre_aseguradora'] ?? '');

$aseguradorarespo_nueva = trim($_POST['nueva_aseguradorarespo'] ?? '');
$nit_aseguradorarespo = trim($_POST['nit_aseguradorarespo'] ?? '');
$aseguradorarespo_seleccionada = trim($_POST['nombre_aseguradora_respo'] ?? '');

// Función para insertar si no existe
function insertarSiNoExiste($conexion, $tabla, $campos) {
    $where = implode(' AND ', array_map(fn($k) => "$k = ?", array_keys($campos)));
    $query = "SELECT id FROM $tabla WHERE $where";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param(str_repeat('s', count($campos)), ...array_values($campos));
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) return $res->fetch_assoc()['id'];

    $columns = implode(', ', array_keys($campos));
    $placeholders = implode(', ', array_fill(0, count($campos), '?'));
    $sql = "INSERT INTO $tabla ($columns) VALUES ($placeholders)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($campos)), ...array_values($campos));
    $stmt->execute();
    return $stmt->insert_id;
}



// Manejo del objeto
$objeto_final = !empty($objeto_nuevo) ? $objeto_nuevo : $objeto_seleccionado;
if (!empty($objeto_nuevo)) {
    insertarSiNoExiste($conexion, 'objeto', ['nombre' => $objeto_nuevo]);
}

// Manejo de aseguradora
$aseguradora_final = !empty($aseguradora_nueva) ? $aseguradora_nueva : $aseguradora_seleccionada;
if (!empty($aseguradora_nueva) && !empty($nit_aseguradora)) {
    insertarSiNoExiste($conexion, 'aseguradora', [
        'nombre' => $aseguradora_nueva,
        'nit' => $nit_aseguradora
    ]);
}

$aseguradorarespo_final = !empty($aseguradorarespo_nueva) ? $aseguradorarespo_nueva : $aseguradorarespo_seleccionada;
if (!empty($aseguradorarespo_nueva) && !empty($nit_aseguradorarespo)) {
    insertarSiNoExiste($conexion, 'aseguradora_respo', [
        'nombre' => $aseguradorarespo_nueva,
        'nit' => $nit_aseguradorarespo
    ]);
}
// Insertar nuevo ordenador del gasto
if (!empty($nuevo_nombre_ordenador) && !empty($nuevo_grado_ordenador) && !empty($nueva_cedula_ordenador)) {
    insertarSiNoExiste($conexion, 'ordenador_gasto', [
        'grado' => $nuevo_grado_ordenador,
        'nombre' => $nuevo_nombre_ordenador,
        'cedula' => $nueva_cedula_ordenador,
        'lugar_expedicion_cedula' => $nuevo_lugar_expedicion_ordenador
    ]);
    $nombre_ordenador = $nuevo_nombre_ordenador; // actualiza para Word
}

// Insertar nuevo jefe de contratos
if (!empty($nuevo_nombre_jefe) && !empty($nueva_cedula_jefe)) {
    insertarSiNoExiste($conexion, 'jefe_contratos', [
        'nombre' => $nuevo_nombre_jefe,
        'cedula' => $nueva_cedula_jefe
    ]);
    $nombre_jefe = $nuevo_nombre_jefe; // reemplaza en caso de nuevo
}

// Insertar nuevo asesor jurídico
if (!empty($nuevo_nombre_asesor) && !empty($nueva_cedula_asesor)) {
    insertarSiNoExiste($conexion, 'asesor_juridico', [
        'nombre' => $nuevo_nombre_asesor,
        'cedula' => $nueva_cedula_asesor
    ]);
    $nombre_asesor = $nuevo_nombre_asesor;
}


// Actualizar contrato_detallado
$sql = "UPDATE contrato_detallado SET
    objeto = ?, no_poliza_cumplimiento = ?, fecha_inicio_poliza_cumplimiento = ?,
    fecha_terminacion_poliza_cumplimiento = ?, no_poliza_rcp = ?, fecha_inicio_poliza_rcp = ?,
    fecha_terminacion_poliza_rcp = ?, fecha_aprobacion_poliza = ?, fecha_aprobacion_poliza2 = ?
    WHERE documento_identidad = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssssssss", // ← 11 letras tipo
    $objeto_final,
    $no_poliza_cumplimiento,
    $fecha_inicio_poliza_cumplimiento,
    $fecha_terminacion_poliza_cumplimiento,
    $no_poliza_rcp,
    $fecha_inicio_poliza_rcp,
    $fecha_terminacion_poliza_rcp,
    $fecha_aprobacion_poliza,
    $fecha_aprobacion_poliza2,
    $documento,
    
);


if ($stmt->execute()) {
    // Traer datos del contrato
    $consulta = $conexion->prepare("SELECT * FROM contrato_detallado WHERE documento_identidad = ?");
$consulta->bind_param("s", $documento);
$consulta->execute();
$datos = $consulta->get_result()->fetch_assoc();
$numero_contrato = $datos['no_contrato'] ?? '0000';  // Usa el número real del contrato


    // Datos aseguradora
    $aseguradora_nombre = '';
    $nit_aseguradora_final = '';
    if (!empty($aseguradora_final)) {
        $asegQuery = $conexion->prepare("SELECT nombre, nit FROM aseguradora WHERE nombre = ?");
        $asegQuery->bind_param("s", $aseguradora_final);
        $asegQuery->execute();
        $res = $asegQuery->get_result()->fetch_assoc();
        if ($res) {
            $aseguradora_nombre = $res['nombre'];
            $nit_aseguradora_final = $res['nit'];
        }
    }

    // Datos aseguradora
    $aseguradorarespo_nombre = '';
    $nit_aseguradorarespo_final = '';
    if (!empty($aseguradorarespo_final)) {
        $asegQuery = $conexion->prepare("SELECT nombre, nit FROM aseguradora_respo WHERE nombre = ?");
        $asegQuery->bind_param("s", $aseguradorarespo_final);
        $asegQuery->execute();
        $res = $asegQuery->get_result()->fetch_assoc();
        if ($res) {
            $aseguradorarespo_nombre = $res['nombre'];
            $nit_aseguradorarespo_final = $res['nit'];
        }
    }
    // Datos del ordenador del gasto
$grado_ordenador_final = $grado_ordenador;
$cedula_ordenador_final = $cedula_ordenador;
$lugar_expedicion_final = $lugar_expedicion_ordenador;

if (!empty($nombre_ordenador)) {
    $stmtOrd = $conexion->prepare("SELECT grado, cedula, lugar_expedicion_cedula FROM ordenador_gasto WHERE nombre = ?");
    $stmtOrd->bind_param("s", $nombre_ordenador);
    $stmtOrd->execute();
    $resOrd = $stmtOrd->get_result()->fetch_assoc();
    if ($resOrd) {
        $grado_ordenador_final = $resOrd['grado'];
        $cedula_ordenador_final = $resOrd['cedula'];
        $lugar_expedicion_final = $resOrd['lugar_expedicion_cedula'];
    }
}
$nombre_ordenador_final = $nombre_ordenador;

// Datos del asesor jurídico
$cedula_asesor_final = $nueva_cedula_asesor;
if (!empty($nombre_asesor)) {
    $stmtAse = $conexion->prepare("SELECT cedula FROM asesor_juridico WHERE nombre = ?");
    $stmtAse->bind_param("s", $nombre_asesor);
    $stmtAse->execute();
    $resAse = $stmtAse->get_result()->fetch_assoc();
    if ($resAse) {
        $cedula_asesor_final = $resAse['cedula'];
    }
}


    // Cargar plantilla
    $template = new TemplateProcessor(__DIR__ . '/plantilla_poliza.docx');
  

    // Reemplazos
    $partes_contrato = explode('-', $datos['no_contrato']);
    $nro_contrato = $partes_contrato[0] ?? 'N/A';
    $anio_contrato = isset($datos['fecha_suscripcion']) ? date('Y', strtotime($datos['fecha_suscripcion'])) : '2025';

    $template->setValue('nro_contrato', $nro_contrato);
    $template->setValue('anio_contrato', $anio_contrato);
    $template->setValue('precio_poliza_calidad', $valor_calidad);
    $template->setValue('objeto', $datos['objeto'] ?? 'N/A');
    $template->setValue('aseguradora', $aseguradora_nombre);
    $template->setValue('nit_aseguradora', $nit_aseguradora_final);
    $template->setValue('aseguradorarespo', $aseguradorarespo_nombre);
    $template->setValue('nit_aseguradorarespo', $nit_aseguradorarespo_final);
    $template->setValue('numero_poliza', $datos['no_poliza_cumplimiento'] ?? 'N/A');
    $template->setValue('fecha_inicio_poliza', $datos['fecha_inicio_poliza_cumplimiento'] ?? 'N/A');
    $template->setValue('fecha_fin_poliza', $datos['fecha_terminacion_poliza_cumplimiento'] ?? 'N/A');
    $template->setValue('precio_poliza_cumplimiento', $valor_cumplimiento ?: 'N/A');
    $template->setValue('numero_poliza_responsabilidad', $datos['no_poliza_rcp'] ?? 'N/A');
    $template->setValue('fecha_inicio_poliza_responsabilidad', $datos['fecha_inicio_poliza_rcp'] ?? 'N/A');
    $template->setValue('fecha_fin_poliza_responsabilidad', $datos['fecha_terminacion_poliza_rcp'] ?? 'N/A');
    $template->setValue('valor_poliza_responsabilidad', $valor_rcp ?: 'N/A');
    $template->setValue('nombre_contratista', $datos['nombre_completo_contratista'] ?? 'N/A');
    $template->setValue('cedula_contratista', $datos['documento_identidad'] ?? 'N/A');
    $template->setValue('no_contrato', $datos['no_contrato'] ?? 'N/A');
    $template->setValue('grado_ordenador_gasto', $grado_ordenador_final ?? 'N/A');
    $template->setValue('nombre_ordenador_gasto', $nombre_ordenador ?? 'N/A');
    $template->setValue('cedula_ordenador_gasto', $cedula_ordenador_final ?? 'N/A');
    $template->setValue('lugar_ordenador_gasto', $lugar_expedicion_final ?? 'N/A');
    $template->setValue('nomb_asesor_juridico', $nombre_asesor ?? 'N/A');
    $template->setValue('nomb_jefe_contratos', $nombre_jefe ?? 'N/A');
    $template->setValue('unidad', $datos['unidad'] ?? 'N/A');

    // Fecha en texto: 3 de julio de 2025
    $meses = ['01'=>'enero', '02'=>'febrero', '03'=>'marzo', '04'=>'abril', '05'=>'mayo', '06'=>'junio',
              '07'=>'julio', '08'=>'agosto', '09'=>'septiembre', '10'=>'octubre', '11'=>'noviembre', '12'=>'diciembre'];
    $fecha_en_texto = date('j') . ' de ' . $meses[date('m')] . ' de ' . date('Y');
    $template->setValue('fecha_en_texto', $fecha_en_texto);

    // Guardar y descargar
    $nombre_archivo = "Poliza_{$numero_contrato}.docx";
    $ruta_guardado = __DIR__ . "/contratos_poliza/" . $nombre_archivo;
    $template->saveAs($ruta_guardado);
$nombre_completo = $datos['nombre_completo_contratista'] ?? 'Usuario';
header("Location: poliza_generada.php?archivo=" . urlencode($nombre_archivo) . "&nombre_completo=" . urlencode($nombre_completo));
exit;


} else {
    echo "Error al insertar contrato: " . $stmt->error;
}
?>

