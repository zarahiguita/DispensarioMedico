<?php
// Seguridad básica: solo POST con archivo
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['archivo'])) {
    http_response_code(400);
    exit('No se ha recibido ningún archivo.');
}

$cedula = $_POST['cedula'] ?? '';
$tipo   = $_POST['tipo_acta'] ?? '';

// Mapea la selección a la carpeta correcta (FIRMADOS)
switch ($tipo) {
    case 'prestacion':
        $directorioDestino = __DIR__ . '/contratos_acta_inicio_prestacion_firmado/';
        $carpetaUrl        = 'contratos_acta_inicio_prestacion_firmado/';
        break;
    case 'contrato':
        $directorioDestino = __DIR__ . '/contratos_acta_inicio_contrato_firmado/';
        $carpetaUrl        = 'contratos_acta_inicio_contrato_firmado/';
        break;
    default:
        http_response_code(400);
        exit('Tipo de acta inválido.');
}

// Crea la carpeta si no existe
if (!is_dir($directorioDestino)) {
    @mkdir($directorioDestino, 0775, true);
}

// Valida extensión
$archivo = $_FILES['archivo'];
if ($archivo['error'] !== UPLOAD_ERR_OK) {
    exit('Error al subir el archivo. Código: ' . $archivo['error']);
}

$nombreOriginal = basename($archivo['name']);
$ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
$permitidas = ['pdf', 'doc', 'docx'];
if (!in_array($ext, $permitidas)) {
    exit('Tipo de archivo no permitido. Solo PDF, DOC o DOCX.');
}

// Evita colisiones: si existe, agrega timestamp
$destino = $directorioDestino . $nombreOriginal;
if (file_exists($destino)) {
    $sinExt = pathinfo($nombreOriginal, PATHINFO_FILENAME);
    $destino = $directorioDestino . $sinExt . '_' . date('Ymd_His') . '.' . $ext;
}

if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
    exit('Error: No se pudo mover el archivo.');
}

// Redirige de vuelta a subir_acta.php para mostrar el SweetAlert
$nombreCodificado = urlencode(basename($destino));
$cedulaCodificada = urlencode($cedula);
header("Location: subir_acta.php?cedula={$cedulaCodificada}&exito=1&archivo={$nombreCodificado}");
exit;
