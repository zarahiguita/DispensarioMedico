<?php 
include("../conexion/cn.php");


$cedula = $_GET['cedula'] ?? '';
$nombre_usuario = $_GET['nombre_completo'] ?? '';
$objeto = $_GET['objeto'] ?? '';
$asesores = $conexion->query("SELECT nombre FROM asesor_juridico");
$supervisores = $conexion->query("SELECT nombre_supervisor FROM supervisor");
$modalidades = $conexion->query("SELECT modalidad FROM modalidad_contratacion");
$ordenadores = $conexion->query("SELECT nombre FROM ordenador_gasto");
$rps = $conexion->query("SELECT numero, fecha, valor FROM rp");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST["cedula"];
    $modalidad = $_POST["modalidad"];
    $fecha_inicio = $_POST["fecha_inicio"];
    $supervisor = $_POST["supervisor"];
    $asesor_juridico = $_POST["asesor_juridico"];
    $nuevo_supervisor = $_POST["nuevo_supervisor"] ?? '';
    $nuevo_asesor = $_POST["nuevo_asesor"] ?? '';
    $rp = $_POST["rp"] ?? '';
    $numero_rp = $_POST["numero_rp"] ?? '';
    $fecha_rp = $_POST["fecha_rp"] ?? '';
    $valor_rp = $_POST["valor_rp"] ?? '';

if ($rp === 'nuevo' && $numero_rp && $fecha_rp && $valor_rp) {
    // Verificar si ya existe ese número de RP
    $verifica = $conexion->prepare("SELECT id FROM rp WHERE numero = ?");
    $verifica->bind_param("s", $numero_rp);
    $verifica->execute();
    $verifica->store_result();

    if ($verifica->num_rows === 0) {
        $inserta = $conexion->prepare("INSERT INTO rp (numero, fecha, valor) VALUES (?, ?, ?)");
        $inserta->bind_param("ssd", $numero_rp, $fecha_rp, $valor_rp);
        $inserta->execute();
        $inserta->close();
    }

    $verifica->close();
    $rp_final = $numero_rp;
} else {
    $rp_final = $rp;
}

   

    if ($nuevo_supervisor) {
        $conexion->query("INSERT INTO supervisor (nombre_supervisor, fecha_creacion, fecha_actualizacion) VALUES ('$nuevo_supervisor', NOW(), NOW())");
        $supervisor = $nuevo_supervisor;
    }
    if ($nuevo_asesor) {
        $conexion->query("INSERT INTO asesor_juridico (nombre, cedula) VALUES ('$nuevo_asesor', '')");
        $asesor_juridico = $nuevo_asesor;
    }
    // ORDENADOR DEL GASTO
