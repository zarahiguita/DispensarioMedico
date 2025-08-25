<?php 
// 🔧 Ajusta esta ruta según tu proyecto
include("../conexion/cn.php");

$cedula         = $_GET['cedula'] ?? '';
$nombre_usuario = $_GET['nombre_completo'] ?? '';
$objeto         = $_GET['objeto'] ?? '';

$asesores     = $conexion->query("SELECT nombre FROM asesor_juridico");
$supervisores = $conexion->query("SELECT nombre_supervisor FROM supervisor");
$modalidades  = $conexion->query("SELECT modalidad FROM modalidad_contratacion");
$ordenadores  = $conexion->query("SELECT nombre FROM ordenador_gasto");
$rps          = $conexion->query("SELECT numero, fecha, valor FROM rp");

// === Trae datos del contrato para precargar textos ===
$datos = null;
if ($cedula !== '') {
    $stmt = $conexion->prepare("
        SELECT no_contrato, cdp, fecha_cdp, rp, fecha_rp,
               rubro_presupuestal, descripcion_rubro,
               valor_total_contrato, fecha_terminacion_contrato,
               profesion, nombre_completo_contratista, fecha_suscripcion,
               documento_identidad, lugar_expedicion_documento, objeto, supervisor
        FROM contrato_detallado
        WHERE documento_identidad = ?
        LIMIT 1
    ");
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $datos = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

function fechaLargaEs($iso, $locale='es_CO', $tz='America/Bogota'){
    if(!$iso) return '';
    if(class_exists('IntlDateFormatter')){
        $fmt = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE, $tz, IntlDateFormatter::GREGORIAN);
        $dt  = new DateTime($iso, new DateTimeZone($tz));
        return ucfirst(mb_strtolower($fmt->format($dt), 'UTF-8'));
    }
    return date('d \d\e F \d\e Y', strtotime($iso)); // fallback
}
function numeroALetrasMayus($n){
    if(class_exists('NumberFormatter')){
        $f = new NumberFormatter("es", NumberFormatter::SPELLOUT);
        return mb_strtoupper($f->format((float)$n), 'UTF-8');
    }
    return number_format((float)$n, 0, ',', '.'); // fallback
}

$valor_numerico = $datos['valor_total_contrato'] ?? 0;
$valor_letras   = numeroALetrasMayus($valor_numerico);
$fecha_cdp_fmt  = fechaLargaEs($datos['fecha_cdp'] ?? '');
$fecha_rp_fmt   = fechaLargaEs($datos['fecha_rp'] ?? '');
$fecha_fin_fmt  = mb_strtoupper(fechaLargaEs($datos['fecha_terminacion_contrato'] ?? ''), 'UTF-8');

$forma_pago_default = "FORMA DE REMUNERACIÓN AL CONTRATISTA\tMDN – EJÉRCITO NACIONAL – DISPENSARIO MEDICO DE MEDELLIN, se obliga a pagar el 100% del valor del contrato, de la siguiente forma: 
Los pagos que de conformidad con este contrato deba efectuar el MDN –EJÉRCITO NACIONAL–DISPENSARIO MEDICO DE MEDELLIN, al contrato se imputará a las apropiaciones presupuestales de la vigencia 2025, 
SIIF- CDP No. " . ($datos['cdp'] ?? '___') . " del {$fecha_cdp_fmt} y CRP No. " . ($datos['rp'] ?? '___') . " del {$fecha_rp_fmt}, expedido por el Jefe de Presupuesto del DMMED, por el Rubro presupuestal " . ($datos['rubro_presupuestal'] ?? '___') . " " . ($datos['descripcion_rubro'] ?? '') . ", recurso 16 SSF, por valor de {$valor_letras} DE PESOS M/CTE ($" . number_format($valor_numerico, 0, ',', '.') . ",00) IVA INCLUIDO, pagos parciales de acuerdo al recibido a satisfacción mensual de cada solicitud realizada por el supervisor del contrato de acuerdo a las necesidades de la Dirección de Sanidad Ejército – Dispensario Médico de Medellín, previo cumplimiento de los siguientes requisitos:

a) Acta de recibo a satisfacción parcial, expedida por el supervisor, y representante del contratista.
b) Situación de recursos por parte del Ministerio de Hacienda y Crédito Público, Dirección del Tesoro Nacional (asignación cupo PAC).
c) Que se ejecuten los demás trámites administrativos correspondientes.
d) Verificación por parte del MDN – EJÉRCITO NACIONAL – DIRECCIÓN DE SANIDAD – DISPENSARIO MEDICO DE MEDELLIN del cumplimiento del contratista del pago de aportes parafiscales y los propios del SENA, ICBF y Cajas de Compensación Familiar.
e) Documento equivalente a factura según artículo 3 del Decreto 522 de 2003.

