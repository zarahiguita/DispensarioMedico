<?php
$cedula = $_GET['cedula'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="UTF-8">
    <title>Carga del contrato firmado</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            background-color: white;
            height: 100vh;
        }

     .contenido {
    display: flex;
    flex-direction: column;
    align-items: center; /* Cambiado de flex-start a center */
    justify-content: center;
    height: 50vh;
    /* padding-left eliminado */
}

.titulo {
    font-size: 60px;
    font-weight: bold;
    color: #AF1415;
    text-shadow: -2px -2px 0 #ffffff, 2px -2px 0 #ffffff, -2px 2px 0 #ffffff, 2px 2px 0 #ffffff;
    margin-bottom: 50px;
    font-family: 'Arial Black', Gadget, sans-serif;
    text-align: center; /* añadido para centrar internamente */
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

.img-left,
.img-right {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 400px; /* Tamaño aumentado */
  height: auto;
}

.img-left {
  left: 120px; /* Ajusta la distancia del formulario */
}

.img-right {
  right: 90px; /* Ajusta la distancia del formulario */
}

    </style>
</head>
<body>

<div class="contenido">
    <div class="titulo">SUBIR CONTRATO FIRMADO</div>

    <div class="container">
        <p class="nota"> Aqui vas a poder subir y guardar el contrato de prestación de servicios ya firmado. Solo se permiten archivos WORD y PDF</p>
        <form method="POST" action="procesar_contrato.php" enctype="multipart/form-data" onsubmit="mostrarSpinner()">
            
        <!-- Imagen izquierda -->
	<img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
	
	<!-- Imagen derecha -->
	<img src="../imagenes/logo2.png" alt="Logo" class="img-right">

    
        <input type="file" name="archivo" accept=".doc,.docx,.pdf" required>
          <input type="hidden" name="cedula" value="<?= htmlspecialchars($cedula) ?>">

            <div class="submit-group">
                <input type="submit" name="submit" value="Subir">
                <div class="spinner" id="spinner"></div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<form id="formPolizaRedirect" method="GET" action="../vista/formulario_poliza.php" style="display: none;">
    <input type="hidden" name="cedula" value="<?= htmlspecialchars($cedula) ?>">
</form>
<script>
    function mostrarSpinner() {
        document.getElementById('spinner').style.display = 'inline-block';
    }

    // Función para obtener parámetros de la URL
   const cedula = '<?= $cedula ?>';

    // Función para obtener parámetros de la URL
    function getParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    if (getParam('exito') === '1') {
        const archivo = decodeURIComponent(getParam('archivo') || 'el archivo');

        Swal.fire({
            icon: 'success',
            title: '¡Archivo subido correctamente!',
            html: `<b>El archivo <u>${archivo}</u> fue cargado correctamente.</b><br><br>¿Desea continuar con el ingreso de la póliza?`,
            showCancelButton: true,
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Volver',
            confirmButtonColor: '#AF1415',
            cancelButtonColor: '#999999',
            background: '#f0f9ff',
            color: '#1e2a38',
            backdrop: '#AF1415'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirigir con la cédula como parámetro en la URL
              document.getElementById('formPolizaRedirect').submit();
            } else {
                window.location.href = 'subir_contrato.php';
            }
        });
    }
</script>
</body>
</html>
