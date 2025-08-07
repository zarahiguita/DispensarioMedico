<?php
include('../bd/cn.php');  // Ajusta la ruta según corresponda

header('Content-Type: application/json');

if (!isset($_GET['documento'])) {
    echo json_encode(['existe' => false]);
    exit;
}

$documento = $conexion->real_escape_string($_GET['documento']);
$query = "SELECT COUNT(*) as total FROM contrato_detallado WHERE documento_identidad = '$documento'";
$result = $conexion->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    echo json_encode(['existe' => ($row['total'] > 0)]);
} else {
    echo json_encode(['existe' => false]);
}
?>
