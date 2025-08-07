<?php
require 'vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nro_contrato = '020';
    $anio_contrato = '2025';
    $nombre_contratista = 'ALEJANDRA ACOSTA RESTREPO';
    $cedula_contratista = '1017267793';
    $ciudad_expedicion_cedula_contratista = 'MEDELLIN';
    $direccion_contratista = 'CRA 52 # 77B - 60 APTO 502 EDF TORRES DE SAN FERNANDO';
    $definicion_rubro_presupuestal = 'A-02-02-02-009-003 “SERVICIOS PARA EL CUIDADO DE LA SALUD HUMANA Y SERVICIOS SOCIALES”';
    $cargo_contratista = 'AUXILIAR DE ENFERMERÍA';
    $anio_vigencia_contrato = '2025';
    $tipo_cuenta_bancaria = 'ahorros';
    $nro_cuenta_bancaria = '13045658382';
    $entidad_bancaria = 'BANCOLOMBIA';
    $lugar_ejecuccion = 'DISPENSARIO MÉDICO DE MEDELLÍN';
    $direccion_lugar_ejecuccion = 'Carrera 77c No. 51-136';
    $dia_final_contrato = '31';
    $mes_final_contrato = 'diciembre';
    $anio_final_contrato = '2025';
    $nombre_supervisor = 'MY. GRETTY MARCELA SOSA QUIROGA';
    $dia_firma_contrato_texto = 'quince (15)';
    $mes_firma_contrato = 'enero';
    $anio_firma_contrato = '2025';
    $nomb_asesor_juridico = 'Sebastián Monsalve M';
    $numero_depositos_texto = 'doce (12)';
    $valor_contrato_texto = 'VEINTITRÉS MILLONES OCHOCIENTOS NOVENTA Y SIETE MIL PESOS M/CTE ($23.987.000,00)';
    //para poliza
    $objecto = 'PRESTACION DE SERVICIOS PROFESIONALES, TECNICOS Y/O DE APOYO A LA GESTION COMO TECNOLOGO EN IMÁGENES DIAGNOSTICAS QUE REQUIERE EL DISPENSARIO MEDICO DE MEDELLIN PARA LA REGIONAL No. 7 DE SANIDAD MILITAR Y SUS UNIDADES CENTRALIZADAS, VIGENCIA 2025';
    $aseguradora = 'SEGUROS DEL ESTADO';
    $nit_aseguradora = '860.009.578-6';
    $numero_poliza = '25-44-101197575';
    $fecha_inicio_poliza = '15/01/2025';
    $fecha_fin_poliza = '15/01/2025';
    $precio_poliza_cumpliento = '2.285.800,00';
    $precio_poliza_calidad = '2.285.800,00';
    $numero_poliza_responsabilidad = '25-44-101197575';
    $fecha_inicio_poliza_responsabilidad = '15/01/2025';
    $fecha_fin_poliza_responsabilidad = '15/01/2025';
    $valor_poliza_responsabilidad = '284.700.000,00';
    $fecha_en_texto = '20 días del mes de enero de 2025';
    // 🔹 Datos de la tabla (mockeados)
    $pagos = [
        ['No' => 1, 'Mes' => 'ENERO 2025', 'Valor' => '$1.039.000,00']
       /* ['No' => 2, 'Mes' => 'FEBRERO 2025', 'Valor' => '$2.078.000,00'],
        ['No' => 3, 'Mes' => 'MARZO 2025', 'Valor' => '$2.078.000,00'],
        ['No' => 4, 'Mes' => 'ABRIL 2025', 'Valor' => '$2.078.000,00'],
        ['No' => 5, 'Mes' => 'MAYO 2025', 'Valor' => '$2.078.000,00'],
        ['No' => 6, 'Mes' => 'JUNIO 2025', 'Valor' => '$2.078.000,00']
        ['No' => 7, 'Mes' => 'JULIO 2025', 'Valor' => '$2.078.000,00'],
        ['No' => 8, 'Mes' => 'AGOSTO 2025', 'Valor' => '$2.078.000,00'],
        ['No' => 9, 'Mes' => 'SEPTIEMBRE 2025', 'Valor' => '$2.078.000,00'],
        ['No' => 10, 'Mes' => 'OCTUBRE 2025', 'Valor' => '$2.078.000,00'],
        ['No' => 11, 'Mes' => 'NOVIEMBRE 2025', 'Valor' => '$2.078.000,00'],
        ['No' => 12, 'Mes' => 'DICIEMBRE 2025', 'Valor' => '$2.078.000,00']*/
    ];

    $total_pago = '$23.897.000,00'; 

    // Cargar plantilla de Word
    $templateWord = new TemplateProcessor('plantilla_contratos.docx');

    // Reemplazo de valores generales
    $templateWord->setValue('nro_contrato', $nro_contrato);
    $templateWord->setValue('anio_contrato', $anio_contrato);
    $templateWord->setValue('nombre_contratista', $nombre_contratista);
    $templateWord->setValue('cedula_contratista', $cedula_contratista);
    $templateWord->setValue('ciudad_expedicion_cedula_contratista', $ciudad_expedicion_cedula_contratista);
    $templateWord->setValue('direccion_contratista', $direccion_contratista);
    $templateWord->setValue('definicion_rubro_presupuestal', $definicion_rubro_presupuestal);
    $templateWord->setValue('cargo_contratista', $cargo_contratista);
    $templateWord->setValue('anio_vigencia_contrato', $anio_vigencia_contrato);
    $templateWord->setValue('tipo_cuenta_bancaria', $tipo_cuenta_bancaria);
    $templateWord->setValue('nro_cuenta_bancaria', $nro_cuenta_bancaria);
    $templateWord->setValue('entidad_bancaria', $entidad_bancaria);
    $templateWord->setValue('lugar_ejecuccion', $lugar_ejecuccion);
    $templateWord->setValue('direccion_lugar_ejecuccion', $direccion_lugar_ejecuccion);
    $templateWord->setValue('dia_final_contrato', $dia_final_contrato);
    $templateWord->setValue('mes_final_contrato', $mes_final_contrato);
    $templateWord->setValue('anio_final_contrato', $anio_final_contrato);
    $templateWord->setValue('nombre_supervisor', $nombre_supervisor);
    $templateWord->setValue('dia_firma_contrato_texto', $dia_firma_contrato_texto);
    $templateWord->setValue('mes_firma_contrato', $mes_firma_contrato);
    $templateWord->setValue('anio_firma_contrato', $anio_firma_contrato);
    $templateWord->setValue('nomb_asesor_juridico', $nomb_asesor_juridico);
    $templateWord->setValue('numero_depositos_texto', $numero_depositos_texto);
    $templateWord->setValue('valor_contrato_texto', $valor_contrato_texto);

    
    // 🔹 Insertar tabla dinámica de pagos
    $templateWord->cloneRow('No', count($pagos));

    foreach ($pagos as $index => $pago) {
        $templateWord->setValue('No#' . ($index + 1), $pago['No']);
        $templateWord->setValue('Mes#' . ($index + 1), $pago['Mes']);
        $templateWord->setValue('Valor#' . ($index + 1), $pago['Valor']);
    }

    // 🔹 Insertar total de pagos
    $templateWord->setValue('total_pago', $total_pago);

    // Guardar el documento modificado
    $outputFile = 'Contrato_Modificado.docx';
    $pdfFile = "Contrato_Generado.pdf";

    $templateWord->saveAs($outputFile);

    // Forzar la descarga del archivo
    header("Content-Disposition: attachment; filename=$outputFile");
    header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
    readfile($outputFile);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Contrato</title>
</head>
<body>
    <h2>Generar Contrato</h2>
    <form action="" method="post">
        <button type="submit">Generar Documento</button>
    </form>
</body>
</html>
