<?php
use PhpOffice\PhpWord\TemplateProcessor;
require __DIR__ . '/../vendor/autoload.php';

// Formato largo español
function fechaLargaEs($iso, $locale='es_CO', $tz='America/Bogota'){
  if(!$iso) return '';
  if(class_exists('IntlDateFormatter')){
    $fmt = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE, $tz, IntlDateFormatter::GREGORIAN);
    $dt  = new DateTime($iso, new DateTimeZone($tz));
    return ucfirst(mb_strtolower($fmt->format($dt), 'UTF-8'));
  }
  return date('d \d\e F \d\e Y', strtotime($iso));
}

// Recoger todos los campos definidos en la plantilla
$fields = [
  'lugar','fecha_oficio','grado_supervisor_abrev','nombre_supervisor','cargo_supervisor',
  'numero_ao','objeto_contrato','contratista_razon_social','contratista_nit','rep_legal_nombre',
  'rep_legal_cc','rep_legal_lugar','contratista_direccion','contratista_telefonos','contratista_correos',
  'valor_contrato_letras','valor_contrato_num','fecha_terminacion','anexos','ordenador_nombre',
  'ordenador_cargo','supervisor_cc','supervisor_correo','supervisor_celular','vb_nombre','vb_cargo'
];

$data = [];
foreach($fields as $k){ $data[$k] = $_POST[$k] ?? ''; }

// Normalizar fechas
$data['fecha_oficio']      = fechaLargaEs($data['fecha_oficio']);
$data['fecha_terminacion'] = fechaLargaEs($data['fecha_terminacion']);

// Reforzar MAYÚSCULAS donde corresponde
foreach (['objeto_contrato','contratista_razon_social','rep_legal_nombre','valor_contrato_letras','ordenador_nombre','ordenador_cargo','vb_nombre','vb_cargo'] as $u) {
  $data[$u] = mb_strtoupper($data[$u], 'UTF-8');
}

// Cargar plantilla

$template = new TemplateProcessor('plantilla_enterado_supervisor.docx');

// Setear valores
foreach ($data as $k => $v) { $template->setValue($k, $v); }

// Guardar y descargar
$slug   = preg_replace('/[^A-Za-z0-9_-]+/','_', $data['numero_ao'] ?: 'AO');
$nombre = 'ENTERADO_SUPERVISOR_' . $slug . '.docx';
$dest   = __DIR__ . '/salidas';
if (!is_dir($dest)) @mkdir($dest, 0775, true);
$ruta = $dest . '/' . $nombre;

$template->saveAs($ruta);

header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="'.$nombre.'"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
readfile($ruta);
exit;