<?php 
include("../conexion/cn.php");
?> 

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registro de CEP</title>
	<link rel="stylesheet" type="text/css" href="../css/cep.css">

</head>
<body>
<h2 class="form__titulo">Registro de CEP</h2>

<form action="../bd/registrar_cep.php" method="post" class="form-register">
	<!-- Imagen izquierda -->
	<img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
	
	<!-- Imagen derecha -->
	<img src="../imagenes/logo2.png" alt="Logo" class="img-right">

	<div class="contenedor-inputs"> 
		<label for="numero"> Numero:</label>
		<input type="text" id="numero" name="numero" required><br>

		<label for="fecha"> Fecha:</label>
		<input type="date" id="fecha" name="fecha" required><br>

		<label for="valor"> valor:</label>
		<input type="text" id="valor" name="valor" required><br><br>

		<input type="submit" value="Ingresar" class="btn_enviar">
	</div>
</form>
</body>
</html>
