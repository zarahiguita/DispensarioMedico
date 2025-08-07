<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Empleado</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            display: flex;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            overflow: hidden;
            width: 80%;
            max-width: 900px;
        }
        .form-container {
            padding: 40px;
            max-width: 350px;
            text-align: center;
        }
        .form-container h2 {
            color: #AF1415;
            margin-bottom: 25px;
        }
        .form-container p {
            font-size: 16px;
            color: #333;
            margin-bottom: 25px;
            line-height: 1.5;
        }
        .form-container input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 16px;
            border: 2px solid #AF1415;
            border-radius: 8px;
            box-sizing: border-box;
        }
        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #AF1415;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color .3s;
        }
        .form-container button:hover {
            background-color: #8c0f10;
        }
        .image-container {
            width: 400px;
            background-color: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
            display: block;
        }
        @media (max-width: 800px) {
            .container {
                flex-direction: column;
            }
            .image-container {
                width: 100%;
                padding: 20px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
       
        <div class="form-container">
            <h2>Buscar Empleado </h2>
            <p>
                Bienvenido al sistema de gestión de empleados. A través de este formulario podrás buscar la información de los empleados mediante su cédula de ciudadania. <br><br>
                Introduce la cédula para ver los detalles de su contrato, incluyendo el estado de su contrato y la opción de renovación.
            </p>
            <form action="mostrar_empleado.php" method="get">
                <input type="text" name="cedula" placeholder="Ingrese cédula del empleado" required>
                <button type="submit">Buscar</button>
            </form>
        </div>
        
        <div class="image-container">
            <img src="../imagenes/imagen1.png" alt="Imagen decorativa">
        </div>
    </div>
</body>
</html>
