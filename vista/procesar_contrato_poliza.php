<?php
// Ruta donde se guardarán los contratos firmados
$directorioDestino = __DIR__ . '/contratos_poliza_firmados/';

// Verificamos que el formulario haya sido enviado y el archivo esté presente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
    $archivo = $_FILES['archivo'];

    // Validamos si hubo error en la subida
    if ($archivo['error'] === UPLOAD_ERR_OK) {
        // Obtenemos el nombre original del archivo
        $nombreArchivo = basename($archivo['name']);

        // Ruta completa del archivo destino
        $rutaCompleta = $directorioDestino . $nombreArchivo;

        // Movemos el archivo desde su ubicación temporal al destino final
        if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {

            $cedula = $_POST['cedula'] ?? $_GET['cedula'] ?? '';
            $nombreCodificado = urlencode($nombreArchivo);
            $cedulaCodificada = urlencode($cedula);
 
         $nombreCodificado = urlencode($nombreArchivo);
         header("Location: subir_poliza.php?cedula=$cedulaCodificada&exito=1&archivo=$nombreCodificado");
exit;
}
 else {
            echo "Error: No se pudo mover el archivo.";
        }
    } else {
        echo "Error al subir el archivo. Código de error: " . $archivo['error'];
    }
} else {
    echo "No se ha recibido ningún archivo.";
}
