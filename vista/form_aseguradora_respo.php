<?php 
include("../conexion/cn.php");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? '';
    $nit = $_POST["nit"] ?? '';

    $insertar = "INSERT INTO aseguradora_respo(nombre, nit) VALUES ('$nombre', '$nit')";
    $resultado = mysqli_query($conexion, $insertar);
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Resultado</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
    <script>
    <?php if (!$resultado): ?>
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: 'No se pudo guardar la aseguradora de responsabilidad.',
            confirmButtonText: 'Volver'
        }).then(() => {
            window.history.back();
        });
    <?php else: ?>
        Swal.fire({
            icon: 'success',
            title: '¡Registro exitoso!',
            text: 'La aseguradora fue guardada correctamente.',
            confirmButtonText: 'Volver'
        }).then(() => {
            window.location.href = 'form_aseguradora_respo.php';
        });
    <?php endif; ?>
    </script>
    </body>
    </html>
    <?php
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>Registro Aseguradora de Responsabilidad</title>
	<link rel="stylesheet" type="text/css" href="../css/ordenador.css">
</head>
<body>
<h2 class="form__titulo">Registro de Aseguradora de Responsabilidad</h2>

<form method="post" class="form-register">
	<img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
	<img src="../imagenes/logo2.png" alt="Logo" class="img-right">

	<div class="contenedor-inputs"> 
		<label for="nombre">Nombre:</label>
		<input type="text" id="nombre" name="nombre" required>

		<label for="nit">NIT:</label>
		<input type="text" id="nit" name="nit" required><br><br>

		<input type="submit" value="Ingresar" class="btn_enviar">
	</div>
</form>
</body>
</html>
