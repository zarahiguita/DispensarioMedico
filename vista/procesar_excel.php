<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . '/../servicio/vendor/autoload.php'; 
require '../conexion/cn.php'; 

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

$directorio_actual = "../BD_excel/BD_actualizada/";
$directorio_historial = "../BD_excel/historial/";

if (!is_dir($directorio_actual) || !is_dir($directorio_historial)) {
    die("Error: Las carpetas necesarias no existen.");
}

if (isset($_POST['submit'])) {
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
        $nombreArchivo = $_FILES['archivo']['name'];
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);

        if (in_array(strtolower($extension), ['xls', 'xlsx'])) {
            $rutaDestino = $directorio_actual . $nombreArchivo;

            // Mover archivo anterior al historial si es distinto
            $archivos_actuales = glob($directorio_actual . "*.{xls,xlsx}", GLOB_BRACE);
            foreach ($archivos_actuales as $archivo_previo) {
                if (basename($archivo_previo) !== $nombreArchivo) {
                    $nombre_base = pathinfo($archivo_previo, PATHINFO_FILENAME);
                    $ext = pathinfo($archivo_previo, PATHINFO_EXTENSION);
                    $fecha = date("Y-m-d_H-i-s");
                    $nuevo_nombre_historial = $nombre_base . "_" . $fecha . "." . $ext;
                    rename($archivo_previo, $directorio_historial . $nuevo_nombre_historial);
                }
            }

            if (move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaDestino)) {
                $spreadsheet = IOFactory::load($rutaDestino);
                $sheet = $spreadsheet->getActiveSheet();

                $highestRow = $sheet->getHighestDataRow();
                $highestColumn = $sheet->getHighestDataColumn();
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

                $datos = [];
                for ($row = 1; $row <= $highestRow; $row++) {
                    $fila = [];
                    for ($col = 1; $col <= $highestColumnIndex; $col++) {
                        $cellCoord = Coordinate::stringFromColumnIndex($col) . $row;
                        $cell = $sheet->getCell($cellCoord);
                        $value = $cell->getCalculatedValue();  // <-- CAMBIO CLAVE

                        $format = $cell->getStyle()->getNumberFormat()->getFormatCode();

                        // Convertir fechas Excel
                        if (Date::isDateTime($cell) && is_numeric($value)) {
                            try {
                                $value = Date::excelToDateTimeObject($value)->format('Y-m-d');
                            } catch (Exception $e) {
                                $value = null;
                            }

                        // Convertir texto tipo "10 de enero de 2025"
                        } elseif (is_string($value) && preg_match('/\d{1,2} de [a-z]+ de \d{4}/i', $value)) {
                            $meses = [
                                'enero' => '01', 'febrero' => '02', 'marzo' => '03',
                                'abril' => '04', 'mayo' => '05', 'junio' => '06',
                                'julio' => '07', 'agosto' => '08', 'septiembre' => '09',
                                'octubre' => '10', 'noviembre' => '11', 'diciembre' => '12'
                            ];
                            if (preg_match('/(\d{1,2}) de ([a-z]+) de (\d{4})/i', strtolower($value), $match)) {
                                $dia = str_pad($match[1], 2, '0', STR_PAD_LEFT);
                                $mes = $meses[$match[2]] ?? '01';
                                $anio = $match[3];
                                $value = "$anio-$mes-$dia";
                            }

                        // Limpiar valores monetarios
                        } elseif (is_string($value) && preg_match('/^\$?[0-9\.,]+$/', $value)) {
                            $value = str_replace(['$', ',', '.'], '', $value);

                        // Si es numérico y con formato de moneda
                        } elseif (is_numeric($value) && preg_match('/0\.00|#,##0\.00|#,##0_/', $format)) {
                            $value = (float)$value;
                        }

                        $fila[] = trim((string)$value);
                    }

                    $fila = array_pad($fila, $highestColumnIndex, null);
                    $datos[] = $fila;
                }

                // Eliminar registros existentes
                mysqli_query($conexion, "DELETE FROM contrato_detallado");

                $i = 0;
                foreach ($datos as $fila) {
                    if ($i++ === 0) continue; // Saltar encabezado

                    // Mapear campos
                    list(
                        $no_registro, $unidad, $no_proceso, $no_contrato, $objeto, $cdp, $fecha_cdp, $rp, $fecha_rp,
                        $valor_pago_mensual, $valor_total_contrato, $fecha_suscripcion, $fecha_inicio_contrato,
                        $fecha_aprobacion_poliza, $fecha_terminacion_contrato, $meses_contratados, $supervisor,
                        $nombre_completo_contratista, $documento_identidad, $lugar_expedicion_documento,
                        $fecha_expedicion_documento, $fecha_nacimiento, $direccion_residencia, $telefono,
                        $correo_electronico, $profesion, $cod_verificacion_rut, $area_desempeno, $unspc,
                        $tipo_cuenta_bancaria, $numero_cuenta_bancaria, $entidad_bancaria, $entidad_salud,
                        $entidad_pension, $entidad_arl, $modalidad_contratacion, $tipo_contratacion, $rubro_presupuestal,
                        $descripcion_rubro, $no_poliza_cumplimiento, $fecha_inicio_poliza_cumplimiento,
                        $fecha_terminacion_poliza_cumplimiento, $no_poliza_rcp, $fecha_inicio_poliza_rcp,
                        $fecha_terminacion_poliza_rcp, $fecha_aprobacion_poliza2, $fecha_acta_inicio
                    ) = array_map(fn($v) => mysqli_real_escape_string($conexion, $v), array_pad($fila, 47, null));

                    $query = "INSERT INTO contrato_detallado (
                        no_registro, unidad, no_proceso, no_contrato, objeto, cdp, fecha_cdp, rp, fecha_rp,
                        valor_pago_mensual, valor_total_contrato, fecha_suscripcion, fecha_inicio_contrato,
                        fecha_aprobacion_poliza, fecha_terminacion_contrato, meses_contratados, supervisor,
                        nombre_completo_contratista, documento_identidad, lugar_expedicion_documento,
                        fecha_expedicion_documento, fecha_nacimiento, direccion_residencia, telefono,
                        correo_electronico, profesion, cod_verificacion_rut, area_desempeno, unspc,
                        tipo_cuenta_bancaria, numero_cuenta_bancaria, entidad_bancaria, entidad_salud,
                        entidad_pension, entidad_arl, modalidad_contratacion, tipo_contratacion, rubro_presupuestal,
                        descripcion_rubro, no_poliza_cumplimiento, fecha_inicio_poliza_cumplimiento,
                        fecha_terminacion_poliza_cumplimiento, no_poliza_rcp, fecha_inicio_poliza_rcp,
                        fecha_terminacion_poliza_rcp, fecha_aprobacion_poliza2, fecha_acta_inicio
                    ) VALUES (
                        '$no_registro', '$unidad', '$no_proceso', '$no_contrato', '$objeto', '$cdp', '$fecha_cdp',
                        '$rp', '$fecha_rp', '$valor_pago_mensual', '$valor_total_contrato', '$fecha_suscripcion',
                        '$fecha_inicio_contrato', '$fecha_aprobacion_poliza', '$fecha_terminacion_contrato',
                        '$meses_contratados', '$supervisor', '$nombre_completo_contratista', '$documento_identidad',
                        '$lugar_expedicion_documento', '$fecha_expedicion_documento', '$fecha_nacimiento', '$direccion_residencia',
                        '$telefono', '$correo_electronico', '$profesion', '$cod_verificacion_rut', '$area_desempeno', '$unspc',
                        '$tipo_cuenta_bancaria', '$numero_cuenta_bancaria', '$entidad_bancaria', '$entidad_salud',
                        '$entidad_pension', '$entidad_arl', '$modalidad_contratacion', '$tipo_contratacion', '$rubro_presupuestal',
                        '$descripcion_rubro', '$no_poliza_cumplimiento', '$fecha_inicio_poliza_cumplimiento',
                        '$fecha_terminacion_poliza_cumplimiento', '$no_poliza_rcp', '$fecha_inicio_poliza_rcp',
                        '$fecha_terminacion_poliza_rcp', '$fecha_aprobacion_poliza2', '$fecha_acta_inicio'
                    )";

                    if (!mysqli_query($conexion, $query)) {
                        echo "Error en fila $i: " . mysqli_error($conexion) . "<br>";
                    }
                }

                header("Location: excel.php?exito=1");
                exit();
            } else {
                echo "<script>alert('Error al mover el archivo.');</script>";
            }
        } else {
            echo "<script>alert('Solo se permiten archivos Excel (.xls, .xlsx).');</script>";
        }
    } else {
        echo "<script>alert('No se seleccionó ningún archivo o hubo un error.');</script>";
    }
}
?>
