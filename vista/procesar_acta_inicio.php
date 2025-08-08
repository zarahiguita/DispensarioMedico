<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Bogota');
require __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;


include('../bd/cn.php');

$cedula = $_POST["cedula"];
$modalidad = $_POST["modalidad"];

echo "<script>alert('Modalidad recibida: " . strtolower(trim(string: $modalidad)) === 'prestación de servicios' . "');</script>";

$fecha_inicio = $_POST["fecha_inicio"];
$supervisor = $_POST["supervisor"];
$asesor_juridico = $_POST["asesor_juridico"];
$nuevo_supervisor = $_POST["nuevo_supervisor"] ?? '';
$nuevo_asesor = $_POST["nuevo_asesor"] ?? '';
$rp = $_POST["rp"] ?? '';
$numero_rp = $_POST["numero_rp"] ?? '';
$fecha_rp = $_POST["fecha_rp"] ?? '';
$valor_rp = $_POST["valor_rp"] ?? '';

if ($rp === 'nuevo' && $numero_rp && $fecha_rp && $valor_rp) {
    // Verificar si ya existe ese número de RP
    $verifica = $conexion->prepare("SELECT id FROM rp WHERE numero = ?");
    $verifica->bind_param("s", $numero_rp);
    $verifica->execute();
    $verifica->store_result();

    if ($verifica->num_rows === 0) {
        $inserta = $conexion->prepare("INSERT INTO rp (numero, fecha, valor) VALUES (?, ?, ?)");
        $inserta->bind_param("ssd", $numero_rp, $fecha_rp, $valor_rp);
        $inserta->execute();
        $inserta->close();
    }

    $verifica->close();
    $rp_final = $numero_rp;
} else {
    $rp_final = $rp;
}



