<?php 
include("../conexion/cn.php");
?> 

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registro de lugar de ejecución</title>
	<link rel="stylesheet" type="text/css" href="../css/ejecucion.css">
</head>
<body>
<h2 class="form__titulo">Registro de lugar de ejecución</h2>

<form action="../bd/registrar_ejecucion.php" method="post" class="form-register">
	<!-- Imagen izquierda -->
	<img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
	
	<!-- Imagen derecha -->
	<img src="../imagenes/logo2.png" alt="Logo" class="img-right">

	<div class="contenedor-inputs"> 
		<label for="lugar_ejecucion"> Lugar de ejecución:</label>
		<input type="text" id="lugar_ejecucion" name="lugar_ejecucion" required><br>

		<label for="direccion_ejecucion">Direccion de ejecución:</label>
		<input type="text" id="direccion_ejecucion" name="direccion_ejecucion" required><br><br>

		<input type="submit" value="Ingresar" class="btn_enviar">
	</div>
</form>
</body>
</html>

