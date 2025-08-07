<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingreso de usuarios</title>
    <link rel="stylesheet" type="text/css" href="">
    <style>
        .imagen-container {
            width: 77%;
            height: calc(100vh - 60px); /* Reduce la altura para no tapar el título */
            background: url('../imagenes/logoprinc.png') no-repeat center center;
            background-size: cover;
            position: fixed;
            left: 0;
            top: 60px; /* Baja la imagen */
        }

    
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: flex-end; 
            height: 100vh;
            background-color:white ; 
        }

        .titulo_principal {
            position: relative;
            top: 10px; /* Mantiene el título en la parte superior */
            width: 100%;
            text-align: center;
            color: #AF1415;
            font-size: 1.8em;
            font-weight: bold;
        }

        .form-register {
    background-color:#AF1415;
    padding: 15px;
    border-radius: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 400px;
    
    margin-top: 60px;
}

        .form__titlo {
            font-size: 1.5em;
            margin-bottom: 20px;
            color: white; 
        }

        .contenedor-inputs {
            display: flex;
            flex-direction: column;
        }

        .input-48, .input-100 {
            padding: 10px;
            margin: 10px 0;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn_enviar {
            padding: 10px;
            font-size: 1.1em;
            background-color:rgb(10, 5, 5);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn_enviar:hover {
            background-color: #rgb(34, 28, 13);
        }
    </style>
</head>
<body>

<h1 class="titulo_principal">HOSPITAL MILITAR DE MEDELLÍN (HOMME)</h1>
    <div class="imagen-container"></div>

   
    <div class="separator"></div>

   
    <form action="../bd/login.php" method="post" class="form-register">
        <h2 class="form__titlo">Inicio de sesión</h2>
        <div class="contenedor-inputs">
            <input type="text" name="usuario" placeholder="usuario" class="input-48" required>
            <input type="password" name="contrasena" placeholder="contraseña" class="input-100" required><br>
            <input type="submit" value="Ingresar" class="btn_enviar"> <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            <input type="submit" name="registro" value="Registrar">
            <input type="submit" name="registro" value="Olvido su contraseña? presiona aqui">
        </div>
    </form>

</body>
</html>


