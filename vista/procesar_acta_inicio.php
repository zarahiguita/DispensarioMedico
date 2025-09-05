<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Bogota');

require __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

// 🔧 Ajusta esta ruta según tu proyecto
include('../bd/cn.php');

// ==== Helpers ====
function fechaLargaEs($iso, $locale='es_CO', $tz='America/Bogota'){
    if(!$iso) return '';
    if(class_exists('IntlDateFormatter')){
        $fmt = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE, $tz, IntlDateFormatter::GREGORIAN);
        $dt  = new DateTime($iso, new DateTimeZone($tz));
        return ucfirst(mb_strtolower($fmt->format($dt), 'UTF-8'));
    }
    return date('d \d\e F \d\e Y', strtotime($iso)); // fallback
}
function numeroALetras($numero){
    if(class_exists('NumberFormatter')){
        $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);
        return strtoupper($formatter->format((float)$numero));
    }
    return number_format((float)$numero, 0, ',', '.'); // fallback
}

// ==== POST ====
$cedula           = $_POST["cedula"];
$modalidad        = $_POST["modalidad"];
$fecha_inicio     = $_POST["fecha_inicio"];
$supervisor       = $_POST["supervisor"];
$asesor_juridico  = $_POST["asesor_juridico"];

$nuevo_supervisor = $_POST["nuevo_supervisor"] ?? '';
$nuevo_asesor     = $_POST["nuevo_asesor"] ?? '';

$rp               = $_POST["rp"] ?? '';
$numero_rp        = $_POST["numero_rp"] ?? '';
$fecha_rp         = $_POST["fecha_rp"] ?? '';
$valor_rp         = $_POST["valor_rp"] ?? '';

$ordenador_gasto  = $_POST["ordenador_gasto"] ?? '';
$grado_ordenador  = $_POST["grado_ordenador"] ?? '';
$nombre_ordenador = $_POST["nombre_ordenador"] ?? '';
$ced_ordenador    = $_POST["cedula_ordenador"] ?? '';
$lugar_exp_ord    = $_POST["lugar_expedicion_ordenador"] ?? '';

// NUEVOS campos (plantilla editable)
$lugar                  = $_POST['lugar'] ?? 'Medellín, Antioquia';
$nombre_subdirector     = $_POST['nombre_subdirector'] ?? 'MARLON GÓMEZ RODRÍGUEZ';
$cargo_subdirector      = $_POST['cargo_subdirector'] ?? 'Subdirector Administrativo y financiero del DMMED';
$representante_nombre   = $_POST['representante_legal'] ?? 'TITO ALBERTO ZAPATA BEDOYA';
$representante_cc       = $_POST['representante_cc'] ?? '71.614.965';
$representante_lugar_cc = $_POST['representante_lugar_cc'] ?? 'Medellín';
$numero_acta            = $_POST['numero_acta'] ?? '';
$numero_folio            = $_POST['numero_folio'] ?? '';

$anio_contrato          = $_POST['anio_contrato'] ?? date('Y');

$forma_pago_texto_post  = $_POST['forma_pago_texto'] ?? '';
$texto_plazo_post       = $_POST['texto_plazo'] ?? '';

