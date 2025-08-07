<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../servicio/vendor/autoload.php'; 
require '../conexion/cn.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

setcookie("descarga_completa", "1", time() + 60, "/");

$conexion = new mysqli("localhost", "root", "", "gestion_contratacion");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$cedula        = $_POST['cedula'] ?? '';
$fecha_inicio  = $_POST['fecha_inicio'] ?? '';
$fecha_fin     = $_POST['fecha_fin'] ?? '';
$valor_mensual = $_POST['valor_mensual'] ?? '';
$valor_total   = $_POST['valor_total'] ?? '';
$meses         = $_POST['meses'] ?? '';

if (empty($cedula)) {
    die("❌ Cédula no proporcionada.");
}

// Actualizar contrato
$sql = "
    UPDATE contrato_detallado SET
        fecha_inicio_contrato = '$fecha_inicio',
        fecha_terminacion_contrato = '$fecha_fin',
        meses_contratados = '$meses',
        valor_total_contrato = '$valor_total',
        valor_pago_mensual = '$valor_mensual'
    WHERE documento_identidad = '$cedula'
";

if ($conexion->query($sql) === TRUE) {

    $query = "SELECT * FROM contrato_detallado WHERE documento_identidad = '$cedula'";
    $result = $conexion->query($query);

    if ($result && $result->num_rows > 0) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $campos = array_keys($result->fetch_assoc());
        $result->data_seek(0);

        // Encabezados con estilo
        foreach ($campos as $i => $campo) {
            $colLetra = Coordinate::stringFromColumnIndex($i + 1);
            $cell = $colLetra . '1';

            $sheet->setCellValue($cell, $campo);

            // Estilo del encabezado
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('4B5320'); // Verde militar
            $sheet->getStyle($cell)->getFont()->setBold(true)->getColor()->setARGB(Color::COLOR_WHITE);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cell)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($cell)->getAlignment()->setWrapText(true);

            // Ancho fijo (puedes ajustar el valor)
            $sheet->getColumnDimension($colLetra)->setWidth(25);
        }

        // Datos
        $fila = 2;
        while ($datos = $result->fetch_assoc()) {
            foreach ($campos as $i => $campo) {
                $colLetra = Coordinate::stringFromColumnIndex($i + 1);
                $cell = "{$colLetra}{$fila}";
                $sheet->setCellValue($cell, $datos[$campo]);

                // Ajuste de texto dentro de la celda
                $sheet->getStyle($cell)->getAlignment()->setWrapText(true);
                $sheet->getStyle($cell)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            }
            // Dejar que la fila crezca automáticamente
            $sheet->getRowDimension($fila)->setRowHeight(-1);
            $fila++;
        }

        // Altura automática también para el encabezado
        $sheet->getRowDimension(1)->setRowHeight(-1);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="contrato_' . $cedula . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    } else {
        echo "❌ No se encontraron registros para generar el Excel.";
    }

} else {
    echo "❌ Error actualizando el contrato: " . $conexion->error;
}

$conexion->close();
?>

