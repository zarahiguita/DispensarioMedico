<?php 
include("../conexion/cn.phpxion/cn.php");;
$usuarios = "SELECT * FROM tabla_usuario"

 ?>


<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Formulario registro de usuarios</title>
	<link rel="stylesheet" type="text/css" href="../css/login.css">
</head>
<body>
<h2 class="form__titulo"> Registros de usuarios </h2>
<form action="registrar1.php" method="post" class="form-register">
<img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
		
		<!-- Imagen derecha -->
		<img src="../imagenes/logo2.jpg" alt="Logo" class="img-right">
		<div class="contenedor-inputs"> 
			<label for= "rol">Rol:</label> <input type="text" name="rol"> <br></br>
		<label>Empleado</label>
		<select name="empleado"> 
				<?php 
			$consulta="SELECT * FROM tabla_empleados";
			$ejecutar=mysqli_query($conexion,$consulta) or die(mysqli_error($conexion));

			 ?>
          <option value="">Seleccione una opción</option>
			<?php foreach ($ejecutar as $opciones): ?>

				<option value="<?php echo $opciones['id_empleado'] ?>"><?php echo $opciones['nombre'] ?></option>

			<?php endforeach ?>
		</select></br></br>
            <label for= "usuario">Usuario:</label> <input type="text" name="usuario"><br></br>
			<label for= "contraseña">Contraseña:</label> <input type="text" name="contraseña"><br><br>
			<input type="submit" name="" value="Ingresar"class="btn_enviar"><br><br><br><br>
			<center><BUTTON> <a type="submit" id="export_data" name='generar_excel' value="Export to excel" href="http://localhost/practicas/exportar_usuarios.php">Ver base de datos en Excel </a></center>
			
		</div>
		

	</form>


</body>
</html>