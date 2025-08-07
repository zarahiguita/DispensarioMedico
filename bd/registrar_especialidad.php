<?php 
include("../conexion/cn.php");
$profesion = $_POST["profesion"];

date_default_timezone_set('America/Bogota');
$fecha_creacion = date('Y-m-d H:i:s');
$fecha_actualizacion = date('Y-m-d H:i:s');


$insertar = "INSERT INTO profesiones(profesion, fecha_creacion, fecha_actualizacion) 
VALUES ('$profesion', '$fecha_creacion', '$fecha_actualizacion')";

$resultado = mysqli_query($conexion, $insertar);

 ?>

 <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
      /* Puedes personalizar aún más con clases */
      .swal2-popup {
        font-family: 'Segoe UI', sans-serif;
        font-size: 16px;
        border-radius: 12px !important;
      }
      .swal2-title {
        font-weight: bold;
        font-size: 22px;
      }
    </style>
</head>
<body>
<script>
<?php if (!$resultado): ?>
    Swal.fire({
        icon: 'error',
        title: '¡Error!',
        text: 'Ocurrió un error al registrar el asesor jurídico.',
        confirmButtonText: 'Volver',
        confirmButtonColor: '#d33',
        background: '#fff0f0',
        color: '#990000',
        backdrop: `
          rgba(255,0,0,0.1)
          url("https://i.imgur.com/A041ZcQ.gif")
          left top
          no-repeat
        `
    }).then(() => {
        window.history.back(); // Regresar al formulario
    });
<?php else: ?>
    Swal.fire({
        icon: 'success',
        title: '¡Registro exitoso!',
        html: '<b>La especialidad fue creada correctamente.</b>',
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#AF1415',
        background: '#f0f9ff',
        color: '#1e2a38',
        backdrop: `
          #AF1415
        `
    }).then(() => {
        window.location.href = '../vista/supervisor.php';
    });
<?php endif; ?>
</script>
</body>
</html>
<?php
mysqli_close($conexion);
?>
