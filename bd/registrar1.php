<?php 
include 'cn.php';
$rol = $_POST["rol"];
$id_empleado = intval($_POST["empleado"]);
$usuario = $_POST["usuario"];
$contraseña = $_POST["contraseña"];

$insertar = "INSERT INTO tabla_usuario(rol, id_empleado, usuario, contraseña) VALUES ('$rol', '$id_empleado', '$usuario', '$contraseña')";

$resultado = mysqli_query($conexion, $insertar);

if (!$resultado) {
	echo 'Error al registrar';
}
	else {
		echo 'usuario registrado exitosamente';
	}
	mysqli_close($conexion);
 ?>