if ($nuevo_supervisor) {
    $conexion->query("INSERT INTO supervisor (nombre_supervisor, fecha_creacion, fecha_actualizacion) VALUES ('$nuevo_supervisor', NOW(), NOW())");
    $supervisor = $nuevo_supervisor;
}
if ($nuevo_asesor) {
    $conexion->query("INSERT INTO asesor_juridico (nombre, cedula) VALUES ('$nuevo_asesor', '')");
    $asesor_juridico = $nuevo_asesor;
}
// ORDENADOR DEL GASTO
$ordenador_gasto = $_POST["ordenador_gasto"];
if ($ordenador_gasto === 'nuevo') {
    $grado = $_POST["grado_ordenador"] ?? '';
    $nombre = $_POST["nombre_ordenador"] ?? '';
    $cedula_ordenador = $_POST["cedula_ordenador"] ?? '';
    $lugar_expedicion = $_POST["lugar_expedicion_ordenador"] ?? '';

    if ($nombre) {
        $conexion->query("INSERT INTO ordenador_gasto (grado, nombre, cedula, lugar_expedicion_cedula)
                          VALUES ('$grado', '$nombre', '$cedula_ordenador', '$lugar_expedicion')");
        $ordenador_gasto_final = $nombre;
    } else {
        $ordenador_gasto_final = 'N/A';
    }
} else {
    $ordenador_gasto_final = $ordenador_gasto;
}


$sql = "UPDATE contrato_detallado SET 
        fecha_acta_inicio = ?,  
        supervisor = ?, 
        nombre_asesor_juridico = ?,
        rp = ?,
        fecha_rp = ?,
        valor_pago_mensual = ?
        WHERE documento_identidad = ?";

$stmt = $conexion->prepare(query: $sql);
$stmt->bind_param(
    "sssssss", // 7 parámetros tipo string
    $fecha_inicio,
    $supervisor,
    $asesor_juridico,
    $rp,
    $fecha_rp,
    $valor_rp,
    $cedula
);

if ($stmt->execute()) {

    // Consultar datos del contrato
    $stmt = $conexion->prepare("SELECT * FROM contrato_detallado WHERE documento_identidad = ?");
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $datos = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$datos) {
        die("No se encontró información para la cédula proporcionada.");
    }

    $modalidad_boolean = "";

    if ($modalidad === 'Prestación de Servicios') {
        $template = new TemplateProcessor('plantilla_acta_inicio_1.docx');

    } else {
        $template = new TemplateProcessor(documentTemplate: 'plantilla_acta_inicio_2.docx');

    }

    // Seleccionar plantilla según modalidad
    if ($modalidad === 'Prestación de Servicios') {
        $modalidad_boolean = "contratos_acta_inicio_prestacion/";
        $template->setValue('numero_acta', '00000214');
        $template->setValue('lugar', 'Medellín, Antioquia');
        $template->setValue('fecha_acta', date('d \d\e F \d\e Y', strtotime($fecha_inicio)));

        $template->setValue('nombre_subdirector', 'MARLON GÓMEZ RODRÍGUEZ');
        $template->setValue('cargo_subdirector', 'Subdirector Administrativo y financiero del DMMED');

        $template->setValue('nombre_supervisor', $datos['supervisor']);
        $template->setValue('numero_contrato', $datos['no_contrato']);
        $template->setValue('nombre_contratista', $datos['nombre_completo_contratista']);
        $template->setValue('nit_contratista', $datos['documento_identidad']);
        $template->setValue('representante_legal', $datos['nombre_completo_contratista']);
        $template->setValue('cedula_contratista', $datos['documento_identidad']);
        $template->setValue('lugar_cedula_contratista', $datos['lugar_expedicion_documento'] ?? 'N/A');
        $template->setValue('objeto_contrato', $datos['objeto']);
        $template->setValue('fecha_acta_inicio', date('d \d\e F \d\e Y', strtotime($fecha_inicio)));
        $template->setValue('fecha_suscripcion', date('d \d\e F \d\e Y', strtotime($datos['fecha_suscripcion'])));
        $template->setValue('valor_letras', 'VALOR EN LETRAS'); // Puedes implementar conversión numérica a texto
        $template->setValue('valor_numerico', '$' . number_format($datos['valor_total_contrato'], 0, ',', '.'));
        $template->setValue('forma_pago', 'Forma de pago según el contrato.');
        $template->setValue('plazo_contrato', 'Desde la aprobación de la póliza hasta el 30 de julio de 2025, o hasta agotar el presupuesto asignado, lo que ocurra primero.');
        $template->setValue('nit_representante_legal', $datos['documento_identidad']);
        $template->setValue('cargo_contratista', $datos['profesion'] ?? 'N/A');

    } else {
        $template = new TemplateProcessor(__DIR__ . '/plantilla_acta_inicio_2.docx');
        $modalidad_boolean = "contratos_acta_inicio_contrato/";

        $template->setValue('lugar', 'Medellín, Antioquia');
        $template->setValue('fecha_acta', date('d \d\e F \d\e Y', strtotime($fecha_inicio)));
        $template->setValue('nombre_subdirector', 'MARLON GÓMEZ RODRÍGUEZ');
        $template->setValue('cargo_subdirector', 'Subdirector Administrativo y financiero del DMMED');
        $template->setValue('nombre_supervisor', $datos['supervisor']);
        $template->setValue('numero_contrato', $datos['no_contrato']);
        $template->setValue('nombre_contratista', $datos['nombre_completo_contratista']);
        $template->setValue('cedula_contratista', $datos['documento_identidad']);
        $template->setValue('lugar_cedula_contratista', $datos['lugar_expedicion_documento'] ?? 'N/A');
        $template->setValue('cargo_contratista', $datos['profesion'] ?? 'N/A');
        $template->setValue('objeto_contrato', $datos['objeto']);
        $template->setValue('fecha_acta_inicio', date('d \d\e F \d\e Y', strtotime($fecha_inicio)));
        $template->setValue('fecha_suscripcion', date('d \d\e F \d\e Y', strtotime($datos['fecha_suscripcion'])));
        $template->setValue('valor_letras', 'VALOR EN LETRAS');
        $template->setValue('valor_numerico', '$' . number_format($datos['valor_total_contrato'], 0, ',', '.'));
        $template->setValue('forma_pago', 'Forma de pago según el contrato.');
        $template->setValue('plazo_contrato', 'Desde la aprobación de la garantía y el registro presupuestal, sin exceder el 31 de diciembre de 2025.');
    }

    $nombre_archivo = "Acta_Inicio_{$datos['no_contrato']}.docx";


    $ruta_guardado = __DIR__ . "/" . $modalidad_boolean . $nombre_archivo;
    $template->saveAs($ruta_guardado);


    $nombre_completo = $datos['nombre_completo_contratista'];
    //header("Location: acta_inicio_generada.php?archivo=" . urlencode($nombre_archivo) . "&nombre_completo=" . urlencode($nombre_completo));
    header("Location: acta_inicio_generada.php?archivo=" . urlencode($nombre_archivo) . "&ruta=" . urlencode($modalidad_boolean) . "&nombre_completo=" . urlencode($nombre_completo));

    exit;


} else {
    echo "<script>alert('❌ Error al guardar el acta.'); window.history.back();</script>";
}
exit;