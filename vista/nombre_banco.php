<?php 
include("../conexion/cn.php");
?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registro de banco</title>
	<link rel="stylesheet" type="text/css" href="../css/nombre_banco.css">
	<script>
		document.addEventListener("DOMContentLoaded", function () {
			let nombreInput = document.getElementById("nombre");

			// Función para validar el campo de nombre
			function validarNombre() {
				let regex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/; // Solo letras y espacios
				if (!regex.test(nombreInput.value)) {
					nombreInput.setCustomValidity("El nombre solo puede contener letras y espacios.");
				} else {
					nombreInput.setCustomValidity("");
				}
			}

			// Ejecutar validación cuando el usuario escriba en el campo
			nombreInput.addEventListener("input", validarNombre);
		});
	</script>
</head>
<body>

<h2 class="form__titulo"> Registro de banco </h2>

<form action="../bd/registrar_nombre_banco.php" method="post" class="form-register">
	<!-- Imagen izquierda -->
	<img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
	
	<!-- Imagen derecha -->
	<img src="../imagenes/logo2.png" alt="Logo" class="img-right">
	
	<div class="contenedor-inputs"> 
		<label for="nombre">Nombre del banco:</label>
		<input type="text" id="nombre" name="nombre" required title="Solo se permiten letras y espacios"><br><br>

		<input type="submit" value="Ingresar" class="btn_enviar">
	</div>
</form>

</body>
</html>
