<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carga de base de datos</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url('../imagenes/soldado2.jpeg');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            background-color: #f0f0f0;
            height: 100vh;
        }

        .contenido {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            height: 100vh;
            padding-left: 9cm;
        }

        .titulo {
            font-size: 48px;
            font-weight: bold;
            color:#AF1415;
            text-shadow: -2px -2px 0 #ffffff, 2px -2px 0 #ffffff, -2px 2px 0 #ffffff, 2px 2px 0 #ffffff;
            margin-bottom: 50px;
            font-family: 'Arial Black', Gadget, sans-serif;
        }

        .container {
            background-color: #AF1415;
            color: white;
            width: 450px;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }

        .nota {
            font-size: 14px;
            margin-bottom: 20px;
            color: #ffdada;
        }

        input[type="file"] {
            margin: 20px 0;
            color: white;
        }

        .submit-group {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        input[type="submit"] {
            background-color: white;
            color: #AF1415;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #e0e0e0;
        }

        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<div class="contenido">
    <div class="titulo">BASE DE DATOS</div>

    <div class="container">
        <p class="nota">- Ten en cuenta que si cargas este archivo reemplazará el anterior.<br>- El sistema solo admite archivos de Excel.</p>
        <form method="POST" action="procesar_excel.php" enctype="multipart/form-data" onsubmit="mostrarSpinner()">
            <input type="file" name="archivo" accept=".xls,.xlsx" required><br>

            <div class="submit-group">
                <input type="submit" name="submit" value="Subir">
                <div class="spinner" id="spinner"></div>
            </div>
        </form>
    </div>
</div>

<script>
    function mostrarSpinner() {
        document.getElementById('spinner').style.display = 'inline-block';
    }

    if (window.location.search.includes('exito=1')) {
        alert('Archivo subido correctamente.');
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>

</body>
</html>
