<?php
$conexion = mysqli_connect("localhost", "root", "", "bd_artesanias");

if (!$conexion) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}
?>
