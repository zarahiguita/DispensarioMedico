<?php 
include 'cn.php';
$id_usuario = intval($_POST["usuario"]);
$id_contratacion = intval($_POST["contratacion"]);
$firma_data = $_POST["firma_data"];

$insertar = "INSERT INTO tabla_firma(id_usuario, id_contratacion, firma_data) VALUES ('$id_usuario', '$id_contratacion', '$firma_data')";

$resultado = mysqli_query($conexion, $insertar);

if (!$resultado) {
	echo 'Error al registrar';
}
	else {
		echo 'usuario registrado exitosamente';
	}
	mysqli_close($conexion);
 ?>