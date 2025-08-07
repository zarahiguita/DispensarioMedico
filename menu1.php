<!DOCTYPE html>
<html lang="es">
<head>
    <title>Menú Segundario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <base href="http://localhost/practicas/">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color:rgba(255, 255, 255, 0.4);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            position: relative;
            color: black;
            overflow-x: hidden;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Estilos para imágenes laterales de pantalla completa */
        .imagen-lateral {
            position: fixed;
            top: 0;
            width: 100px;
            height: 100vh;
            object-fit: cover;
        }

        .izquierda {
            left: 0;
        }

        .derecha {
            right: 0;
        }

        .imagen-superior {
            text-align: center;
            background-color: white;
            padding: 10px;
            width: 100%;
        }

        .imagen-superior img {
            width: 250px;
            height: auto;
            animation: fadeIn 1.5s ease-in-out;
        }

        /* Estilos del menú */
        .menu {
            background-color: #AF1415;
            padding: 10px;
            width: 100%;
            text-align: center;
        }

        .menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .menu li {
            position: relative;
        }


        .menu a {
            background-color: #AF1415;
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
            padding: 12px 18px;
            border-radius: 8px;
            display: inline-block;
            transition: 0.3s;
            position: relative;
            overflow: hidden;
        }

        .menu a:hover {
            background-color: #AF1415;
            transform: scale(1.1);
        }

        .submenu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #AF1415;
            padding: 10px;
            border-radius: 8px;
            text-align: left;
            min-width: 200px;
        }

        .submenu a {
            display: block;
            padding: 8px;
            font-size: 16px;
            border-radius: 5px;
        }

        .submenu a:hover {
            background-color: #AF1415;
        }

        .menu li:hover .submenu {
            display: block;
        }



        /* Contenedor de imágenes */
        .imagen-container {
            width: 350px;
            height: 350px;
            padding: 10px;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
        }

        .imagen-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .imagen-container img:hover {
            transform: scale(1.05);
        }

        /* Barra de búsqueda */
        .busqueda {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
            animation: fadeIn 2s ease-in-out;
        }

        .busqueda input {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #AF1415;
            background-color: #ffffff;
            color: black;
            width: 250px;
        }

        .busqueda button {
            background-color:#AF1415;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .busqueda button:hover {
            background-color:#AF1415;
        }
    </style>
</head>
<body>

    <img class="imagen-lateral izquierda" src="./imagenes/lateral1.png" alt="Imagen Izquierda">
    <img class="imagen-lateral derecha" src="./imagenes/lateral2.png" alt="Imagen Derecha">

    <header>
        <div class="imagen-superior">
            <img src="./imagenes/logo4.png" alt="Logo Superior">
        </div>
        <nav class="menu">
            <ul>
                <li>
                    <a href="">Formularios de ingreso</a>
                    <div class="submenu">
                        <a href="vista/form_ordenador.php">Ingreso de Ordenador del gasto</a>
                        <a href="vista/form_asesor.php">Ingreso de Asesor juridico</a>
                        <a href="vista/form_ejecucion.php">Ingreso de Lugar de ejecución</a>
                        <a href="vista/supervisor.php">Ingreso de Supervisor</a>
                        <a href="vista/form_unidad.php">Ingreso de Unidad</a>
                        <a href="vista/form_rubro.php">Ingreso de Rubro</a>
                        <a href="vista/nombre_banco.php">Ingreso de Banco</a>   
                        <a href="vista/form_objeto.php">Ingreso de Objeto</a>   
                        <a href="vista/form_aseguradora.php">Ingreso de Aseguradora de Cumplimiento</a>   
                        <a href="vista/form_aseguradora_respo.php">Ingreso de Aseguradora de Responsabilidad</a>
                        <a href="vista/formulario_cep.php">Ingreso de CEP</a>
                        <a href="vista/formulario_rp.php">Ingreso de RP</a>   
                    
                        
                    </div>
                </li>
                <li><a href="vista/formulario_estudios.php">Formulario de estudios previos</a></li>
                <li><a href="vista/formulario_contrato.php">Contrato de prestación de servicios</a></li>
                <li><a href="vista/excel.php">Base de datos</a></li>

            </ul>
        </nav>
    </header>

    <div class="imagen-container">
        <img src="./imagenes/logo2.png" alt="Cruz">
        <img src="./imagenes/ejercito.png" alt="Ejercito">
    </div>

    <div class="busqueda">
    <a href="vista/formulario_busqueda.php">
        <button>Buscar empleado</button>
    </a>
</div>

</body>
</html>

<?php /*
include("../conexion/cn.php");
$sql = "";
$resultado = $conexion->query($sql);
$conexion->close();*/
?>
