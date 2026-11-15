<?php
$host = "localhost";
$user = "root";
$pass = "";
$baseDatos = "mascotopia_tienda";
$puerto = "3306";

$conexion = mysqli_connect($host, $user, $pass, $baseDatos, $puerto);

if (!$conexion) {
    die("Error al conectar... " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8mb4");
?>