<?php
$archivo = isset($_GET['archivo']) ? $_GET['archivo'] : '';
$ruta_archivo = 'contratos_generados/' . $archivo;
$existeArchivo = file_exists($ruta_archivo);
$nombre_usuario = isset($_GET['nombre_completo']) ? $_GET['nombre_completo'] : 'Usuario';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato Generado</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #AF1415;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
        }

        h1 {
            color: rgb(34, 28, 13);
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #444;
            margin-bottom: 30px;
        }

        .btn {
            background-color: rgb(34, 28, 13);
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #6B8E23;
        }

        .error {
            color: red;
            font-weight: bold;
        }
        .container p {
    color: white;
}
.img-left,
.img-right {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 320px;
  height: auto;
}

.img-left {
  left: 200px;
}

.img-right {
  right: 150px;
}
.volver-btn {
    margin-top: 60px;
    text-align: center;
    width: 100%;
}

    </style>
</head>
<body>
    <img src="../imagenes/ejercito.png" alt="Ejército" class="img-left">
	
	<!-- Imagen derecha -->
	<img src="../imagenes/logo2.png" alt="Logo" class="img-right">

 <div class="main-wrapper">
    <div class="container">
        <?php if ($archivo && $existeArchivo): ?>
            <h1>✅ Contrato generado correctamente a nombre de <?= htmlspecialchars($nombre_usuario) ?></h1>
            <p>Haz clic en el siguiente botón para descargar el archivo:</p>
            <a href="<?= $ruta_archivo ?>" download class="btn">📄 Descargar Contrato</a>
        <?php else: ?>
            <h1 class="error">❌ Error</h1>
            <p class="error">No se encontró el archivo en la ruta esperada:<br><code><?= $ruta_archivo ?></code></p>
        <?php endif; ?>
    </div>

    <!-- Botón Volver debajo y centrado -->
    <div class="volver-btn">
        <a href="formulario_contrato.php" class="btn">🔙 Volver</a>
    </div>
</div>
