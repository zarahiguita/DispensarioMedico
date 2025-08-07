<?php 
include("../conexion/cn.php");
?> 

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registro del ordenador del gasto</title>
	<link rel="stylesheet" type="text/css" href="../css/ordenador.css">
</head>
<body>
<h2 class="form__titulo">Registro de Ordenador del gasto</h2>

<form action="../bd/registrar_ordenador.php" method="post" class="form-register">
	<!-- Imagen izquierda -->
	<img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
	
	<!-- Imagen derecha -->
	<img src="../imagenes/logo2.png" alt="Logo" class="img-right">

	<div class="contenedor-inputs"> 
		<label for="grado"> Grado:</label>
		<input type="text" id="grado" name="grado" required><br>

		<label for="nombre"> Nombre:</label>
		<input type="text" id="nombre" name="nombre" required><br>

		<label for="cedula"> Cédula:</label>
		<input type="text" id="cedula" name="cedula" required><br>

		<label for="lugar"> Lugar de expedición de la cédula:</label>
		<input type="text" id="lugar" name="lugar" required><br><br>

		<input type="submit" value="Ingresar" class="btn_enviar">
	</div>
</form>
</body>
</html>
