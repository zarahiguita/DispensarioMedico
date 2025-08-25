<?php 
include("../conexion/cn.phpxion/cn.php");;


 ?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Formulario regisro de firmas</title>
	<link rel="stylesheet" type="text/css" href="../css/login.css">
</head>
<body>
<h2 class="form__titulo"> Registros de firmas </h2>
	<form action="registrar9.php" method="post" class="form-register">
		 <!-- Imagen izquierda -->
		 <img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
		
		<!-- Imagen derecha -->
		<img src="../imagenes/logo2.jpg" alt="Logo" class="img-right">
		<div class="contenedor-inputs"> 
		<label>usuario</label>
		<select name="usuario"> 
				<?php 
			$consulta="SELECT * FROM tabla_usuario";
			$ejecutar=mysqli_query($conexion,$consulta) or die(mysqli_error($conexion));

			 ?>
          <option value="">Seleccione una opción</option>
			<?php foreach ($ejecutar as $opciones): ?>

				<option value="<?php echo $opciones['id'] ?>"><?php echo $opciones['usuario'] ?></option>

			<?php endforeach ?>
		</select></br></br>
		<label>Numero de contrato</label>
		<select name="contratacion"> 
				<?php 
			$consulta="SELECT * FROM tabla_contratacion";
			$ejecutar=mysqli_query($conexion,$consulta) or die(mysqli_error($conexion));

			 ?>
          <option value="">Seleccione una opción</option>
			<?php foreach ($ejecutar as $opciones): ?>

				<option value="<?php echo $opciones['id'] ?>"><?php echo $opciones['id'] ?></option>

			<?php endforeach ?>
		</select></br></br>
			<label for= "firma_data">Firma:</label> <input type="text" name="firma_data"> <br></br>

			<input type="submit" name="" value="Ingresar"class="btn_enviar">
			
			
		</div>
		

	</form>


</body>
</html>