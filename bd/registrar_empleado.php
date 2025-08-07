<?php
include("cn.php");

// Captura y sanitiza los datos
function limpiar($valor) {
    return htmlspecialchars(trim($valor));
}

// DATOS SALUD
$entidad_salud = limpiar($_POST['entidad_salud']);
$entidad_pension = limpiar($_POST['entidad_pension']);
$entidad_arl = limpiar($_POST['entidad_arl']);

// INSERTAR SALUD
$sql_salud = "INSERT INTO salud (entidad_salud, entidad_pension, entidad_arl, fecha_creacion, fecha_actualizacion)
              VALUES (?, ?, ?, NOW(), NOW())";
$stmt_salud = $conexion->prepare($sql_salud);
$stmt_salud->bind_param("sss", $entidad_salud, $entidad_pension, $entidad_arl);
$stmt_salud->execute();
$id_salud = $stmt_salud->insert_id;
$stmt_salud->close();

// DATOS PÓLIZA
$no_poliza_cumplimiento = limpiar($_POST['no_poliza_cumplimiento']);
$fecha_inicio_poliza_cumplimiento = $_POST['fecha_inicio_poliza_cumplimiento'];
$fecha_fin_poliza_cumplimiento = $_POST['fecha_fin_poliza_cumplimiento'];
$no_poliza_rcp = limpiar($_POST['no_poliza_rcp']);
$fecha_inicio_rcp = $_POST['fecha_inicio_rcp'];
$fecha_fin_rcp = $_POST['fecha_fin_rcp'];
$fecha_aprobacion_polizas = $_POST['fecha_aprobacion_polizas'];

// INSERTAR POLIZA
$sql_poliza = "INSERT INTO poliza (no_poliza_cumplimiento, fecha_inicio_cumplimiento, fecha_terminacion_cumplimiento, 
  no_poliza_rcp, fecha_inicio_rcp, fecha_terminacion_rcp, fecha_aprobacion, fecha_creacion, fecha_actualizacion)
  VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

$stmt_poliza = $conexion->prepare($sql_poliza);
$stmt_poliza->bind_param("sssssss", $no_poliza_cumplimiento, $fecha_inicio_poliza_cumplimiento, $fecha_fin_poliza_cumplimiento,
                         $no_poliza_rcp, $fecha_inicio_rcp, $fecha_fin_rcp, $fecha_aprobacion_polizas);
$stmt_poliza->execute();
$id_poliza = $stmt_poliza->insert_id;
$stmt_poliza->close();

// DATOS EMPLEADO
$n_registro = limpiar($_POST["n_registro"]);
$n_proceso = limpiar($_POST["n_proceso"]);
$nombre = limpiar($_POST["nombre"]);
$unidad = limpiar($_POST["unidad"]);
$n_cedula = limpiar($_POST["n_cedula"]);
$fecha_expedicion_documento = $_POST["fecha_expedicion_documento"];
$lugar_expedicion_documento = limpiar($_POST["lugar_expedicion_documento"]);
$fecha_nacimiento = $_POST["fecha_nacimiento"];
$direccion = limpiar($_POST["direccion"]);
$telefono = limpiar($_POST["telefono"]);
$correo = limpiar($_POST["Correo"]);
$cod_verificacion_rut = limpiar($_POST["cod_verificacion_rut"]);
$area_desempenada = limpiar($_POST["area_desempenada"]);
$id_supervisor = intval($_POST["supervisor_id"]);

// INSERTAR EMPLEADO
$sql_empleado = "INSERT INTO empleados 
(id_supervisor, id_salud, no_proceso, nombre_contratista, documento_identidad, unidad, 
 lugar_expedicion_documento, fecha_expedicion_documento, fecha_nacimiento, 
 direccion_residencia, telefono, correo_electronico, cod_verificacion_rut, area_desempenada, 
 fecha_creacion, fecha_actualizacion, no_registro)
 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)";

$stmt_emp = $conexion->prepare($sql_empleado);
$stmt_emp->bind_param("iisssssssssssss", $id_supervisor, $id_salud, $n_proceso, $nombre, $n_cedula, $unidad,
                      $lugar_expedicion_documento, $fecha_expedicion_documento, $fecha_nacimiento, $direccion,
                      $telefono, $correo, $cod_verificacion_rut, $area_desempenada, $n_registro);
$stmt_emp->execute();
$id_empleado = $stmt_emp->insert_id;
$stmt_emp->close();

// PROFESIÓN
$estado = "activo";
$id_profesion = null;

if (isset($_POST['profesionCheck']) && $_POST['profesionCheck'] === 'on') {
    // Usuario quiere registrar una nueva profesión
    $nueva_profesion = trim($_POST['profesion']);

    if (!empty($nueva_profesion)) {
        // Insertar nueva profesión
        $sql_nueva = "INSERT INTO profesiones (profesion, fecha_creacion, fecha_actualizacion)
                      VALUES (?, NOW(), NOW())";
        $stmt_nueva = $conexion->prepare($sql_nueva);
        $stmt_nueva->bind_param("s", $nueva_profesion);
        $stmt_nueva->execute();
        $id_profesion = $stmt_nueva->insert_id;
        $stmt_nueva->close();
    } else {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Debes escribir una nueva profesión.'
        ]);
        exit;
    }
} else {
    // Tomar la profesión existente del select
    if (isset($_POST['profesion_id']) && is_numeric($_POST['profesion_id'])) {
        $id_profesion = intval($_POST['profesion_id']);
    } else {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Debe seleccionar una profesión válida.'
        ]);
        exit;
    }
}
/* SUPERVISOR
$estado = "activo";
$id_supervisor = null;

if (isset($_POST['supervisorCheck']) && $_POST['supervisorCheck'] === 'on') {
    // Usuario quiere registrar un nuevo supervisor
    $nuevo_supervisor = trim($_POST['supervisor']);

    if (!empty($nuevo_supervisor)) {
        // Insertar nuevo supervisor
        $sql_nueva = "INSERT INTO supervisor (nombre_supervisor, fecha_creacion, fecha_actualizacion)
                      VALUES (?, NOW(), NOW())";
        $stmt_nueva = $conexion->prepare($sql_nueva);
        $stmt_nueva->bind_param("s", $nuevo_supervisor);
        $stmt_nueva->execute();
        $id_supervisor = $stmt_nueva->insert_id;
        $stmt_nueva->close();
    } else {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Debes escribir un nuevo supervisor.'
        ]);
        exit;
    }
} else {
    // Tomar la profesión existente del select
    if (isset($_POST['supervisor_id']) && is_numeric($_POST['supervisor_id'])) {
        $id_supervisor = intval($_POST['supervisor_id']);
    } else {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Debe seleccionar un supervisor válido.'
        ]);
        exit;
    }
} */


// INSERTAR EN EMPLEADOS_X_PROFESION
$sql_prof = "INSERT INTO empleados_x_profesion (id_profesion, id_empleado, estado)
             VALUES (?, ?, ?)";
$stmt_prof = $conexion->prepare($sql_prof);
$stmt_prof->bind_param("iis", $id_profesion, $id_empleado, $estado);
$stmt_prof->execute();
$stmt_prof->close();

header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'message' => 'Empleado registrado correctamente.'
]);
$conexion->close();
?>