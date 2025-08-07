<?php
$cedula = isset($_GET['cedula']) ? htmlspecialchars($_GET['cedula']) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Renovación de Contrato</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: white;
            padding: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .contenedor-central {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 1000px;
        }

        form {
            background-color: #AF1415;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            z-index: 1;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #F0E68C;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 96%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .boton {
            width: 100%;
            background-color: rgb(34, 28, 13);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            font-weight: bold;
        }

        .boton:hover {
            background-color: #6B8E23;
        }

        .img-left,
        .img-right {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 350px;
            height: auto;
            z-index: 0;
        }

        .img-left {
            left: -200px;
        }

        .img-right {
            right: -250px;
        }

        #spinner {
            display: none;
            text-align: center;
            margin-top: 20px;
        }

        .loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #4B5320;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 0.9s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        #spinner p {
            font-weight: bold;
            color: #F0E68C;
            margin-top: 8px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            width: 100%;
            max-width: 500px;
            color: #AF1415;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Renovar Contrato para Cédula: <?= $cedula ?></h2>

<div class="contenedor-central">
    <img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
    <img src="../imagenes/logo2.png" alt="Logo" class="img-right">

    <form id="form-renovacion" action="procesar_excel2.php" method="POST" target="iframeDescarga">
        <input type="hidden" name="cedula" value="<?= $cedula ?>">

        <label for="fecha_inicio">Fecha de Inicio</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" required>

        <label for="fecha_fin">Fecha de Fin</label>
        <input type="date" name="fecha_fin" id="fecha_fin" required>

        <label for="meses">Meses Contratados</label>
        <input type="number" name="meses" id="meses" required readonly>

        <label for="valor_mensual">Valor Pago Mensual</label>
        <input type="text" name="valor_mensual" id="valor_mensual" required>

        <label for="valor_total">Valor Total del Contrato</label>
        <input type="text" name="valor_total" id="valor_total" required>

        <button type="submit" class="boton" id="btn-generar">📝 Generar</button>

        <div id="spinner">
            <div class="loader"></div>
            <p>Generando archivo...</p>
        </div>
    </form>
</div>

<iframe name="iframeDescarga" style="display:none;"></iframe>

<script>
const fechaInicio = document.getElementById("fecha_inicio");
const fechaFin = document.getElementById("fecha_fin");
const meses = document.getElementById("meses");
const valorTotal = document.getElementById("valor_total");
const valorMensual = document.getElementById("valor_mensual");

function calcularMeses() {
    if (fechaInicio.value && fechaFin.value) {
        const inicio = new Date(fechaInicio.value);
        const fin = new Date(fechaFin.value);

        if (fin < inicio) {
            meses.value = 0;
            return;
        }

        let years = fin.getFullYear() - inicio.getFullYear();
        let months = fin.getMonth() - inicio.getMonth();
        let totalMonths = years * 12 + months;

        if (fin.getDate() < inicio.getDate()) {
            totalMonths -= 1;
        }

        meses.value = totalMonths >= 0 ? totalMonths : 0;
        calcularValores();
    }
}

function parseValor(valor) {
    return parseInt(valor.replace(/\D/g, '')) || 0;
}

function formatearPesos(valor) {
    return "$" + valor.toLocaleString("es-CO");
}

function calcularValores() {
    const mesesVal = parseInt(meses.value) || 0;
    const total = parseValor(valorTotal.value);
    const mensual = parseValor(valorMensual.value);

    if (document.activeElement === valorTotal && mesesVal) {
        valorMensual.value = formatearPesos(Math.floor(total / mesesVal));
    } else if (document.activeElement === valorMensual && mesesVal) {
        valorTotal.value = formatearPesos(mensual * mesesVal);
    }
}

[fechaInicio, fechaFin].forEach(input => {
    input.addEventListener("change", calcularMeses);
});

[valorTotal, valorMensual].forEach(input => {
    input.addEventListener("input", calcularValores);
    input.addEventListener("blur", () => {
        const valor = parseValor(input.value);
        input.value = valor ? formatearPesos(valor) : "";
    });
    input.addEventListener("focus", () => {
        input.value = parseValor(input.value) || "";
    });
});

const form = document.getElementById('form-renovacion');
const spinner = document.getElementById('spinner');
const btn = document.getElementById('btn-generar');

function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? match[2] : null;
}

form.addEventListener('submit', function () {
    valorTotal.value = parseValor(valorTotal.value);    
    valorMensual.value = parseValor(valorMensual.value);

    spinner.style.display = 'block';
    btn.disabled = true;

    const interval = setInterval(() => {
        if (getCookie("descarga_completa") === "1") {
            clearInterval(interval);
            spinner.style.display = 'none';
            btn.disabled = false;
            document.cookie = "descarga_completa=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        }
    }, 1000);
});
</script>

</body>
</html>