PARÁGRAFO PRIMERO. Este pago se considera de contado por lo que no se aceptará el cobro de financiación en este caso.
PARÁGRAFO SEGUNDO. En el evento de prórroga en la entrega del objeto del contrato, se postergará el pago.
PARÁGRAFO TERCERO: El MDN – EJÉRCITO NACIONAL – DIRECCIÓN DE SANIDAD – DISPENSARIO MEDICO DE MEDELLIN, realizará los pagos en la cuenta.
PARÁGRAFO CUARTO: L";

$plazo_prestacion_default = "Será una vez se haya constituido y aprobada la póliza de garantía, se cuente con los soportes presupuestales que respalden la ejecución, hasta el {$fecha_fin_fmt}, y/o hasta agotar presupuesto asignado (lo que primero ocurra).";
$plazo_contrato_default   = "Desde la aprobación de la garantía y el registro presupuestal, sin exceder el {$fecha_fin_fmt}.";

// “Quemados” ahora editables:
$lugar_default                = "Medellín, Antioquia";
$subdirector_nombre_default   = "MARLON GÓMEZ RODRÍGUEZ";
$subdirector_cargo_default    = "Subdirector Administrativo y financiero del DMMED";
$representante_nombre_default = "TITO ALBERTO ZAPATA BEDOYA";
$representante_cc_default     = "71.614.965";
$representante_lugar_default  = "Medellín";
$numero_acta_default          = "00000000";
$anio_contrato_default        = date('Y');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/general.css">
    <title>Formulario Acta de Inicio</title>
    <style>
        body { background-color: white; font-family: Arial, sans-serif; margin: 0; padding: 0; }
        select, input[type="text"], input[type="date"], input[type="number"], input[type="hidden"], textarea {
            width: 100%; max-width: 100%; box-sizing: border-box; font-size: 16px; padding: 8px;
            border: 2px solid rgb(34, 28, 13); border-radius: 4px; background-color: #E0E0C0; color: #333; margin-bottom: 15px;
        }
        .form-register { background-color: #AF1415; color: #F0E68C; padding: 20px; border-radius: 8px; width: 500px;
            margin: 50px auto; box-shadow: 0 2px 5px rgba(0,0,0,0.3); position: relative; }
        .form__titulo { color: #AF1415; background-color: #fff; text-align: center; margin-bottom: 20px; font-size: 1.8em; padding: 10px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        .btn_enviar { width: 100%; padding: 10px; background-color: rgb(34, 28, 13); color: #fff; border: none; border-radius: 4px; font-size: 1em; cursor: pointer; }
        .btn_enviar:hover { background-color: #6B8E23; }
        .img-left, .img-right { position: absolute; top: 50%; transform: translateY(-50%); width: 300px; height: auto; opacity: .08; }
        .img-left { left: -340px; }
        .img-right { right: -320px; }
        .campo-extra { display: none; }
        hr { border: 0; border-top: 1px solid #fff; }
    </style>
    <script>
        function toggleCampo(id, select) {
            const campo = document.getElementById(id);
            campo.style.display = (select.value === 'nuevo') ? 'block' : 'none';
        }
    </script>
</head>
<body>
  <a href="formulario_busqueda.php" class="boton-buscar">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10 2a8 8 0 105.293 14.707l5 5a1 1 0 001.414-1.414l-5-5A8 8 0 0010 2zm0 2a6 6 0 110 12A6 6 0 0110 4z"/></svg>
    Buscar empleado
  </a>
  <a href="../menu1.php" class="boton-volver">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6" stroke="white" stroke-width="2" fill="none"/></svg>
    Volver
  </a>

<h2 class="form__titulo">FORMULARIO ACTA DE INICIO</h2>

<form class="form-register" action="procesar_acta_inicio.php" method="POST">
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
        <option value="nuevo">Agregar Nuevo</option>
    </select>
    <input type="text" name="nuevo_supervisor" id="nuevo_supervisor" class="campo-extra" placeholder="Nuevo supervisor">

    <label for="asesor_juridico">Asesor Jurídico:</label>
    <select name="asesor_juridico" onchange="toggleCampo('nuevo_asesor', this)" required>
        <option value="">-- Selecciona Asesor Jurídico --</option>
        <?php while ($row = $asesores->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['nombre']) ?>"><?= htmlspecialchars($row['nombre']) ?></option>
        <?php endwhile; ?>
        <option value="nuevo">Agregar Nuevo</option>
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

    <hr>

    <!-- NUEVOS CAMPOS: plantillas editables -->
    <h3 style="margin-top:0;">Textos de la plantilla (editables)</h3>

    <label for="lugar">Lugar:</label>
    <input type="text" id="lugar" name="lugar" value="<?= htmlspecialchars($lugar_default) ?>">

    <label for="nombre_subdirector">Nombre Subdirector:</label>
    <input type="text" id="nombre_subdirector" name="nombre_subdirector" value="<?= htmlspecialchars($subdirector_nombre_default) ?>">

    <label for="cargo_subdirector">Cargo Subdirector:</label>
    <input type="text" id="cargo_subdirector" name="cargo_subdirector" value="<?= htmlspecialchars($subdirector_cargo_default) ?>">

    <label for="representante_legal">Representante legal:</label>
    <input type="text" id="representante_legal" name="representante_legal" value="<?= htmlspecialchars($representante_nombre_default) ?>">

    <label for="representante_cc">CC Representante legal:</label>
    <input type="text" id="representante_cc" name="representante_cc" value="<?= htmlspecialchars($representante_cc_default) ?>">

    <label for="representante_lugar_cc">Lugar expedición CC Rep. legal:</label>
    <input type="text" id="representante_lugar_cc" name="representante_lugar_cc" value="<?= htmlspecialchars($representante_lugar_default) ?>">

    <div id="campo_numero_acta">
      <label for="numero_acta">Número de Acta:</label>
      <input type="text" id="numero_acta" name="numero_acta" value="<?= htmlspecialchars($numero_acta_default) ?>">
    </div>

    <label for="anio_contrato">Año del contrato:</label>
    <input type="text" id="anio_contrato" name="anio_contrato" value="<?= htmlspecialchars($anio_contrato_default) ?>">

    <label for="forma_pago_texto">Forma de pago (puedes editar):</label>
    <textarea id="forma_pago_texto" name="forma_pago_texto" rows="12"><?= htmlspecialchars($forma_pago_default) ?></textarea>

    <label for="texto_plazo">Texto de plazo (cambia con la modalidad, pero puedes editar):</label>
    <textarea id="texto_plazo" name="texto_plazo" rows="4"><?= htmlspecialchars($plazo_prestacion_default) ?></textarea>

    <input type="submit" value="📂 Generar Acta de inicio" class="btn_enviar">
</form>

<script>
  // Plantillas desde PHP
  const PLAZO_PRESTACION = <?= json_encode($plazo_prestacion_default, JSON_UNESCAPED_UNICODE) ?>;
  const PLAZO_CONTRATO   = <?= json_encode($plazo_contrato_default, JSON_UNESCAPED_UNICODE) ?>;

  const selModalidad  = document.getElementById('modalidad');
  const campoNumActa  = document.getElementById('campo_numero_acta');
  const txtPlazo      = document.getElementById('texto_plazo');

  function aplicarModalidad() {
    const val = (selModalidad.value || '').toLowerCase();
    if (val.indexOf('prestación') !== -1 || val.indexOf('prestacion') !== -1) {
      campoNumActa.style.display = 'block';
      txtPlazo.value = PLAZO_PRESTACION;
    } else if (val) {
      campoNumActa.style.display = 'none';
      txtPlazo.value = PLAZO_CONTRATO;
    } else {
      campoNumActa.style.display = 'none';
    }
  }
  selModalidad.addEventListener('change', aplicarModalidad);
  aplicarModalidad();
</script>
</body>
</html>
