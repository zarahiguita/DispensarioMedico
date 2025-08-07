<?php 
include 'cn.php';
$tipo = $_POST["tipo"];
$modalidad = $_POST["modalidad"];
$fecha_inicio = $_POST["fecha_inicio"];
$fecha_fin = $_POST["fecha_fin"];
$duracion = $_POST["duracion"];
$valor_total = $_POST["valor_total"];
$valor_pago_mensual = $_POST["valor_pago_mensual"];
$fecha_suscripcion = $_POST["fecha_suscripcion"];
$fecha_cdp = $_POST["fecha_cdp"];
$cdp = $_POST["cdp"];
$fecha_rp = $_POST["fecha_rp"];
$unspc = $_POST["unspc"];
$id_empleado = intval($_POST["empleado"]);
$id_objecto = intval($_POST["objecto"]);

$insertar = "INSERT INTO tabla_contratacion(tipo, modalidad, fecha_inicio, fecha_fin, duracion, valor_total, valor_pago_mensual, fecha_suscripcion, fecha_cdp, cdp, fecha_rp, unspc, id_empleado, id_objecto) 
VALUES ('$tipo', '$modalidad', '$fecha_inicio', '$fecha_fin', '$duracion', '$valor_total', '$valor_pago_mensual', '$fecha_suscripcion', '$fecha_cdp', '$cdp', '$fecha_rp', '$unspc', '$id_empleado', '$id_objecto')";

$resultado = mysqli_query($conexion, $insertar);

if (!$resultado) {
	echo 'Error al registrar';
}
	else {
		echo 'usuario registrado exitosamente';
	}
	mysqli_close($conexion);
 ?>