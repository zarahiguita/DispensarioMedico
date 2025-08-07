<?php


$template = new \PhpOffice\PhpWord\TemplateProcessor('plantilla_acta_inicio_1.docx');


//Revisar si ya existen para no volver a pedirlas.....
$template->setValue('numero_acta', '00000214'); // Puedes generarlo dinámico si lo deseas
$template->setValue('lugar', 'Medellín, Antioquia');
$template->setValue('fecha_acta', '03 de abril de 2025'); // O date('d \d\e F \d\e Y')

$template->setValue('nombre_subdirector', 'MARLON GÓMEZ RODRÍGUEZ');
$template->setValue('cargo_subdirector', 'Subdirector Administrativo y financiero del DMMED');

$template->setValue('nombre_supervisor', 'ELMER DE JESUS TAPASCO CRUZ');
$template->setValue('numero_contrato', '334-DMMED-2025');

$template->setValue('nombre_contratista', 'SOLUCIONES HOSPITALARIAS DE LA COSTA S.A.S.');
$template->setValue('nit_contratista', '900.049.143-1');
$template->setValue('representante_legal', 'CARLOS JULIO MEJIA FERREIRA');
$template->setValue('cedula_contratista', '8.668.441');
$template->setValue('lugar_cedula_contratista', 'Barranquilla');

$template->setValue('objeto_contrato', 'SUMINISTRO DE PAÑALES DESECHABLES SEGÚN FORMULACIÓN MÉDICA (PAÑALES - PAÑITOS) PARA LOS BENEFICIARIOS Y USUARIOS DEL DISPENSARIO MÉDICO DE MEDELLÍN Y SUS SATÉLITES POR INTERMEDIO DE ÓRDENES JUDICIALES PARA LA VIGENCIA DE 2025');

$template->setValue('fecha_acta_inicio', '03 de abril del 2025');
$template->setValue('fecha_suscripcion', '31 de marzo del 2025');

$template->setValue('valor_letras', 'QUINIENTOS DOCE MILLONES DE PESOS M/CTE');
$template->setValue('valor_numerico', '$512.000.000,00');

$template->setValue('forma_pago', 'MDN – EJÉRCITO NACIONAL – DISPENSARIO MÉDICO DE MEDELLÍN se obliga a pagar el 100% del valor del contrato en pagos parciales mensuales, conforme a lo solicitado por el supervisor, con soporte en actas de recibido a satisfacción y disponibilidad presupuestal.');

$template->setValue('plazo_contrato', 'Desde la aprobación de la póliza hasta el 30 de julio de 2025, o hasta agotar el presupuesto asignado, lo que ocurra primero.');

$template->setValue('nit_representante_legal', '900.049.143-1'); // Repetido como parte final





$template = new \PhpOffice\PhpWord\TemplateProcessor('plantilla_acta_inicio_2.docx');

//el 2
$template->setValue('lugar', 'Medellín, Antioquia');
$template->setValue('fecha_acta', '03 de julio de 2025');

$template->setValue('nombre_subdirector', 'MARLON GÓMEZ RODRÍGUEZ');
$template->setValue('cargo_subdirector', 'Subdirector Administrativo y financiero del DMMED');

$template->setValue('nombre_supervisor', 'MARIO ALBERTO MONTOYA LÓPEZ');
$template->setValue('numero_contrato', '406-DMMED-2025');

$template->setValue('nombre_contratista', $nombre_completo); // $_POST['nombre_completo']
$template->setValue('cedula_contratista', $documento_identidad); // $_POST['documento_identidad']
$template->setValue('lugar_cedula_contratista', $ciudad_expedicion); // $_POST['ciudad_expedicion']
$template->setValue('cargo_contratista', $cargo); // $_POST['cargo']

$template->setValue('objeto_contrato', 'PRESTACIÓN DE SERVICIOS TÉCNICOS COMO AUXILIAR DE ENFERMERÍA QUE REQUIERE EL DISPENSARIO MÉDICO DE MEDELLÍN PARA LA REGIONAL No. 7 DE SANIDAD MILITAR, VIGENCIA 2025');

$template->setValue('fecha_acta_inicio', '03 de julio de 2025');
$template->setValue('fecha_suscripcion', '01 de julio de 2025');

$template->setValue('valor_letras', 'DOCE MILLONES CUATROCIENTOS SESENTA Y OCHO MIL PESOS M/CTE');
$template->setValue('valor_numerico', '$12.468.000,00');

$template->setValue('forma_pago', 'El Ministerio de Defensa Nacional - Ejército Nacional - Dirección de Sanidad Ejército - Dispensario Médico de Medellín, cancelará en seis (6) depósitos el valor total del contrato de $12.468.000,00. El valor mensual no excederá $2.078.000,00, de acuerdo al informe del supervisor y actividades desarrolladas.');

$template->setValue('plazo_contrato', 'Desde la aprobación de la garantía y el registro presupuestal, sin exceder el 31 de diciembre de 2025.');


?>