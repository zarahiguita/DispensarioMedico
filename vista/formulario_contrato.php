<?php
include('../bd/cn.php');

// Consultas para cargar los selects
$rubros = $conexion->query("SELECT rubro_presupuestal, descripcion_rubro FROM rubro");
$cargos = $conexion->query("SELECT profesion FROM profesiones");
$bancos = $conexion->query("SELECT nombre FROM banco");
$lugares = $conexion->query("SELECT lugar, direccion FROM lugar_ejecucion");
$ordenador = $conexion->query("SELECT grado, nombre FROM ordenador_gasto");
$asesor = $conexion->query("SELECT nombre FROM asesor_juridico");
$supervisores = $conexion->query("SELECT nombre_supervisor FROM supervisor");
$tipo_banco = $conexion->query("SELECT tipo FROM tipo_banco");
$query_unidad = "SELECT * FROM unidad";
$cdps = $conexion->query("SELECT numero, fecha, valor FROM cep");
$unidad = $conexion->query($query_unidad);

if (!$unidad) {
    die("Error al consultar unidades: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Formulario Contrato</title>
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
  <form class="form-register" action="procesar_formulario.php" method="POST">
    <img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
    <img src="../imagenes/logo2.png" alt="Logo" class="img-right">
    <h2>Registro de contrataciòn</h2>
    <div class="contenedor-inputs">
      <fieldset>
        <legend>Información Básica</legend>
        <div class="campo"><label>Documento de Identidad</label><input type="text" name="documento_identidad" id="documentoInput" required></div>
        <div class="campo"><label>Nombre Completo</label><input type="text" name="nombre_completo" class="solo-letras" required></div>
        <div class="campo">
          
          <label>Unidad</label>
          <select name="unidad" id="unidadSelect" required >
  <option value="">Seleccione la Unidad</option>
  <?php
  if (isset($unidad) && $unidad instanceof mysqli_result && $unidad->num_rows > 0):
      while ($row = $unidad->fetch_assoc()):
  ?>
      <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['unidad']) ?></option>
  <?php
      endwhile;
  else:
  ?>
      <option value="">No hay unidades disponibles</option>
  <?php endif; ?>
</select>


          <label><input type="checkbox" id="crearUnidadCheckbox"> Crear unidad nueva</label>
          <input type="text" name="nueva_unidad" id="nuevaUnidadInput" class="hidden" placeholder="Ingrese unidad" readonly>
          <input type="text" name="nuevo_nombre_unidad" id="nuevoNombreUnidadInput" class="hidden" placeholder="Ingrese nombre de la unidad" readonly>
          <input type="text" name="nueva_localidad" id="nuevaLocalidadInput" class="hidden" placeholder="localidad" readonly>
        </div>
        
        
        <div class="campo"><label>Fecha de expedición de CC:</label><input type="date" name="fecha_expedicion_documento"  required></div>
        <div class="campo"><label>Ciudad de Expedición de la Cédula</label><input type="text" name="ciudad_expedicion" class="solo-letras" required></div>
        <div class="campo"><label>Dirección</label><input type="text" name="direccion" required></div>
        <div class="campo">
          <label>Rubro Presupuestal</label>
          <select name="rubro_presupuestal" id="rubroSelect" required >
            <option value="">Seleccione un rubro</option>
            <?php while ($row = $rubros->fetch_assoc()) { ?>
              <option value="<?= $row['rubro_presupuestal'] ?>"><?= $row['rubro_presupuestal'] ?> - <?= $row['descripcion_rubro'] ?></option>
            <?php } ?>
          </select>
          <label><input type="checkbox" id="crearRubroCheckbox"> Crear nuevo rubro</label>
          <input type="text" name="nuevo_rubro" id="nuevoRubroInput" class="hidden" placeholder="Nuevo rubro" readonly>
          <input type="text" name="descripcion_rubro" id="descripcionRubroInput" class="hidden" placeholder="Descripción del rubro" readonly>
        </div>
        <div class="campo">
          <label>Especialidad</label>
          <select name="cargo" id="cargoSelect" required>
            <option value="">Seleccione la especialidad</option>
            <?php while ($row = $cargos->fetch_assoc()) { ?>
              <option value="<?= $row['profesion'] ?>"><?= $row['profesion'] ?></option>
            <?php } ?>
          </select>
          <label><input type="checkbox" id="crearCargoCheckbox"> Crear nueva especialidad</label>
          <input type="text" name="nuevo_cargo" id="nuevoCargoInput" class="hidden" placeholder="Nuevo cargo" readonly>
        </div>
        <div class="campo">
          <label>Tipo de Cuenta Bancaria</label>
      <select name="tipo_cuenta_bancaria" id="tipo_bancoSelect" required>
            <option value="">Seleccione tipo de cuenta</option>
            <?php while ($row = $tipo_banco->fetch_assoc()) { ?>
              <option value="<?= $row['tipo'] ?>"><?= $row['tipo'] ?></option>
            <?php } ?>
          </select></div>
        <div class="campo"><label>Número de Cuenta Bancaria</label><input type="text" name="numero_cuenta_bancaria" required></div>
        <div class="campo">
          <label>Entidad Bancaria</label>
          <select name="entidad_bancaria" id="entidadSelect" required>
            <option value="">Seleccione una entidad</option>
            <?php while ($row = $bancos->fetch_assoc()) { ?>
              <option value="<?= $row['nombre'] ?>"><?= $row['nombre'] ?></option>
            <?php } ?>
          </select>
          <label><input type="checkbox" id="crearEntidadCheckbox"> Crear nueva entidad</label>
          <input type="text" name="nueva_entidad_bancaria" id="nuevaEntidadInput" class="hidden" placeholder="Nueva entidad bancaria" readonly>
        </div>
        <div class="campo">
          <label>Lugar de Ejecución</label>
          <select name="lugar_ejecucion" id="lugar_ejecucion" required>
            <option value="">Seleccione lugar</option>
            <?php
            // Reset pointer and fetch again for JS map
            $lugares->data_seek(0);
            while ($row = $lugares->fetch_assoc()) { ?>
              <option value="<?= $row['lugar'] ?>"><?= $row['lugar'] ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="campo">
          <label>Dirección Lugar de Ejecución</label>
          <input type="text" name="direccion_lugar_ejecucion" id="direccion_lugar_ejecucion" readonly>
        </div>
        <div class="campo"><label>Fecha de suscrpción</label><input type="date" name="fecha_suscripcion" required></div>
        <div class="campo"><label>Fecha Final del Contrato</label><input type="date" name="fecha_final_contrato" required></div>
        <div class="campo">
          <label>Nombre del Supervisor</label>
          <select name="nombre_supervisor" id="supervisorSelect" required >
            <option value="">Seleccione un supervisor</option>
            <?php while ($row = $supervisores->fetch_assoc()) { ?>
              <option value="<?= $row['nombre_supervisor'] ?>"><?= $row['nombre_supervisor'] ?></option>
            <?php } ?>
          </select>
          <label><input type="checkbox" id="crearSupervisorCheckbox"> Crear nuevo supervisor</label>
          <input type="text" name="nuevo_supervisor" id="nuevoSupervisorInput" class="hidden" placeholder="Nuevo supervisor" readonly>
        </div>
        <div class="campo">
          <label>Asesor juridico</label>
          <select name="nombre_asesor_juridico" id="asesorSelect" required>
            <option value="">Seleccione un Asesor juridico</option>
            <?php while ($row = $asesor->fetch_assoc()) { ?>
              <option value="<?= $row['nombre'] ?>"><?= $row['nombre'] ?></option>
            <?php } ?>
          </select>
          <label><input type="checkbox" id="crearAsesorCheckbox"> Agregar nuevo Asesor juridico</label>
          <input type="text" name="nuevo_nombre_asesor_juridico" id="nuevoAsesorInput" class="hidden" placeholder="Nombre" readonly>
          <input type="text" name="cedula_asesor_juridico" id="cedula_asesorInput" class="hidden" placeholder="Cedula" readonly>
        </div>
        <div class="campo"><label>Plazo de ejecución</label><input type="number" name="meses_contratados" required></div>
        <div class="campo"><label>Valor Total del Contrato</label><input type="number" name="valor_total_contrato" step="0.01" required></div>
   <div class="campo">
  <label>CDP</label>
  <select name="cdp" id="cdpSelect" required>
    <option value="">Seleccione un CDP</option>
    <?php while ($row = $cdps->fetch_assoc()) { ?>
      <option value="<?= $row['numero'] ?>">
        <?= 'N° ' . $row['numero'] . ' | Fecha: ' . $row['fecha'] . ' | Valor: $' . number_format($row['valor'], 0, ',', '.') ?>
      </option>
    <?php } ?>
  </select>

  <label><input type="checkbox" id="crearCdpCheckbox"> Crear nuevo CDP</label>

  <div id="nuevoCdpInputs" class="hidden">
    <input type="text" name="nuevo_cdp_numero" id="nuevoCdpNumero" class="campo" placeholder="Número de CDP" readonly>
    <input type="date" name="nuevo_cdp_fecha" id="nuevoCdpFecha" class="campo" placeholder="Fecha del CDP" readonly>
    <input type="number" name="nuevo_cdp_valor" id="nuevoCdpValor" class="campo" placeholder="Valor del CDP" readonly>
  </div>
</div>


        <div class="campo">
          <label>Ordenador del gasto</label>
          <select name="ordenador_gasto" id="ordenadorSelect" required>
            <option value="">Seleccione el ordenador del gasto</option>
            <?php while ($row = $ordenador->fetch_assoc()) { ?>
              <option value="<?= $row['grado'] . ' - ' . $row['nombre'] ?>"><?= $row['grado'] . ' - ' . $row['nombre'] ?></option>
            <?php } ?>
          </select>
          <label><input type="checkbox" id="crearOrdenadorCheckbox"> Agregar nuevo ordenador del gasto</label>
          <div id="nuevoOrdenadorInputs" class="hidden">
            <input type="text" name="nuevo_grado" placeholder="Grado" readonly>
            <input type="text" name="nuevo_nombre_ordenador" placeholder="Nombre" readonly>
            <input type="text" name="nuevo_cedula_ordenador" placeholder="Cédula" readonly>
            <input type="text" name="nuevo_lugar_expedicion_cedula" placeholder="Lugar de expedición" readonly>
          </div>
        </div>
      </fieldset>
      <button type="submit" id="btn-generar" class="btn-guardar">
  💾 Generar Contrato
</button>
<div id="spinner" style="display: none; margin-top: 10px;">
  <div class="loader"></div>
  <p>Generando contrato, por favor espere...</p>
</div>
    </div>
  </form>

  
<!-- SCRIPT: Habilita inputs si seleccionas checkboxes para agregar nuevos valores -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  function toggleSelectAndInput(checkboxId, selectName, inputIds) {
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

  // Configura los checkbox con sus respectivos inputs
  toggleSelectAndInput('crearUnidadCheckbox', 'unidad', ['nuevaUnidadInput', 'nuevoNombreUnidadInput', 'nuevaLocalidadInput']);
  toggleSelectAndInput('crearRubroCheckbox', 'rubro_presupuestal', ['nuevoRubroInput', 'descripcionRubroInput']);
  toggleSelectAndInput('crearCargoCheckbox', 'cargo', ['nuevoCargoInput']);
  toggleSelectAndInput('crearEntidadCheckbox', 'entidad_bancaria', ['nuevaEntidadInput']);
  toggleSelectAndInput('crearSupervisorCheckbox', 'nombre_supervisor', ['nuevoSupervisorInput']);
  toggleSelectAndInput('crearAsesorCheckbox', 'nombre_asesor_juridico', ['nuevoAsesorInput', 'cedula_asesorInput']);

  // Ordenador del gasto
  const crearOrdenadorCheckbox = document.getElementById('crearOrdenadorCheckbox');
  const ordenadorSelect = document.querySelector('select[name="ordenador_gasto"]');
  const nuevoOrdenadorInputs = document.getElementById('nuevoOrdenadorInputs');
  crearOrdenadorCheckbox.addEventListener('change', function () {
    const checked = this.checked;
    ordenadorSelect.disabled = checked;
    nuevoOrdenadorInputs.classList.toggle('hidden', !checked);
    Array.from(nuevoOrdenadorInputs.querySelectorAll('input')).forEach(input => {
      input.readOnly = !checked;
      if (!checked) input.value = '';
    });
  });

  // Direccion automática de lugar de ejecución
  const lugarDireccionMap = {
    <?php
    $lugares->data_seek(0);
    while ($row = $lugares->fetch_assoc()) {
      $lugar = addslashes($row['lugar']);
      $direccion = addslashes($row['direccion']);
      echo "'$lugar': '$direccion',";
    }
    ?>
  };
  document.getElementById('lugar_ejecucion').addEventListener('change', function () {
    const lugarSeleccionado = this.value;
    const direccion = lugarDireccionMap[lugarSeleccionado] || '';
    document.getElementById('direccion_lugar_ejecucion').value = direccion;
  });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const docInput = document.getElementById('documentoInput');
  const form = document.querySelector('.form-register');
  const inputsYSelects = form.querySelectorAll('input:not(#documentoInput):not([type="submit"]), select, textarea');
  const nombreInput = document.querySelector('input[name="nombre_completo"]');
  const tipoCuentaInput = document.querySelector('input[name="tipo_cuenta_bancaria"]');
  const numeroCuentaInput = document.querySelector('input[name="numero_cuenta_bancaria"]');
  const plazoInput = document.querySelector('input[name="meses_contratados"]');
  const valorContratoInput = document.querySelector('input[name="valor_total_contrato"]');
  const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');

  let validandoDocumento = false;

  // Función para bloquear todos los campos menos el documento
  function bloquearCampos() {
    inputsYSelects.forEach(el => {
      el.disabled = true;
      el.value = '';
    });
  }

  // Función para desbloquear campos
  function desbloquearCampos() {
    inputsYSelects.forEach(el => {
      el.disabled = false;
    });
  }

  // Solo números
  function validarNumero(valor) {
    return /^[0-9]+$/.test(valor.trim());
  }

  // Foco al siguiente campo habilitado
  function focusSiguienteCampo(campoActual) {
    const campos = Array.from(document.querySelectorAll('input:not([disabled]):not([type=hidden]), select:not([disabled]), textarea:not([disabled])'));
    const indexActual = campos.indexOf(campoActual);
    if (indexActual >= 0 && indexActual + 1 < campos.length) {
      campos[indexActual + 1].focus();
    }
  }

  // Lógica de validación del documento
  function validarDocumentoInput() {
    const documento = docInput.value.trim();
    if (documento === '') {
      bloquearCampos();
      Swal.fire({
        icon: 'error',
        title: 'Campo requerido',
        text: 'Por favor ingrese el número de documento.'
      }).then(() => {
        setTimeout(() => {
          docInput.focus();
          docInput.select();
        }, 0);
      });
      return;
    }

    validandoDocumento = true;
    fetch('verificar_documento.php?documento=' + encodeURIComponent(documento))
      .then(res => res.json())
      .then(data => {
        if (data.existe) {
          bloquearCampos();
          Swal.fire({
            icon: 'info',
            title: 'El contrato ya existe',
            text: 'Ya hay un contrato con este documento, si deseas consultarlo dale click en Buscar empleado'
          }).then(() => {
            docInput.value = '';
            setTimeout(() => {
              docInput.focus();
              docInput.select();
              validandoDocumento = false;
            }, 0);
          });
        } else {
          desbloquearCampos();
          validandoDocumento = false;
        }
      });
  }

  // Bloquear campos al inicio
  bloquearCampos();

  // Validación por teclado (Enter o Tab)
  docInput.addEventListener('keydown', function (e) {
    if ((e.key === 'Enter' || e.key === 'Tab') && !validandoDocumento) {
      e.preventDefault(); // Previene salto
      validarDocumentoInput();
      setTimeout(() => {
        focusSiguienteCampo(docInput);
      }, 100);
    }
  });

  // Validación al perder el foco
  docInput.addEventListener('blur', function () {
    if (!validandoDocumento) {
      validarDocumentoInput();
    }
  });

  // Solo permite números en documento
  docInput.addEventListener('input', () => {
    docInput.value = docInput.value.replace(/\D/g, '');
  });


  // Validación del campo nombre
  nombreInput.addEventListener('input', () => {
    const valor = nombreInput.value.trim();
    if (!/^[A-Za-zÁÉÍÓÚÑáéíóúñ ]+$/.test(valor)) {
      nombreInput.setCustomValidity('Solo se permiten letras y espacios.');
    } else {
      nombreInput.setCustomValidity('');
    }
  });

  // Limpieza de caracteres no alfabéticos
  tipoCuentaInput.addEventListener('input', () => {
    tipoCuentaInput.value = tipoCuentaInput.value.replace(/[^A-Za-zÁÉÍÓÚÑáéíóúñ ]/g, '');
  });

  // Solo números en cuenta
  numeroCuentaInput.addEventListener('input', () => {
    numeroCuentaInput.value = numeroCuentaInput.value.replace(/\D/g, '');
  });

  // Solo números en plazo
  plazoInput.addEventListener('input', () => {
    if (!validarNumero(plazoInput.value)) {
      Swal.fire({
        icon: 'warning',
        title: 'Solo números',
        text: 'Ingrese únicamente la cantidad de meses.'
      });
      plazoInput.value = plazoInput.value.replace(/\D/g, '');
    }
  });

  // Solo números en valor del contrato
  valorContratoInput.addEventListener('input', () => {
    valorContratoInput.value = valorContratoInput.value.replace(/[^\d.]/g, '');
  });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const nombreInput = document.querySelector('input[name="nombre_completo"]');

  nombreInput.addEventListener('input', function () {
    this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');
  });
});
</script>

<script>
  const selectUnidad = document.getElementById("unidad");
  const nuevaUnidad = document.getElementById("nueva_unidad");

  nuevaUnidad.addEventListener("input", function () {
    if (this.value.trim() !== "") {
      selectUnidad.required = false;
    } else {
      selectUnidad.required = true;
    }
  });

  selectUnidad.addEventListener("change", function () {
    if (this.value !== "") {
      nuevaUnidad.required = false;
    } else {
      nuevaUnidad.required = true;
    }
  });
</script>

<script>
  const selectRubro = document.getElementById("rubro_presupuestal");
  const nuevoRubro = document.getElementById("nuevo_rubro");

  nuevoRubro.addEventListener("input", function () {
    if (this.value.trim() !== "") {
      selectRubro.required = false;
    } else {
      selectRubro.required = true;
    }
  });

  selectRubro.addEventListener("change", function () {
    if (this.value !== "") {
      nuevoRubro.required = false;
    } else {
      nuevoRubro.required = true;
    }
  });
</script>
<script>
  const selectcargo = document.getElementById("cargo");
  const nuevoCargo = document.getElementById("nuevo_cargo");

  nuevoCargo.addEventListener("input", function () {
    if (this.value.trim() !== "") {
      selectcargo.required = false;
    } else {
      selectcargo.required = true;
    }
  });

  selectcargo.addEventListener("change", function () {
    if (this.value !== "") {
      nuevoCargo.required = false;
    } else {
      nuevoCargo.required = true;
    }
  });
</script>
<script>
  const selectentidad = document.getElementById("entidad_bancaria");
  const nuevaEntidad = document.getElementById("nueva_entidad_bancaria");

  nuevaEntidad.addEventListener("input", function () {
    if (this.value.trim() !== "") {
      selectentidad.required = false;
    } else {
      selectentidad.required = true;
    }
  });

  selectentidad.addEventListener("change", function () {
    if (this.value !== "") {
      nuevaEntidad.required = false;
    } else {
      nuevaEntidad.required = true;
    }
  });
</script>
<script>
  const selectNombreAsesor = document.getElementById("nombre_asesor_juridico");
  const nuevoNombreAsesor = document.getElementById("nuevo_nombre_asesor_juridico");

  nuevoNombreAsesor.addEventListener("input", function () {
    if (this.value.trim() !== "") {
      selectNombreAsesor.required = false;
    } else {
      selectNombreAsesor.required = true;
    }
  });

  selectNombreAsesor.addEventListener("change", function () {
    if (this.value !== "") {
      nuevoNombreAsesor.required = false;
    } else {
      nuevoNombreAsesor.required = true;
    }
  });
</script>
<script>
  const selectNombreSupervisor = document.getElementById("nombre_supervisor");
  const nuevoSupervisor = document.getElementById("nuevo_supervisor");

  nuevoSupervisor.addEventListener("input", function () {
    if (this.value.trim() !== "") {
      selectNombreSupervisor.required = false;
    } else {
      selectNombreSupervisor.required = true;
    }
  });

  selectNombreSupervisor.addEventListener("change", function () {
    if (this.value !== "") {
      nuevoSupervisor.required = false;
    } else {
      nuevoSupervisor.required = true;
    }
  });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const checkbox = document.getElementById('crearCdpCheckbox');
  const select = document.getElementById('cdpSelect');
  const inputsDiv = document.getElementById('nuevoCdpInputs');
  const inputs = inputsDiv.querySelectorAll('input');

  checkbox.addEventListener('change', function () {
    const checked = this.checked;
    select.disabled = checked;
    inputsDiv.classList.toggle('hidden', !checked);
    inputs.forEach(input => {
      input.readOnly = !checked;
      input.required = checked;
      if (!checked) input.value = '';
    });
    if (!checked) {
      select.required = true;
    } else {
      select.required = false;
    }
  });
});
</script>

<script>
  const selectOrdenador = document.getElementById("ordenador_gasto");
  const nuevoOrdenador = document.getElementById("nuevo_grado");

  nuevoOrdenador.addEventListener("input", function () {
    if (this.value.trim() !== "") {
      selectOrdenador.required = false;
    } else {
      selectOrdenador.required = true;
    }
  });

  selectOrdenador.addEventListener("change", function () {
    if (this.value !== "") {
      nuevoOrdenador.required = false;
    } else {
      nuevoOrdenador.required = true;
    }
  });
</script>
<script>
  document.querySelector("form").addEventListener("submit", function(e) {
    const boton = document.getElementById("btn-generar");
    const spinner = document.getElementById("spinner");

    // Mostrar spinner y desactivar botón
    boton.disabled = true;
    spinner.style.display = "block";
    boton.textContent = "Generando...";
  });
</script>

</body>
</html>
