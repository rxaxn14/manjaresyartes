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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mi Perfil - Manjares y Artes</title>

    <!-- Enlace a Bootstrap 4 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        /* Estilos personalizados */
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 900px;
            margin-top: 30px;
        }

        .perfil-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .perfil-header img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 30px;
        }

        .perfil-header h1 {
            font-size: 36px;
            color: #007bff;
        }

        .perfil-header p {
            font-size: 18px;
            color: #6c757d;
        }

        .btn-volver {
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            display: inline-block;
            margin-top: 20px;
        }

        .btn-volver:hover {
            background-color: #0056b3;
            color: white;
        }

        .historial-compras table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .historial-compras th, .historial-compras td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .historial-compras th {
            background-color: #007bff;
            color: white;
        }

        .historial-compras tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="perfil-header">
        <!-- Foto de perfil -->
        <img src="imagenes/rox.jpeg" alt="Foto de Perfil">
        <div>
            <h1>Mi Perfil</h1>
            <p><strong>Nombre:</strong> <?php echo isset($perfil['nombre']) ? $perfil['nombre'] : 'No disponible'; ?></p>
            <p><strong>Correo Electrónico:</strong> <?php echo isset($perfil['correo_electronico']) ? $perfil['correo_electronico'] : 'No disponible'; ?></p>
            <p><strong>Dirección:</strong> <?php echo isset($perfil['direccion']) ? $perfil['direccion'] : 'No disponible'; ?></p>
            <p><strong>Teléfono:</strong> <?php echo isset($perfil['telefono']) ? $perfil['telefono'] : 'No disponible'; ?></p>
            <p><strong>Rol:</strong> <?php echo isset($perfil['Rol']) ? ucfirst($perfil['Rol']) : 'No disponible'; ?></p>
        </div>
    </div>

    <h2>Historial de Compras</h2>
    <div class="historial-compras">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí puedes agregar código PHP para mostrar el historial de compras desde la base de datos -->
                <tr>
                    <td>2024-11-01</td>
                    <td>Producto Ejemplo</td>
                    <td>2</td>
                    <td>Bs. 200</td>
                </tr>
                <tr>
                    <td>2024-11-05</td>
                    <td>Producto Otro Ejemplo</td>
                    <td>1</td>
                    <td>Bs. 150</td>
                </tr>
                <!-- Agregar más filas dinámicamente -->
            </tbody>
        </table>
    </div>

    <a href="home.php" class="btn-volver">Volver</a>
</div>

<!-- Enlace a jQuery y Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
