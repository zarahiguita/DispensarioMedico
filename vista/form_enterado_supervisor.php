<?php
// Usa los MISMO estilos/clases del formulario de referencia (empleado.css)
// Solo agrego .hidden y estilos de los dos botones como en tu ejemplo.
include('../bd/cn.php');


$objeto_poliza = $conexion->query("SELECT nombre FROM objeto");


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario — ENTERADO SUPERVISOR (AO)</title>
    <link rel="stylesheet" type="text/css" href="../css/empleado.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    .hidden {
        display: none;
    }

    .boton-buscar,
    .boton-volver {
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

    .boton-buscar {
        top: 20px;
        left: 20px;
    }

    .boton-volver {
        top: 20px;
        right: 20px;
    }

    .boton-buscar svg,
    .boton-volver svg {
        width: 16px;
        height: 16px;
        fill: white;
    }

    .boton-buscar:hover,
    .boton-volver:hover {
        background-color: #45a049;
    }
    </style>
</head>

<body>
    <!-- Botones arriba (idénticos al ejemplo) -->
    <a href="formulario_busqueda.php" class="boton-buscar">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path
                d="M10 2a8 8 0 105.293 14.707l5 5a1 1 0 001.414-1.414l-5-5A8 8 0 0010 2zm0 2a6 6 0 110 12A6 6 0 0110 4z" />
        </svg>
        Buscar empleado
    </a>
    <a href="../menu1.php" class="boton-volver">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M15 18l-6-6 6-6" stroke="white" stroke-width="2" fill="none" />
        </svg>
        Volver
    </a>

    <!-- MISMAS clases y estructura -->
    <form class="form-register" action="generar_enterado_supervisor.php" method="POST" autocomplete="off">
        <img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
        <img src="../imagenes/logo2.png" alt="Logo" class="img-right">

        <h2>Registro de contrataciòn</h2>

        <div class="contenedor-inputs">
            <!-- Encabezado y asunto -->
            <fieldset>
                <legend>Encabezado y Asunto</legend>

                <div class="campo">
                    <label>Lugar</label>
                    <input type="text" name="lugar" id="lugar" value="Medellín, Antioquia" required>
                </div>

                <div class="campo">
                    <label>Fecha del oficio</label>
                    <input type="date" name="fecha_oficio" id="fecha_oficio" required>
                </div>

                <div class="campo">
                    <label>Grado (abreviado)</label>
                    <input type="text" name="grado_supervisor_abrev" value="SV" id="grado_supervisor_abrev" required>
                </div>

                <div class="campo">
                    <label>Nombre del Supervisor</label>
                    <input type="text" name="nombre_supervisor" id="nombre_supervisor" required>
                </div>

                <div class="campo">
                    <label>Cargo Supervisor</label>
                    <input type="text" name="cargo_supervisor" id="cargo_supervisor"
                        placeholder="Supervisor Contrato ..." required>
                </div>

                <div class="campo">
                    <label>Número de Aceptación de Oferta</label>
                    <input type="text" name="numero_ao" id="numero_ao" value="000-DMMED-2025" required>
                </div>

                <div class="campo">
                    <label>Objeto</label>
                    <select name="objeto_contrato" id="objeto_contrato" required>
                        <option value="">Seleccione un objeto</option>
                        <?php while ($row = $objeto_poliza->fetch_assoc()) { ?>
                        <option value="<?= $row['nombre'] ?>"><?= $row['nombre'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </fieldset>

            <!-- Contratista y representante -->
            <fieldset>
                <legend>Contratista y Representante</legend>

                <div class="campo">
                    <label>Razón Social </label>
                    <input type="text" name="contratista_razon_social" id="contratista_razon_social"
                        placeholder="SOLUCIONES INTEGRALES ..." required>
                </div>

                <div class="campo">
                    <label>NIT</label>
                    <input type="text" name="contratista_nit" id="contratista_nit" placeholder="901.607.913-4" required>
                </div>

                <div class="campo">
                    <label>Representante Legal — Nombre </label>
                    <input type="text" name="rep_legal_nombre" id="rep_legal_nombre" required>
                </div>

                <div class="campo">
                    <label>Representante Legal — Cédula</label>
                    <input type="text" name="rep_legal_cc" id="rep_legal_cc" required>
                </div>

                <div class="campo">
                    <label>Representante Legal — Lugar de expedición</label>
                    <input type="text" name="rep_legal_lugar" id="rep_legal_lugar" placeholder="Bogotá D.C" required>
                </div>

                <div class="campo">
                    <label>Dirección contratista</label>
                    <input type="text" name="contratista_direccion" id="contratista_direccion" placeholder="Dirección"
                        required>
                </div>

                <div class="campo">
                    <label>Teléfono(s)</label>
                    <input type="text" name="contratista_telefonos" id="contratista_telefonos"
                        placeholder="3124307827 - 3223949580" required>
                </div>

                <div class="campo">
                    <label>Correo(s)</label>
                    <input type="text" name="contratista_correos" id="contratista_correos"
                        placeholder="mail@ejemplo.com; otro@ejemplo.com" required>
                </div>
            </fieldset>

            <!-- Valores y plazos -->
            <fieldset>
                <legend>Valores y Plazos</legend>

                <div class="campo">
                    <label>Valor en letras</label>
                    <!-- Visible deshabilitado -->
                    <input type="text" id="valor_contrato_letras_view" value="" disabled>
                    <!-- Oculto que sí se envía en el POST -->
                    <input type="hidden" name="valor_contrato_letras" id="valor_contrato_letras">
                </div>

                <div class="campo">
                    <label>Valor numérico</label>
                    <input type="text" name="valor_contrato_num" id="valor_contrato_num" placeholder="35.000.000"
                        required>
                </div>

                <div class="campo">
                    <label>Fecha de terminación</label>
                    <input type="date" name="fecha_terminacion" id="fecha_terminacion" required>
                </div>

                <div class="campo">
                    <label>Anexos</label>
                    <input type="text" name="anexos" id="anexos" placeholder="formato de informe de supervisión"
                        value="formato de informe de supervisión">
                </div>
            </fieldset>

            <!-- Firmas / Notificación / Vo. Bo. -->
            <fieldset>
                <legend>Firmas / Notificación / Visto Bueno</legend>

                <div class="campo">
                    <label>Nombre Ordenador </label>
                    <input type="text" name="ordenador_nombre" id="ordenador_nombre" placeholder="Teniente Coronel ..."
                        value="MARLON GÓMEZ RODRÍGUEZ" required>
                </div>

                <div class="campo">
                    <label>Cargo Ordenador </label>
                    <input type="text" name="ordenador_cargo" id="ordenador_cargo"
                        placeholder="ORDENADOR DEL GASTO – ..." value="Teniente Coronel" required>
                </div>

                <div class="campo">
                    <label>Cédula Supervisor</label>
                    <input type="text" name="supervisor_cc" id="supervisor_cc" required>
                </div>

                <div class="campo">
                    <label>Correo Supervisor</label>
                    <input type="email" name="supervisor_correo" id="supervisor_correo" required>
                </div>

                <div class="campo">
                    <label>Celular Supervisor</label>
                    <input type="tel" name="supervisor_celular" id="supervisor_celular" required>
                </div>

                <div class="campo">
                    <label>Visto Bueno — Nombre </label>
                    <input type="text" name="vb_nombre" id="vb_nombre" placeholder="PS. ABG. ..." value="PS. ABG. ..."
                        required>
                </div>

                <div class="campo">
                    <label>Visto Bueno — Cargo </label>
                    <input type="text" name="vb_cargo" id="vb_cargo" placeholder="ASESOR(A) JURÍDICO(A) ..."
                        value="ASESOR(A) JURÍDICO(A) ..." required>
                </div>
            </fieldset>

            <button type="submit" id="btn-generar" class="btn-guardar">📄 Generar ENTERADO</button>
            <div id="spinner" style="display: none; margin-top: 10px;">
                <div class="loader"></div>
                <p>Generando documento, por favor espere...</p>
            </div>
        </div>
    </form>

    <script>
    // Forzar MAYÚSCULAS en campos específicos
    ['objeto_contrato', 'contratista_razon_social', 'rep_legal_nombre',
        'valor_contrato_letras', 'ordenador_nombre', 'ordenador_cargo',
        'vb_nombre', 'vb_cargo'
    ].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', () => el.value = el.value.toUpperCase());
    });

    // Formato miles en valor numérico al perder foco
    const valorNum = document.getElementById('valor_contrato_num');
    if (valorNum) {
        valorNum.addEventListener('blur', () => {
            const raw = valorNum.value.replace(/[^\\d]/g, '');
            if (!raw) return;
            valorNum.value = new Intl.NumberFormat('es-CO').format(parseInt(raw, 10));
        });
    }

    // Spinner básico al enviar
    const form = document.querySelector('form.form-register');
    form.addEventListener('submit', (e) => {
        // Evita envío inmediato para simular el tiempo
        e.preventDefault();

        const spinner = document.getElementById('spinner');
        const btn = document.getElementById('btn-generar');

        spinner.style.display = 'block';
        btn.disabled = true;

        // Ocultar spinner después de 10 segundos
        setTimeout(() => {
            spinner.style.display = 'none';
            btn.disabled = false;

            // 🔥 Si quieres que aquí sí se envíe el form:
            form.submit();
        }, 2000); // 10000 ms = 10 segundos
    });
    </script>

    <script>
    // ===== Conversor número → letras (ES) =====
    function numeroALetrasES(num) {
        num = Number(num) || 0;
        if (num === 0) return "CERO DE PESOS M/CTE";

        function unidades(n) {
            const u = ["", "UNO", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE"];
            return u[n];
        }

        function decenas(n) {
            const d = ["", "DIEZ", "VEINTE", "TREINTA", "CUARENTA", "CINCUENTA", "SESENTA", "SETENTA", "OCHENTA",
                "NOVENTA"
            ];
            const e = ["ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO",
                "DIECINUEVE"
            ];
            if (n < 10) return unidades(n);
            if (n >= 11 && n <= 19) return e[n - 11];
            const dec = Math.floor(n / 10),
                uni = n % 10;
            if (n === 10) return "DIEZ";
            if (n === 20) return "VEINTE";
            if (n > 20 && n < 30) return "VEINTI" + (uni ? unidades(uni).toLowerCase() : "");
            return d[dec] + (uni ? " Y " + unidades(uni) : "");
        }

        function centenas(n) {
            const c = ["", "CIENTO", "DOSCIENTOS", "TRESCIENTOS", "CUATROCIENTOS", "QUINIENTOS", "SEISCIENTOS",
                "SETECIENTOS", "OCHOCIENTOS", "NOVECIENTOS"
            ];
            if (n === 100) return "CIEN";
            const cen = Math.floor(n / 100),
                resto = n % 100;
            return (cen ? c[cen] + (resto ? " " : "") : "") + (resto ? decenas(resto) : "");
        }

        function seccion(n, divisor, singular, plural) {
            const cientos = Math.floor(n / divisor);
            const resto = n - cientos * divisor;
            let texto = "";
            if (cientos > 0) texto = (cientos > 1 ? numero(cientos) + " " + plural : singular);
            return {
                texto,
                resto
            };
        }

        function miles(n) {
            const s = seccion(n, 1000, "MIL", "MIL");
            const texto = s.texto;
            const r = s.resto;
            const rtxt = r ? (r < 100 ? (texto ? " " : "") + decenas(r) : (texto ? " " : "") + centenas(r)) : "";
            return (texto + rtxt).trim();
        }

        function millones(n) {
            const s = seccion(n, 1000000, "UN MILLÓN", "MILLONES");
            const texto = s.texto;
            const r = s.resto;
            const rtxt = r ? (r < 1000 ? (texto ? " " : "") + (r < 100 ? decenas(r) : centenas(r)) :
                (texto ? " " : "") + miles(r)) : "";
            return (texto + rtxt).trim();
        }

        function milesMillones(n) {
            const s = seccion(n, 1000000000, "UN MIL MILLONES", "MIL MILLONES");
            const texto = s.texto;
            const r = s.resto;
            const rtxt = r ? (r < 1000000 ? (texto ? " " : "") + millones(r) : (texto ? " " : "") + millones(r)) : "";
            return (texto + rtxt).trim();
        }

        function numero(n) {
            if (n < 100) return decenas(n);
            if (n < 1000) return centenas(n);
            if (n < 1000000) return miles(n);
            if (n < 1000000000) return millones(n);
            return milesMillones(n);
        }

        const letras = numero(num);
        const moneda = (num === 1) ? "PESO M/CTE" : "DE PESOS M/CTE";
        return (letras + " " + moneda).toUpperCase();
    }

    // ===== Wiring con los inputs =====
    const numInput = document.getElementById("valor_contrato_num");
    const letrasView = document.getElementById("valor_contrato_letras_view");
    const letrasHidden = document.getElementById("valor_contrato_letras");

    function actualizarLetras() {
        const raw = (numInput.value || "").replace(/[^0-9]/g, "");
        if (!raw) {
            letrasView.value = "";
            letrasHidden.value = "";
            return;
        }
        const n = parseInt(raw, 10);
        const letras = numeroALetrasES(n);
        letrasView.value = letras;
        letrasHidden.value = letras;
    }

    if (numInput) {
        numInput.addEventListener("input", () => {
            // mientras escribe, calculamos
            actualizarLetras();
        });

        // al perder foco, formateo con miles
        numInput.addEventListener("blur", () => {
            const raw = (numInput.value || "").replace(/[^0-9]/g, "");
            if (!raw) return;
            numInput.value = new Intl.NumberFormat("es-CO").format(parseInt(raw, 10));
        });

        // inicial por si trae valor precargado
        actualizarLetras();
    }
    </script>
</body>

</html>