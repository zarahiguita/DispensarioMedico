<?php 
include("../conexion/cn.php");
?> 

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registro de unidad</title>
	<link rel="stylesheet" type="text/css" href="../css/ordenador.css">
</head>
<body>
<h2 class="form__titulo">Registro de Unidad</h2>

<form action="../bd/registrar_unidad.php" method="post" class="form-register">
	<!-- Imagen izquierda -->
	<img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
	
	<!-- Imagen derecha -->
	<img src="../imagenes/logo2.png" alt="Logo" class="img-right">

	<div class="contenedor-inputs"> 
		<label for="unidad"> Unidad:</label>
		<input type="text" id="unidad" name="unidad" required><br>

		<label for="nombre"> Nombre:</label>
		<input type="text" id="nombre" name="nombre" required><br>

		<label for="localidad"> Localidad:</label>
		<input type="text" id="localidad" name="localidad" required><br><br>

		<input type="submit" value="Ingresar" class="btn_enviar">
	</div>
</form>
</body>
</html>
