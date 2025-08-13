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