// ==== RP nuevo (si aplica) ====
if ($rp === 'nuevo' && $numero_rp && $fecha_rp && $valor_rp) {
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

// ==== Altas rápidas ====
if ($nuevo_supervisor) {
    $conexion->query("INSERT INTO supervisor (nombre_supervisor, fecha_creacion, fecha_actualizacion) VALUES ('".$conexion->real_escape_string($nuevo_supervisor)."', NOW(), NOW())");
    $supervisor = $nuevo_supervisor;
}
if ($nuevo_asesor) {
    $conexion->query("INSERT INTO asesor_juridico (nombre, cedula) VALUES ('".$conexion->real_escape_string($nuevo_asesor)."', '')");
    $asesor_juridico = $nuevo_asesor;
}
if ($ordenador_gasto === 'nuevo') {
    if ($nombre_ordenador) {
        $conexion->query("INSERT INTO ordenador_gasto (grado, nombre, cedula, lugar_expedicion_cedula)
                          VALUES ('".$conexion->real_escape_string($grado_ordenador)."',
                                  '".$conexion->real_escape_string($nombre_ordenador)."',
                                  '".$conexion->real_escape_string($ced_ordenador)."',
                                  '".$conexion->real_escape_string($lugar_exp_ord)."')");
        $ordenador_gasto_final = $nombre_ordenador;
    } else {
        $ordenador_gasto_final = 'N/A';
    }
} else {
    $ordenador_gasto_final = $ordenador_gasto;
}

// ==== Actualiza algunos campos en contrato_detallado ====
$sql = "UPDATE contrato_detallado SET 
        fecha_acta_inicio = ?,  
        supervisor = ?, 
        nombre_asesor_juridico = ?,
        rp = ?,
        fecha_rp = ?,
        valor_pago_mensual = ?
        WHERE documento_identidad = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
    "sssssss",
    $fecha_inicio,
    $supervisor,
    $asesor_juridico,
    $rp_final,     // <-- usar el final
    $fecha_rp,
    $valor_rp,
    $cedula
);

// ==== Ejecuta y genera DOCX ====
if ($stmt->execute()) {

    // Datos del contrato
    $stmt2 = $conexion->prepare("SELECT * FROM contrato_detallado WHERE documento_identidad = ? LIMIT 1");
    $stmt2->bind_param("s", $cedula);
    $stmt2->execute();
    $datos = $stmt2->get_result()->fetch_assoc();
    $stmt2->close();

    if (!$datos) {
        die("No se encontró información para la cédula proporcionada.");
    }

    // Plantilla según modalidad
    if ($modalidad === 'Prestación de Servicios') {
        $template = new TemplateProcessor(__DIR__ . '/plantilla_acta_inicio_1.docx');
    } else {
        $template = new TemplateProcessor(__DIR__ . '/plantilla_acta_inicio_2.docx');
    }

    // Fechas y textos derivados
    $fecha_formateada = fechaLargaEs($fecha_inicio);
    $valor_numerico   = $datos['valor_total_contrato'];
    $valor_letras     = numeroALetras($valor_numerico);
    $valor_completo   = "VALOR DEL CONTRATO " . $valor_letras . " DE PESOS M/CTE ($" . number_format($valor_numerico, 0, ',', '.') . ",00) IVA INCLUIDO";

    $fecha_cdp_fmt  = fechaLargaEs($datos['fecha_cdp'] ?? '');
    $fecha_rp_fmt   = fechaLargaEs($datos['fecha_rp'] ?? '');
    $fecha_fin_fmt  = mb_strtoupper(fechaLargaEs($datos['fecha_terminacion_contrato'] ?? ''), 'UTF-8');

    // Fallbacks si llegan vacíos desde el form
    if (trim($forma_pago_texto_post) === '') {
        $forma_pago_texto_post = "FORMA DE REMUNERACIÓN AL CONTRATISTA\tMDN – EJÉRCITO NACIONAL – DISPENSARIO MEDICO DE MEDELLIN, se obliga a pagar el 100% del valor del contrato, de la siguiente forma: 
Los pagos que de conformidad con este contrato deba efectuar el MDN –EJÉRCITO NACIONAL–DISPENSARIO MEDICO DE MEDELLIN, al contrato se imputará a las apropiaciones presupuestales de la vigencia 2025, 
SIIF- CDP No. {$datos['cdp']} del {$fecha_cdp_fmt} y CRP No. {$rp_final} del {$fecha_rp_fmt}, expedido por el Jefe de Presupuesto del DMMED, por el Rubro presupuestal {$datos['rubro_presupuestal']} {$datos['descripcion_rubro']}, recurso 16 SSF, por valor de {$valor_letras} DE PESOS M/CTE ($" . number_format($valor_numerico, 0, ',', '.') . ",00) IVA INCLUIDO, pagos parciales de acuerdo al recibido a satisfacción mensual de cada solicitud realizada por el supervisor del contrato de acuerdo a las necesidades de la Dirección de Sanidad Ejército – Dispensario Médico de Medellín, previo cumplimiento de los siguientes requisitos:

a) Acta de recibo a satisfacción parcial, expedida por el supervisor, y representante del contratista.
b) Situación de recursos por parte del Ministerio de Hacienda y Crédito Público, Dirección del Tesoro Nacional (asignación cupo PAC).
c) Que se ejecuten los demás trámites administrativos correspondientes.
d) Verificación por parte del MDN – EJÉRCITO NACIONAL – DIRECCIÓN DE SANIDAD – DISPENSARIO MEDICO DE MEDELLIN del cumplimiento del contratista del pago de aportes parafiscales y los propios del SENA, ICBF y Cajas de Compensación Familiar.
e) Documento equivalente a factura según artículo 3 del Decreto 522 de 2003.

