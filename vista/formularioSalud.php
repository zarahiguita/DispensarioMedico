<?php 
include("../conexion/cn.phpxion/cn.php");;


 ?>


<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Regisro de Salud</title>
	<link rel="stylesheet" type="text/css" href="../css/cuenta_bancaria.css">
</head>
<body>
<h2 class="form__titlo"> Registro de salud </h2>
	<form action="registrar3.php" method="post" class="form-register">
		

		  <!-- Imagen izquierda -->
		  <img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
		
		<!-- Imagen derecha -->
		<img src="../imagenes/logo2.jpg" alt="Logo" class="img-right">

		<div class="contenedor-inputs">
			<label for= "entidad_salud">Entidad de salud:</label> <input type="text" name="entidad_salud"> <br>
            <label for= "entidad_pension">Entidad de pensión:</label> <input type="text" name="entidad_pension"><br>
            <label for= "entidad_arl"> Entidad ARL:</label> <input type="text" name="entidad_arl"><br>
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
			<input type="submit" name="" value="Ingresar"class="btn_enviar">
			
			
		</div>
		

	</form>


</body>
</html>