$ordenador_gasto = $_POST["ordenador_gasto"];
if ($ordenador_gasto === 'nuevo') {
    $grado = $_POST["grado_ordenador"] ?? '';
    $nombre = $_POST["nombre_ordenador"] ?? '';
    $cedula_ordenador = $_POST["cedula_ordenador"] ?? '';
    $lugar_expedicion = $_POST["lugar_expedicion_ordenador"] ?? '';

    if ($nombre) {
        $conexion->query("INSERT INTO ordenador_gasto (grado, nombre, cedula, lugar_expedicion_cedula)
                          VALUES ('$grado', '$nombre', '$cedula_ordenador', '$lugar_expedicion')");
        $ordenador_gasto_final = $nombre;
    } else {
        $ordenador_gasto_final = 'N/A';
    }
} else {
    $ordenador_gasto_final = $ordenador_gasto;
}


    $sql = "UPDATE contrato_detallado SET 
        fecha_acta_inicio = ?,  
        supervisor = ?, 
        nombre_asesor_juridico = ?
       
        WHERE documento_identidad = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssss", 
        $fecha_inicio, 
        $supervisor, $asesor_juridico, $cedula
    );

    if ($stmt->execute()) {
        echo "<script>alert('✅ Acta de inicio guardada correctamente.'); window.location.href = 'formulario_busqueda.php';</script>";
    } else {
        echo "<script>alert('❌ Error al guardar el acta.'); window.history.back();</script>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario Acta de Inicio</title>
    <style>
        body { background-color: white; font-family: Arial, sans-serif; margin: 0; padding: 0; }
       select,
input[type="text"],
input[type="date"],
input[type="number"],
input[type="hidden"],
textarea {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    font-size: 16px;
    padding: 8px;
    border: 2px solid rgb(34, 28, 13);
    border-radius: 4px;
    background-color: #E0E0C0;
    color: #333;
    margin-bottom: 15px;
}


        .form-register {
            background-color: #AF1415; color: #F0E68C;
            padding: 20px; border-radius: 8px; width: 500px;
            margin: 50px auto; box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        .form__titulo {
            color: #AF1415;
            background-color: #fff;
            text-align: center; margin-bottom: 20px; font-size: 1.8em;
            padding: 10px 0;
        }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        .btn_enviar {
            width: 100%; padding: 10px;
            background-color: rgb(34, 28, 13); color: #fff;
            border: none; border-radius: 4px;
            font-size: 1em; cursor: pointer;
        }
        .btn_enviar:hover { background-color: #6B8E23; }
        .img-left, .img-right {
            position: absolute; top: 50%; transform: translateY(-50%);
            width: 300px; height: auto;
        }
        .img-left { left: 120px; }
        .img-right { right: 90px; }
        .campo-extra { display: none; }
    </style>
    <script>
        function toggleCampo(id, select) {
            const campo = document.getElementById(id);
            campo.style.display = select.value === 'nuevo' ? 'block' : 'none';
        }
    </script>
</head>
<body>

<h2 class="form__titulo">FORMULARIO ACTA DE INICIO</h2>

<form method="POST" class="form-register">
    <img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
    <img src="../imagenes/logo2.png" alt="Logo" class="img-right">

    <input type="hidden" name="cedula" value="<?= htmlspecialchars($cedula) ?>">
    <input type="hidden" name="nombre_completo" value="<?= htmlspecialchars($nombre_usuario) ?>">
    <input type="hidden" name="objeto" value="<?= htmlspecialchars($objeto) ?>">

<label for="modalidad">Modalidad de Contratación:</label>
<select id="modalidad" name="modalidad" required>
    <option value="">-- Selecciona Modalidad --</option>
    <?php while ($row = $modalidades->fetch_assoc()): ?>
        <option value="<?= htmlspecialchars($row['modalidad']) ?>"><?= htmlspecialchars($row['modalidad']) ?></option>
    <?php endwhile; ?>
</select>

    <label for="fecha_inicio">Fecha del Acta de Inicio:</label>
    <input type="date" id="fecha_inicio" name="fecha_inicio" required>

    <label for="supervisor">Supervisor:</label>
    <select name="supervisor" onchange="toggleCampo('nuevo_supervisor', this)" required>
        <option value="">-- Selecciona Supervisor --</option>
        <?php while ($row = $supervisores->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['nombre_supervisor']) ?>"><?= htmlspecialchars($row['nombre_supervisor']) ?></option>
        <?php endwhile; ?>
        <option value="nuevo"> Agregar Nuevo</option>
    </select>
    <input type="text" name="nuevo_supervisor" id="nuevo_supervisor" class="campo-extra" placeholder="Nuevo supervisor">

    <label for="asesor_juridico">Asesor Jurídico:</label>
    <select name="asesor_juridico" onchange="toggleCampo('nuevo_asesor', this)" required>
        <option value="">-- Selecciona Asesor Jurídico --</option>
        <?php while ($row = $asesores->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['nombre']) ?>"><?= htmlspecialchars($row['nombre']) ?></option>
        <?php endwhile; ?>
        <option value="nuevo"> Agregar Nuevo</option>
    </select>
    <input type="text" name="nuevo_asesor" id="nuevo_asesor" class="campo-extra" placeholder="Nuevo asesor jurídico">
    <label for="rp">RP:</label>
<select name="rp" id="rp" onchange="toggleCampo('nuevo_rp', this)" required>
    <option value="">-- Selecciona RP --</option>
    <?php while ($row = $rps->fetch_assoc()): ?>
        <option value="<?= htmlspecialchars($row['numero']) ?>">
            <?= 'N° ' . htmlspecialchars($row['numero']) . ' | Fecha: ' . htmlspecialchars($row['fecha']) . ' | Valor: $' . number_format($row['valor'], 0, ',', '.') ?>
        </option>
    <?php endwhile; ?>
    <option value="nuevo">Agregar Nuevo</option>
</select>

<div id="nuevo_rp" class="campo-extra">
    <label for="numero_rp">Número RP:</label>
    <input type="text" name="numero_rp" id="numero_rp">

    <label for="fecha_rp">Fecha RP:</label>
    <input type="date" name="fecha_rp" id="fecha_rp">

    <label for="valor_rp">Valor RP:</label>
    <input type="number" name="valor_rp" id="valor_rp">
</div>

    
    <label for="ordenador_gasto">Ordenador del Gasto:</label>
    <select name="ordenador_gasto" onchange="toggleCampo('nuevo_ordenador', this)" required>
        <option value="">-- Selecciona Ordenador --</option>
        <?php while ($row = $ordenadores->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['nombre']) ?>"><?= htmlspecialchars($row['nombre']) ?></option>
        <?php endwhile; ?>
        <option value="nuevo">Agregar Nuevo</option>
    </select>

    <div id="nuevo_ordenador" class="campo-extra">
        <label for="grado_ordenador">Grado:</label>
        <input type="text" name="grado_ordenador" id="grado_ordenador">

        <label for="nombre_ordenador">Nombre:</label>
        <input type="text" name="nombre_ordenador" id="nombre_ordenador">

        <label for="cedula_ordenador">Cédula:</label>
        <input type="text" name="cedula_ordenador" id="cedula_ordenador">

        <label for="lugar_expedicion_ordenador">Lugar de Expedición de la Cédula:</label>
        <input type="text" name="lugar_expedicion_ordenador" id="lugar_expedicion_ordenador">
    </div>

    <input type="submit" value="📂 Generar Acta de inicio" class="btn_enviar">
</form>

</body>
</html>
