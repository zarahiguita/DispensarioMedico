<?php
$host     = "localhost";
$user     = "root";
$pass     = "";
$db       = "gestion_contratacion";
$conexion = new mysqli($host, $user, $pass, $db);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$cedula = isset($_GET['cedula']) ? $conexion->real_escape_string($_GET['cedula']) : '';

if ($cedula === '') {
    die("Cédula no proporcionada.");
}

// Obtener datos del empleado
$sql = "SELECT * FROM empleados WHERE documento_identidad = '$cedula'";
$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $empleado = $resultado->fetch_assoc();

    // Fechas actuales
    $fecha_inicio = new DateTime($empleado['fecha_creacion']);
    $fecha_fin_actual = new DateTime($empleado['fecha_actualizacion']);
    $fecha_fin_nueva = $fecha_fin_actual->modify('+1 year');

    // Actualizar la fecha de finalización en la base de datos
    $nueva_fecha = $fecha_fin_nueva->format('Y-m-d');
    $update_sql = "UPDATE empleados SET fecha_actualizacion = '$nueva_fecha' WHERE documento_identidad = '$cedula'";
    $conexion->query($update_sql);

    // Generar nombre del nuevo contrato
    $nombre_archivo_original = "contratos/{$cedula}.docx";
    $nombre_archivo_renovado = "contratos/{$cedula}_renovado.docx";

    // Copiar el contrato existente a una nueva versión
    if (file_exists($nombre_archivo_original)) {
        if (!copy($nombre_archivo_original, $nombre_archivo_renovado)) {
            die("No se pudo crear la copia del contrato.");
        }

        // Redirigir al archivo renovado
        header("Location: $nombre_archivo_renovado");
        exit;
    } else {
        echo "El contrato original no existe.";
    }
} else {
    echo "Empleado no encontrado.";
}

$conexion->close();
?>
