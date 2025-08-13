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
	<link rel="stylesheet" type="text/css" href="../css/general.css">

</head>
<body>
 <a href="formulario_busqueda.php" class="boton-buscar">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
      <path d="M10 2a8 8 0 105.293 14.707l5 5a1 1 0 001.414-1.414l-5-5A8 8 0 0010 2zm0 2a6 6 0 110 12A6 6 0 0110 4z"/>
    </svg>
    Buscar empleado
  </a>
  <a href="../menu1.php" class="boton-volver">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
      <path d="M15 18l-6-6 6-6" stroke="white" stroke-width="2" fill="none"/>
    </svg>
    Volver
  </a>

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
