<?php
session_start();
include('db.php'); // Conectar con la base de datos

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

// Obtener los datos del perfil del usuario
$sql = "SELECT * FROM Usuario WHERE ID_Usuario = '$id_usuario'";
$result = mysqli_query($conexion, $sql);

if (!$result) {
    die("Error en la consulta SQL: " . mysqli_error($conexion));
}

$perfil = mysqli_fetch_assoc($result);

// Asegúrate de usar las claves correctas, revisa la base de datos para confirmar los nombres
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <p><strong>Rol:</strong> <?php echo isset($perfil['Rol']) ? ucfirst($perfil['Rol']) : 'No disponible'; ?></p>

    <title>Mi Perfil - Manjares y Artes</title>
    <link rel="stylesheet" href="css/perfil.css">
    <style>
        /* Aquí irían tus estilos CSS */
    </style>
</head>
<body>
    <div class="container">
        <h1>Mi Perfil</h1>
        <p><strong>Nombre:</strong> <?php echo isset($perfil['nombre']) ? $perfil['nombre'] : 'No disponible'; ?></p>
        <p><strong>Correo Electrónico:</strong> <?php echo isset($perfil['correo_electronico']) ? $perfil['correo_electronico'] : 'No disponible'; ?></p>
        <p><strong>Dirección:</strong> <?php echo isset($perfil['direccion']) ? $perfil['direccion'] : 'No disponible'; ?></p>
        <p><strong>Teléfono:</strong> <?php echo isset($perfil['telefono']) ? $perfil['telefono'] : 'No disponible'; ?></p>
        <p><strong>Rol:</strong> <?php echo isset($perfil['rol']) ? ucfirst($perfil['rol']) : 'No disponible'; ?></p>
        
        <h2>Historial de Compras</h2>
        <div class="historial-compras">
            <table>
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
                <!-- Aquí puedes agregar código PHP para mostrar el historial de compras desde la base de datos -->
            </table>
        </div>
        
        <a href="home.php" class="btn-volver">Volver</a>
    </div>
</body>
</html>
