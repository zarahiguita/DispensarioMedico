<?php
require 'vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variables para contrato
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
    $total_pago = '$23.897.000,00';

    $pagos = [['No' => 1, 'Mes' => 'ENERO 2025', 'Valor' => '$1.039.000,00']];

    // Variables para póliza
    $objeto = 'PRESTACION DE SERVICIOS PROFESIONALES, TECNICOS Y/O DE APOYO A LA GESTION COMO TECNOLOGO EN IMÁGENES DIAGNOSTICAS QUE REQUIERE EL DISPENSARIO MEDICO DE MEDELLIN PARA LA REGIONAL No. 7 DE SANIDAD MILITAR Y SUS UNIDADES CENTRALIZADAS, VIGENCIA 2025';
    $aseguradora = 'NOMBRE ASEGURADORA';
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

    // Documento contrato
    $templateContrato = new TemplateProcessor('plantilla_contratos.docx');
    $templateContrato->setValue('nro_contrato', $nro_contrato);
    $templateContrato->setValue('anio_contrato', $anio_contrato);
    $templateContrato->setValue('nombre_contratista', $nombre_contratista);
    $templateContrato->setValue('cedula_contratista', $cedula_contratista);
    $templateContrato->setValue('ciudad_expedicion_cedula_contratista', $ciudad_expedicion_cedula_contratista);
    $templateContrato->setValue('direccion_contratista', $direccion_contratista);
    $templateContrato->setValue('definicion_rubro_presupuestal', $definicion_rubro_presupuestal);
    $templateContrato->setValue('cargo_contratista', $cargo_contratista);
    $templateContrato->setValue('anio_vigencia_contrato', $anio_vigencia_contrato);
    $templateContrato->setValue('tipo_cuenta_bancaria', $tipo_cuenta_bancaria);
    $templateContrato->setValue('nro_cuenta_bancaria', $nro_cuenta_bancaria);
    $templateContrato->setValue('entidad_bancaria', $entidad_bancaria);
    $templateContrato->setValue('lugar_ejecuccion', $lugar_ejecuccion);
    $templateContrato->setValue('direccion_lugar_ejecuccion', $direccion_lugar_ejecuccion);
    $templateContrato->setValue('dia_final_contrato', $dia_final_contrato);
    $templateContrato->setValue('mes_final_contrato', $mes_final_contrato);
    $templateContrato->setValue('anio_final_contrato', $anio_final_contrato);
    $templateContrato->setValue('nombre_supervisor', $nombre_supervisor);
    $templateContrato->setValue('dia_firma_contrato_texto', $dia_firma_contrato_texto);
    $templateContrato->setValue('mes_firma_contrato', $mes_firma_contrato);
    $templateContrato->setValue('anio_firma_contrato', $anio_firma_contrato);
    $templateContrato->setValue('nomb_asesor_juridico', $nomb_asesor_juridico);
    $templateContrato->setValue('numero_depositos_texto', $numero_depositos_texto);
    $templateContrato->setValue('valor_contrato_texto', $valor_contrato_texto);
    $templateContrato->cloneRow('No', count($pagos));
    foreach ($pagos as $index => $pago) {
        $templateContrato->setValue('No#' . ($index + 1), $pago['No']);
        $templateContrato->setValue('Mes#' . ($index + 1), $pago['Mes']);
        $templateContrato->setValue('Valor#' . ($index + 1), $pago['Valor']);
    }
    $templateContrato->setValue('total_pago', $total_pago);
    $outputContrato = 'Contrato_Modificado.docx';
    $templateContrato->saveAs($outputContrato);

    // Documento póliza
    $templatePoliza = new TemplateProcessor('plantilla_poliza.docx');
        $templatePoliza->setValue('nro_contrato', $nro_contrato);
    $templatePoliza->setValue('anio_contrato', $anio_contrato);
    $templatePoliza->setValue('objeto', $objeto);
    $templatePoliza->setValue('nombre_contratista', $nombre_contratista);
    $templatePoliza->setValue('cedula_contratista', $cedula_contratista);
    $templatePoliza->setValue('aseguradora', $aseguradora);
    $templatePoliza->setValue('nit_aseguradora', $nit_aseguradora);
    $templatePoliza->setValue('numero_poliza', $numero_poliza);
    $templatePoliza->setValue('fecha_inicio_poliza', $fecha_inicio_poliza);
    $templatePoliza->setValue('fecha_fin_poliza', $fecha_fin_poliza);
    $templatePoliza->setValue('precio_poliza_cumpliento', $precio_poliza_cumpliento);
    $templatePoliza->setValue('precio_poliza_calidad', $precio_poliza_calidad);
    $templatePoliza->setValue('numero_poliza_responsabilidad', $numero_poliza_responsabilidad);
    $templatePoliza->setValue('fecha_inicio_poliza_responsabilidad', $fecha_inicio_poliza_responsabilidad);
    $templatePoliza->setValue('fecha_fin_poliza_responsabilidad', $fecha_fin_poliza_responsabilidad);
    $templatePoliza->setValue('valor_poliza_responsabilidad', $valor_poliza_responsabilidad);
    $templatePoliza->setValue('fecha_en_texto', $fecha_en_texto);
    $outputPoliza = 'Poliza_Modificada.docx';
    $templatePoliza->saveAs($outputPoliza);

    // Crear ZIP
    $zip = new ZipArchive();
    $zipFile = 'Documentos_Contrato_Poliza.zip';
    if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
        $zip->addFile($outputContrato);
        $zip->addFile($outputPoliza);
        $zip->close();

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipFile . '"');
        header('Content-Length: ' . filesize($zipFile));
        readfile($zipFile);
        unlink($outputContrato);
        unlink($outputPoliza);
        unlink($zipFile);
        exit;
    } else {
        echo "No se pudo generar el archivo ZIP.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Contrato y Póliza V2</title>
</head>
<body>
    <h2>Generar Contrato y Póliza V2</h2>
    <form method="post">
        <button type="submit">Generar Documentos</button>
    </form>
</body>
</html>
