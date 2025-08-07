<?php

include("../conexion/cn.php");


  session_start();

  $usuario = $_POST['usuario'];
  $contrasena = $_POST['contrasena'];


  if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
  }

  $sql = "SELECT * FROM `login` WHERE usuario = '$usuario' AND contrasena = '$contrasena'";
  $resultado = mysqli_query($conexion, $sql);

  if (mysqli_num_rows($resultado) > 0) {
    $fila = mysqli_fetch_assoc($resultado);
    $_SESSION['usuario'] = $fila['usuario'];
    header("Location: ../menu1.php");
  } else {
    header("Location: ../vista/index.php");
  }

  mysqli_close($conexion);