PARÁGRAFO PRIMERO. Este pago se considera de contado por lo que no se aceptará el cobro de financiación en este caso.
PARÁGRAFO SEGUNDO. En el evento de prórroga en la entrega del objeto del contrato, se postergará el pago.
PARÁGRAFO TERCERO: El MDN – EJÉRCITO NACIONAL – DIRECCIÓN DE SANIDAD – DISPENSARIO MEDICO DE MEDELLIN, realizará los pagos en la cuenta.
PARÁGRAFO CUARTO: L";
    }

    $texto_plazo_default_prestacion = "Será una vez se haya constituido y aprobada la póliza de garantía, se cuente con los soportes presupuestales que respalden la ejecución, hasta el {$fecha_fin_fmt}, y/o hasta agotar presupuesto asignado (lo que primero ocurra).";
    $texto_plazo_default_contrato   = "Desde la aprobación de la garantía y el registro presupuestal, sin exceder el {$fecha_fin_fmt}.";
    if (trim($texto_plazo_post) === '') {
        $texto_plazo_post = ($modalidad === 'Prestación de Servicios') ? $texto_plazo_default_prestacion : $texto_plazo_default_contrato;
    }

    // Texto de contratista con representante legal
    $texto_contratista = $datos['nombre_completo_contratista']
        . " con NIT " . $datos['documento_identidad']
        . "\nDelegado administrativo y judicial " . $representante_nombre
        . "\nCC No. " . $representante_cc . " expedida en " . $representante_lugar_cc;

    // === Set de valores para la plantilla ===
    $template->setValue('lugar', $lugar);
    $template->setValue('fecha_acta', date('d \d\e F \d\e Y', strtotime($fecha_inicio)));
    $template->setValue('nombre_subdirector', $nombre_subdirector);
    $template->setValue('cargo_subdirector', $cargo_subdirector);

    $template->setValue('nombre_supervisor', $datos['supervisor']);
    $template->setValue('numero_contrato', $datos['no_contrato']);
    $template->setValue('nombre_contratista', $datos['nombre_completo_contratista']);
    $template->setValue('cedula_contratista', $datos['documento_identidad']);
    $template->setValue('lugar_cedula_contratista', $datos['lugar_expedicion_documento'] ?? 'N/A');
    $template->setValue('objeto_contrato', $datos['objeto']);
    $template->setValue('fecha_acta_inicio', $fecha_formateada);
    $template->setValue('fecha_suscripcion', date('d \d\e F \d\e Y', strtotime($datos['fecha_suscripcion'])));
    $template->setValue('valor_letras', $valor_completo);
    $template->setValue('valor_numerico', '$' . number_format($datos['valor_total_contrato'], 0, ',', '.'));
    $template->setValue('forma_pago', $forma_pago_texto_post);

    $template->setValue('cargo_contratista', $datos['profesion'] ?? 'N/A');
    $template->setValue('plazo_contrato', $texto_plazo_post);

    if ($modalidad === 'Prestación de Servicios') {
        $modalidad_boolean = "contratos_acta_inicio_prestacion/";
        $template->setValue('numero_acta', $numero_acta ?: '00000000');
        $template->setValue('folio', $numero_folio ?: '38');

        $template->setValue('anio_contrato', $anio_contrato);
        $template->setValue('nit_contratista', $datos['documento_identidad']);
        $template->setValue('representante_legal', $representante_nombre);
        $template->setValue('contratista', $texto_contratista);
        $template->setValue('nit_representante_legal', $representante_cc);
    } else {
        $modalidad_boolean = "contratos_acta_inicio_contrato/";
        // Si tu plantilla 2 usa otros placeholders, añádelos aquí
    }

    // === Guardar DOCX ===
    $nombre_archivo = "Acta_Inicio_{$datos['no_contrato']}.docx";
    $ruta_guardado  = __DIR__ . "/" . $modalidad_boolean . $nombre_archivo;

    // Crea carpeta si no existe
    if (!is_dir(dirname($ruta_guardado))) {
        @mkdir(dirname($ruta_guardado), 0775, true);
    }
    $template->saveAs($ruta_guardado);

    // Redirige a vista de éxito
    $nombre_completo = $datos['nombre_completo_contratista'];
    header("Location: acta_inicio_generada.php?archivo=" . urlencode($nombre_archivo) . "&ruta=" . urlencode($modalidad_boolean) . "&nombre_completo=" . urlencode($nombre_completo));
    exit;

} else {
    echo "<script>alert('❌ Error al guardar el acta.'); window.history.back();</script>";
    exit;
}
