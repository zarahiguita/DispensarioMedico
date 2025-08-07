<?php 
include 'cn.php';
$n_poliza_cumplimiento = $_POST["n_poliza_cumplimiento"];
$fecha_inicio = $_POST["fecha_inicio"];
$fecha_fin = $_POST["fecha_fin"];
$n_poliza_rcp = $_POST["n_poliza_rcp"];
$fecha_terminacion_poliza_RCP = $_POST["fecha_terminacion_poliza_RCP"];
$fecha_aprobacion = $_POST["fecha_aprobacion"];
$id_empleado = intval($_POST["empleado"]);

$insertar = "INSERT INTO tabla_poliza(n_poliza_cumplimiento, fecha_inicio, fecha_fin, n_poliza_rcp, fecha_terminacion_poliza_RCP, fecha_aprobacion, id_empleado) VALUES ('$n_poliza_cumplimiento', '$fecha_inicio', '$fecha_fin', '$n_poliza_rcp', '$fecha_terminacion_poliza_RCP', '$fecha_aprobacion', '$id_empleado')";

$resultado = mysqli_query($conexion, $insertar);

if (!$resultado) {
	echo 'Error al registrar';
}
	else {
		echo 'usuario registrado exitosamente';
	}
	mysqli_close($conexion);
 ?>