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



    function numeroALetras($numero)
    {
        $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);
        $letras = strtoupper($formatter->format($numero));
        return $letras;
    }

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


 // Configurar fecha en español sin strftime()
$formatterFecha = new IntlDateFormatter(
    'es_ES',
    IntlDateFormatter::LONG,
    IntlDateFormatter::NONE,
    'America/Bogota',
    IntlDateFormatter::GREGORIAN
);
$fecha_formateada = $formatterFecha->format(new DateTime($fecha_inicio));
$fecha_formateada = ucfirst(mb_strtolower($fecha_formateada, 'UTF-8'));
//fin

    // Valor numérico en texto
    $valor_numerico = $datos['valor_total_contrato'];
    $valor_letras = numeroALetras($valor_numerico);
    $valor_completo = "VALOR DEL CONTRATO " . $valor_letras . " DE PESOS M/CTE ($" . number_format($valor_numerico, 0, ',', '.') . ",00) IVA INCLUIDO";
    //FIN

    // MOCK: datos del representante legal (temporal)
    $representante_nombre = "TITO ALBERTO ZAPATA BEDOYA";
    $representante_cc = "71.614.965";
    $representante_lugar_cc = "Medellín";

    // Armamos texto del contratista con representante legal
    $texto_contratista = $datos['nombre_completo_contratista']
        . " con NIT " . $datos['documento_identidad']
        . "\nDelegado administrativo y judicial " . $representante_nombre
        . "\nCC No. " . $representante_cc . " expedida en " . $representante_lugar_cc;
    //FIN



    $forma_pago_texto = "FORMA DE REMUNERACIÓN AL CONTRATISTA\tMDN – EJÉRCITO NACIONAL – DISPENSARIO MEDICO DE MEDELLIN, se obliga a pagar el 100% del valor del contrato, de la siguiente forma: 
Los pagos que de conformidad con este contrato deba efectuar el MDN –EJÉRCITO NACIONAL–DISPENSARIO MEDICO DE MEDELLIN, al contrato se imputará a las apropiaciones presupuestales de la vigencia 2025, 
SIIF- CDP No. {$datos['cdp']} del " . strftime('%d de %B de %Y', strtotime($datos['fecha_cdp'])) . " y  CRP No. {$datos['rp']} del " . strftime('%d de %B de %Y', strtotime($datos['fecha_rp'])) . ", expedido por el Jefe de Presupuesto del DMMED, por el Rubro presupuestal {$datos['rubro_presupuestal']} {$datos['descripcion_rubro']}, recurso 16 SSF, por valor de {$valor_letras} DE PESOS M/CTE ($" . number_format($valor_numerico, 0, ',', '.') . ",00) IVA INCLUIDO, pagos parciales de acuerdo al recibido a satisfacción mensual de cada solicitud realizada por el supervisor del contrato de acuerdo a las necesidades de la Dirección de Sanidad Ejército – Dispensario Médico de Medellín, previo cumplimiento de los siguientes requisitos:

a) Acta de recibo a satisfacción parcial, expedida por el supervisor, y representante del contratista.
b) Situación de recursos por parte del Ministerio de Hacienda y Crédito Público, Dirección del Tesoro Nacional (asignación cupo PAC).
c) Que se ejecuten los demás trámites administrativos correspondientes.
d) Verificación por parte del MDN – EJÉRCITO NACIONAL – DIRECCIÓN DE SANIDAD – DISPENSARIO MEDICO DE MEDELLIN del cumplimiento del contratista del pago de aportes parafiscales y los propios del SENA, ICBF y Cajas de Compensación Familiar.
e) Documento equivalente a factura según artículo 3 del Decreto 522 de 2003.

PARÁGRAFO PRIMERO. Este pago se considera de contado por lo que no se aceptará el cobro de financiación en este caso.
PARÁGRAFO SEGUNDO. En el evento de prórroga en la entrega del objeto del contrato, se postergará el pago.
PARÁGRAFO TERCERO: El MDN – EJÉRCITO NACIONAL – DIRECCIÓN DE SANIDAD – DISPENSARIO MEDICO DE MEDELLIN, realizará los pagos en la cuenta.
PARÁGRAFO CUARTO: L";



    $fecha_fin_contrato = strtoupper(strftime('%d de %B de %Y', strtotime($datos['fecha_terminacion_contrato'])));

    if ($modalidad === 'Prestación de Servicios') {
        $texto_plazo = "Será una vez se haya constituido y aprobada la póliza de garantía, se cuente con los soportes presupuestales que respalden la ejecución, hasta el {$fecha_fin_contrato}, y/o hasta agotar presupuesto asignado (lo que primero ocurra).";
    } else {
        $texto_plazo = "Desde la aprobación de la garantía y el registro presupuestal, sin exceder el {$fecha_fin_contrato}.";
    }

    $template->setValue('lugar', 'Medellín, Antioquia');
    $template->setValue('fecha_acta', replace: date('d \d\e F \d\e Y', strtotime($fecha_inicio)));
    $template->setValue('nombre_subdirector', 'MARLON GÓMEZ RODRÍGUEZ');
    $template->setValue('cargo_subdirector', 'Subdirector Administrativo y financiero del DMMED');


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
    $template->setValue('forma_pago', $forma_pago_texto);

    $template->setValue('cargo_contratista', replace: $datos['profesion'] ?? 'N/A');

    $template->setValue('plazo_contrato', $texto_plazo);

    // Seleccionar plantilla según modalidad
    if ($modalidad === 'Prestación de Servicios') {
        $modalidad_boolean = "contratos_acta_inicio_prestacion/";
        $template->setValue('numero_acta', '00000214');
        $template->setValue('anio_contrato', date('Y'));
        $template->setValue('nit_contratista', $datos['documento_identidad']);
        $template->setValue('representante_legal', replace: $representante_nombre);
        $template->setValue(search: 'contratista', replace: $texto_contratista);

        $template->setValue('nit_representante_legal', $representante_cc);

    } else {
        $template = new TemplateProcessor(__DIR__ . '/plantilla_acta_inicio_2.docx');
        $modalidad_boolean = "contratos_acta_inicio_contrato/";
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