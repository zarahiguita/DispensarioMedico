<?php
header('Content-Type: application/json');
include('cn.php');

try {
    $conexion->set_charset("utf8");

    // Obtener fecha actual
    $fechaActual = date("Y-m-d H:i:s");

    // Obtener los datos del formulario
    $empleado_id = $_POST['empleado_id'];
    $cuentaCheck = isset($_POST['cuentaCheck']);
    $cuenta_id = null;

    if ($cuentaCheck) {
        // El usuario quiere registrar una nueva cuenta bancaria
        $tipo_cuenta = $_POST['tipo_cuenta'] ?? '';
        $id_banco = $_POST['banco_id'] ?? '';
        $no_cuenta = $_POST['no_cuenta'] ?? '';

        if (!$tipo_cuenta || !$id_banco || !$no_cuenta) {
            echo json_encode(['status' => 'error', 'message' => 'Faltan datos de la nueva cuenta bancaria.']);
            exit;
        }

        // Verificar si la cuenta ya existe
        $stmtVerificar = $conexion->prepare("SELECT id FROM cuenta_bancaria WHERE no_cuenta = ?");
        $stmtVerificar->bind_param("s", $no_cuenta);
        $stmtVerificar->execute();
        $stmtVerificar->store_result();

        if ($stmtVerificar->num_rows > 0) {
            // Ya existe, obtener el ID
            $stmtVerificar->bind_result($cuenta_id);
            $stmtVerificar->fetch();
        } else {
            // Insertar nueva cuenta bancaria
            $stmtCuenta = $conexion->prepare("INSERT INTO cuenta_bancaria (tipo_cuenta, id_banco, no_cuenta, fecha_creacion, fecha_actualizacion) VALUES (?, ?, ?, ?, ?)");
            $stmtCuenta->bind_param("sisss", $tipo_cuenta, $id_banco, $no_cuenta, $fechaActual, $fechaActual);
            if (!$stmtCuenta->execute()) {
                echo json_encode(['status' => 'error', 'message' => 'Error al registrar la cuenta bancaria.']);
                exit;
            }
            $cuenta_id = $stmtCuenta->insert_id;
        }

    } else {
        $cuenta_id = $_POST['cuenta_id_existente'] ?? null;
        if (!$cuenta_id) {
            echo json_encode(['status' => 'error', 'message' => 'Debe seleccionar o registrar una cuenta bancaria.']);
            exit;
        }
    }

    // Insertar contrato
    $stmtContrato = $conexion->prepare("INSERT INTO contratos (
        id_empleados, id_cuenta_bancaria, no_contrato, fecha_inicio, fecha_terminacion, meses_contratados,
        valor_total, valor_mensual, rp, fecha_rp, fecha_suscripcion, cdp, fecha_acta_inicio,
        fecha_creacion, fecha_actualizacion, id_rubro, id_tipo_contratacion, id_modalidad_contratacion
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmtContrato->bind_param(
        "iissssddsssssssiii",
        $empleado_id,
        $cuenta_id,
        $_POST['no_contrato'],
        $_POST['fecha_inicio'],
        $_POST['fecha_terminacion'],
        $_POST['meses_contratados'],
        $_POST['valor_total'],
        $_POST['valor_mensual'],
        $_POST['rp'],
        $_POST['fecha_rp'],
        $_POST['fecha_suscripcion'],
        $_POST['cdp'],
        $_POST['fecha_acta_inicio'],
        $fechaActual,
        $fechaActual,
        $_POST['rubro_id'],
        $_POST['tipo_id'],
        $_POST['modalidad_id']
    );

    if ($stmtContrato->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Contrato registrado exitosamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar el contrato.']);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>
