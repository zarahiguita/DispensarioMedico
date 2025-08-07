<?php 
include("../conexion/cn.php");
?> 

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registro de Asesor juridico</title>
	<link rel="stylesheet" type="text/css" href="../css/asesor.css">

</head>
<body>
<h2 class="form__titulo">Registro de Asesor juridico</h2>

<form action="../bd/registrar_asesor.php" method="post" class="form-register">
	<!-- Imagen izquierda -->
	<img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
	
	<!-- Imagen derecha -->
	<img src="../imagenes/logo2.png" alt="Logo" class="img-right">

	<div class="contenedor-inputs"> 
		<label for="nombre"> Nombre:</label>
		<input type="text" id="nombre" name="nombre" required><br>

		<label for="cedula"> Cédula:</label>
		<input type="text" id="cedula" name="cedula" required><br><br>

		<input type="submit" value="Ingresar" class="btn_enviar">
	</div>
</form>
</body>
</html>
