<?php
session_start();
include('db.php'); // Conectar con la base de datos

// Verificar si el usuario está logueado y tiene rol de artesano
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'artesano') {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

// Consultar los productos subidos por el artesano
$sql = "SELECT * FROM producto WHERE id_artesano = '$id_usuario'";
$resultado = mysqli_query($conexion, $sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Productos - Artesano</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #D2691E;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            text-align: center;
        }

        nav ul li {
            display: inline;
            margin: 0 10px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #c5571b;
        }

        main {
            max-width: 900px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #D2691E;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #D2691E;
            color: white;
        }

        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background-color: #D2691E;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            display: block;
            text-align: center;
        }

        .btn-back:hover {
            background-color: #c5571b;
        }

        .actions a {
            padding: 5px 10px;
            background-color: #c5571b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px;
        }

        .actions a:hover {
            background-color: #a04415;
        }
    </style>
</head>
<body>
    <header>
        <h1>Mis Productos</h1>
        <nav>
            <ul>
                <li><a href="artesano.php">Subir Producto</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Lista de Productos</h2>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
            <?php
            if (mysqli_num_rows($resultado) > 0) {
                while ($producto = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>";
                    echo "<td>" . $producto['nombre'] . "</td>";
                    echo "<td>" . $producto['descripcion'] . "</td>";
                    echo "<td>Bs" . number_format($producto['precio'], 2) . "</td>";
                    echo "<td>" . $producto['stock'] . "</td>";
                    echo "<td>" . $producto['categoria'] . "</td>";
                    echo "<td class='actions'>";
                    echo "<a href='editar_producto.php?id=" . $producto['ID_producto'] . "'>Editar</a>";
                    echo "<a href='eliminar_producto.php?id=" . $producto['ID_producto'] . "'>Eliminar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No has subido ningún producto.</td></tr>";
            }
            ?>
        </table>
        <a href="artesano.php" class="btn-back">Atrás</a>
    </main>
</body>
</html>

<?php
mysqli_close($conexion);
?>
