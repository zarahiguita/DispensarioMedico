<?php 
include("../conexion/cn.php");
?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> registro de supervisor</title>
	<link rel="stylesheet" type="text/css" href="../css/supervisor.css">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function () {
			let nombreInput = document.getElementById("nombre_supervisor");

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

<h2 class="form__titulo"> Registro de Supervisor </h2>

<form action="../bd/registrar_supervisor.php" method="post" class="form-register">
	<!-- Imagen izquierda -->
	<img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
	
	<!-- Imagen derecha -->
	<img src="../imagenes/logo2.png" alt="Logo" class="img-right">
	
	<div class="contenedor-inputs"> 
		<label for="nombre">Nombre de supervisor:</label>
		<input type="text" id="nombre_supervisor" name="nombre_supervisor" required title="Solo se permiten letras y espacios"><br><br>


		<input type="submit" value="Ingresar" class="btn_enviar">
	</div>
</form>

</body>
</html>
