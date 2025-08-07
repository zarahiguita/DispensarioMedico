<?php 
include("../conexion/cn.php");
?> 

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registro de Rubro</title>
	<link rel="stylesheet" type="text/css" href="../css/rubro.css">
</head>
<body>
<h2 class="form__titulo">Registro de Rubro</h2>

<form action="../bd/registrar_rubro.php" method="post" class="form-register">
	<!-- Imagen izquierda -->
	<img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
	
	<!-- Imagen derecha -->
	<img src="../imagenes/logo2.png" alt="Logo" class="img-right">

	<div class="contenedor-inputs"> 
		<label for="rubro_presupuestal">Rubro presupuestal:</label>
		<input type="text" id="rubro_presupuestal" name="rubro_presupuestal" required>

		<label for="descripcion_rubro">Descripción de rubro:</label>
		<input type="text" id="descripcion_rubro" name="descripcion_rubro" required><br><br>

		<input type="submit" value="Ingresar" class="btn_enviar">
	</div>
</form>
</body>
</html>

