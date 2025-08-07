<?php 
include("../conexion/cn.php");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? '';
    $insertar = "INSERT INTO objeto(nombre) VALUES ('$nombre')";
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
            text: 'No se pudo guardar el objeto.',
            confirmButtonText: 'Volver'
        }).then(() => {
            window.history.back();
        });
    <?php else: ?>
        Swal.fire({
            icon: 'success',
            title: '¡Registro exitoso!',
            text: 'El objeto fue guardado correctamente.',
            confirmButtonText: 'Volver'
        }).then(() => {
            window.location.href = 'form_objeto.php';
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
	<title>Registro de Objeto</title>
	<link rel="stylesheet" type="text/css" href="../css/ordenador.css">
</head>
<body>
<h2 class="form__titulo">Registro de Objeto</h2>

<form method="post" class="form-register">
	<img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
	<img src="../imagenes/logo2.png" alt="Logo" class="img-right">

	<div class="contenedor-inputs"> 
		<label for="nombre">Nombre del objeto:</label>
		<input type="text" id="nombre" name="nombre" required><br><br>

		<input type="submit" value="Ingresar" class="btn_enviar">
	</div>
</form>
</body>
</html>
