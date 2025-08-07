<?php 
include 'cn.php';
$entidad_salud = $_POST["entidad_salud"];
$entidad_pension = $_POST["entidad_pension"];
$entidad_arl = $_POST["entidad_arl"];
$id_empleado = intval($_POST["empleado"]);

$insertar = "INSERT INTO tabla_afiliacion(entidad_salud, entidad_pension, entidad_arl, id_empleado) VALUES ('$entidad_salud', '$entidad_pension', '$entidad_arl', '$id_empleado')";

$resultado = mysqli_query($conexion, $insertar);

if (!$resultado) {
	echo 'Error al registrar';
}
	else {
		echo 'usuario registrado exitosamente';
	}
	mysqli_close($conexion);
 ?>