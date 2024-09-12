<?php
include('db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    // Consulta para verificar usuario y contraseña
    $consulta = "SELECT * FROM usuarios WHERE usuario='$usuario' AND contraseña='$contraseña'";
    $resultado = mysqli_query($conexion, $consulta);
    $filas = mysqli_fetch_assoc($resultado);

    if ($filas) {
        // Guardamos la sesión del usuario
        $_SESSION['usuario'] = $filas['usuario'];
        $_SESSION['rol'] = $filas['rol'];

        // Redirigimos según el rol
        if ($filas['rol'] == 'administrador') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: home.php");
        }
        exit();
    } else {
        echo "<h1>Error de autenticación. Usuario o contraseña incorrectos.</h1>";
    }
}

mysqli_close($conexion);
?>
