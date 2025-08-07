<?php
include('../bd/cn.php');
$cedula = $_GET['cedula'] ?? '';
// Consultas para selects
$aseguradoras = $conexion->query("SELECT nombre FROM aseguradora");
$aseguradorarespo = $conexion->query("SELECT nombre FROM aseguradora_respo");
$objeto_poliza = $conexion->query("SELECT nombre FROM objeto");
$ordenadores = $conexion->query("SELECT nombre FROM ordenador_gasto");
$jefesContratos = $conexion->query("SELECT nombre FROM jefe_contratos");
$asesoresJuridicos = $conexion->query("SELECT nombre FROM asesor_juridico");

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Formulario Póliza</title>
  <link rel="stylesheet" type="text/css" href="../css/empleado.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    .hidden { display: none; }
    .boton-buscar, .boton-volver {
      position: absolute;
      background-color: #AF1415;
      color: white;
      padding: 10px 20px;
      font-size: 14px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .boton-buscar { top: 20px; left: 20px; }
    .boton-volver { top: 20px; right: 20px; }
    .boton-buscar svg, .boton-volver svg { width: 16px; height: 16px; fill: white; }
    .boton-buscar:hover, .boton-volver:hover { background-color: #45a049; }
  </style>
</head>
<body>

  <a href="formulario_busqueda.php" class="boton-buscar">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
      <path d="M10 2a8 8 0 105.293 14.707l5 5a1 1 0 001.414-1.414l-5-5A8 8 0 0010 2zm0 2a6 6 0 110 12A6 6 0 0110 4z"/>
    </svg>
    Buscar empleado
  </a>
  <a href="../menu1.php" class="boton-volver">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
      <path d="M15 18l-6-6 6-6" stroke="white" stroke-width="2" fill="none"/>
    </svg>
    Volver
  </a>

<form class="form-register" action="procesar_poliza.php" method="POST">
  <img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
  <img src="../imagenes/logo2.png" alt="Logo" class="img-right">
  <h2>Registro de Póliza</h2>

  <div class="contenedor-inputs">
    <fieldset>
      <legend>Datos de la Póliza</legend>
      <input type="hidden" name="cedula" value="<?= htmlspecialchars($cedula) ?>">

      <div class="campo">
        <label>Objeto</label>
        <select name="objeto" id="objetoSelect" required>
          <option value="">Seleccione un objeto</option>
          <?php while ($row = $objeto_poliza->fetch_assoc()) { ?>
            <option value="<?= $row['nombre'] ?>"><?= $row['nombre'] ?></option>
          <?php } ?>
        </select>
        <label><input type="checkbox" id="crearObjetoCheckbox"> Crear nuevo objeto</label>
        <input type="text" name="nuevo_objeto" id="nuevoObjetoInput" class="hidden" placeholder="Nuevo objeto" readonly>
      </div>

      <div class="campo">
        <label>Aseguradora poliza cumplimiento</label>
        <select name="nombre_aseguradora" id="aseguradoraSelect" required>
          <option value="">Seleccione una aseguradora</option>
          <?php while ($row = $aseguradoras->fetch_assoc()) { ?>
            <option value="<?= $row['nombre'] ?>"><?= $row['nombre'] ?></option>
          <?php } ?>
        </select>
        <label><input type="checkbox" id="crearAseguradoraCheckbox"> Crear nueva aseguradora</label>
        <input type="text" name="nueva_aseguradora" id="nuevaAseguradoraInput" class="hidden" placeholder="Nombre aseguradora" readonly>
        <input type="text" name="nit_aseguradora" id="nitAseguradoraInput" class="hidden" placeholder="NIT aseguradora" readonly>
      </div>

      <div class="campo"><label>Número de Póliza</label><input type="text" name="numero_poliza" required></div>
      <div class="campo"><label>Fecha Inicio Póliza</label><input type="date" name="fecha_inicio_poliza" required></div>
      <div class="campo"><label>Fecha Fin Póliza</label><input type="date" name="fecha_fin_poliza" required></div>
      <div class="campo"><label>Precio Póliza Cumplimiento</label><input type="text" name="precio_poliza_cumplimiento" required></div>
      <div class="campo"><label>Precio Póliza Calidad</label><input type="text" name="precio_poliza_calidad" required></div>
      <div class="campo"><label>Número Póliza Responsabilidad</label><input type="text" name="numero_poliza_responsabilidad" required></div>
      <div class="campo"><label>Fecha Inicio Responsabilidad</label><input type="date" name="fecha_inicio_poliza_responsabilidad" required></div>
      <div class="campo"><label>Fecha Fin Responsabilidad</label><input type="date" name="fecha_fin_poliza_responsabilidad" required></div>
      <div class="campo"> <label>Aseguradora poliza responsabilidad</label>
        <select name="nombre_aseguradora_respo" id="aseguradorarespoSelect" required>
          <option value="">Seleccione una aseguradora</option>
          <?php while ($row = $aseguradorarespo->fetch_assoc()) { ?>
          <option value="<?= $row['nombre'] ?>"><?= $row['nombre'] ?></option>
          <?php } ?>

        </select>
        <label><input type="checkbox" id="crearAseguradorarespoCheckbox"> Crear nueva aseguradora</label>
        <input type="text" name="nueva_aseguradorarespo" id="nuevaAseguradorarespoInput" class="hidden" placeholder="Nombre aseguradora" readonly>
        <input type="text" name="nit_aseguradorarespo" id="nitAseguradorarespoInput" class="hidden" placeholder="NIT aseguradorarespo" readonly>
      </div>
      <div class="campo"><label>Valor Responsabilidad</label><input type="text" name="valor_poliza_responsabilidad" required></div>
      <!-- ORDENADOR DEL GASTO -->
<div class="campo">
  <label>Ordenador del Gasto</label>
  <select name="ordenador_gasto" id="ordenadorSelect" required>
    <option value="">Seleccione un ordenador</option>
    <?php while ($row = $ordenadores->fetch_assoc()) { ?>
      <option value="<?= $row['nombre'] ?>"><?= $row['nombre'] ?></option>
    <?php } ?>
  </select>
  <label><input type="checkbox" id="crearOrdenadorCheckbox"> Crear nuevo ordenador</label>
  <input type="text" name="grado_ordenador" id="gradoOrdenadorInput" class="hidden" placeholder="Grado" readonly>
  <input type="text" name="nombre_ordenador" id="nombreOrdenadorInput" class="hidden" placeholder="Nombre" readonly>
  <input type="text" name="cedula_ordenador" id="cedulaOrdenadorInput" class="hidden" placeholder="Cédula" readonly>
  <input type="text" name="lugar_expedicion_ordenador" id="lugarOrdenadorInput" class="hidden" placeholder="Lugar de expedición" readonly>
</div>

<!-- JEFE DE CONTRATOS -->
<div class="campo">
  <label>Jefe de Contratos</label>
  <select name="jefe_contratos" id="jefeSelect" required>
    <option value="">Seleccione un jefe</option>
    <?php while ($row = $jefesContratos->fetch_assoc()) { ?>
      <option value="<?= $row['nombre'] ?>"><?= $row['nombre'] ?></option>
    <?php } ?>
  </select>
  <label><input type="checkbox" id="crearJefeCheckbox"> Crear nuevo jefe</label>
  <input type="text" name="nombre_jefe" id="nombreJefeInput" class="hidden" placeholder="Nombre" readonly>
  <input type="text" name="cedula_jefe" id="cedulaJefeInput" class="hidden" placeholder="Cédula" readonly>
</div>

<!-- ASESOR JURÍDICO -->
<div class="campo">
  <label>Asesor Jurídico</label>
  <select name="asesor_juridico" id="asesorSelect" required>
    <option value="">Seleccione un asesor</option>
    <?php while ($row = $asesoresJuridicos->fetch_assoc()) { ?>
      <option value="<?= $row['nombre'] ?>"><?= $row['nombre'] ?></option>
    <?php } ?>
  </select>
  <label><input type="checkbox" id="crearAsesorCheckbox"> Crear nuevo asesor</label>
  <input type="text" name="nombre_asesor" id="nombreAsesorInput" class="hidden" placeholder="Nombre" readonly>
  <input type="text" name="cedula_asesor" id="cedulaAsesorInput" class="hidden" placeholder="Cédula" readonly>
</div>


    </fieldset>

    <button type="submit" id="btn-generar" class="btn-guardar">💾 Registrar Póliza</button>
    <div id="spinner" style="display: none; margin-top: 10px;">
      <div class="loader"></div>
      <p>Guardando póliza, por favor espere...</p>
    </div>
  </div>
</form>

<!-- SCRIPT: Activación de inputs nuevos -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  function toggleInput(checkboxId, selectName, inputIds) {
    const checkbox = document.getElementById(checkboxId);
    const select = document.querySelector(`select[name="${selectName}"]`);
    checkbox.addEventListener('change', function () {
      const checked = this.checked;
      select.disabled = checked;
      inputIds.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
          input.classList.toggle('hidden', !checked);
          input.readOnly = !checked;
          if (!checked) input.value = '';
        }
      });
    });
  }

  toggleInput('crearObjetoCheckbox', 'objeto', ['nuevoObjetoInput']);
  toggleInput('crearAseguradoraCheckbox', 'nombre_aseguradora', ['nuevaAseguradoraInput', 'nitAseguradoraInput']);
  toggleInput('crearAseguradorarespoCheckbox', 'nombre_aseguradora_respo', ['nuevaAseguradorarespoInput', 'nitAseguradorarespoInput']);
  toggleInput('crearOrdenadorCheckbox', 'ordenador_gasto', [
    'gradoOrdenadorInput',
    'nombreOrdenadorInput',
    'cedulaOrdenadorInput',
    'lugarOrdenadorInput'
  ]);

  toggleInput('crearJefeCheckbox', 'jefe_contratos', [
    'nombreJefeInput',
    'cedulaJefeInput'
  ]);

  toggleInput('crearAsesorCheckbox', 'asesor_juridico', [
    'nombreAsesorInput',
    'cedulaAsesorInput'
  ]);

});
</script>

<script>
  document.querySelector("form").addEventListener("submit", function(e) {
    const boton = document.getElementById("btn-generar");
    const spinner = document.getElementById("spinner");

    boton.disabled = true;
    spinner.style.display = "block";
    boton.textContent = "Guardando...";
  });
</script>

</body>
</html>
