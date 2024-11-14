<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "bd_artesanias");

if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Verificar si el formulario de edición fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar"])) {
    $ID_producto = $_POST["ID_producto"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $stock = $_POST["stock"];
    $precio = $_POST["precio"];
    $categoria = $_POST["categoria"];
    $materiales = $_POST["materiales"];
    $dimensiones = $_POST["dimensiones"];
    $colores_disponibles = $_POST["colores_disponibles"];

    // Actualizar los datos del producto
    $query_update = "UPDATE producto SET 
                        nombre='$nombre',
                        descripcion='$descripcion',
                        stock=$stock,
                        precio=$precio,
                        categoria='$categoria',
                        materiales='$materiales',
                        dimensiones='$dimensiones',
                        colores_disponibles='$colores_disponibles'
                    WHERE ID_producto=$ID_producto";
                    
    if ($conexion->query($query_update) === TRUE) {
        echo "Producto actualizado correctamente.";
        // Redirigir al panel inicial después de guardar
        header("Location: edit_product.php");
        exit();
    } else {
        echo "Error al actualizar el producto: " . $conexion->error;
    }
}

// Obtener todos los productos
$query_productos = "SELECT * FROM producto";
$resultado = $conexion->query($query_productos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            color: #333;
        }
        h2 {
            color: #0e4d92;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #0e4d92;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #0e4d92;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f9ff;
        }
        button {
            padding: 8px 16px;
            margin: 5px;
            border: none;
            color: #fff;
            cursor: pointer;
        }
        button[type="submit"] {
            background-color: #4caf50;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        .volver-btn {
            background-color: #ff5722;
            text-decoration: none;
        }
        .volver-btn:hover {
            background-color: #e64a19;
        }
        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 8px;
            margin: 4px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<h2>Lista de Productos</h2>

<?php if (!isset($_GET["ID_producto"])): ?>
<!-- Mostrar la lista solo si no se está editando un producto -->
<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Stock</th>
        <th>Precio</th>
        <th>Categoría</th>
        <th>Materiales</th>
        <th>Dimensiones</th>
        <th>Colores Disponibles</th>
        <th>Acciones</th>
    </tr>
    <?php while ($row = $resultado->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['ID_producto']; ?></td>
        <td><?php echo $row['nombre']; ?></td>
        <td><?php echo $row['descripcion']; ?></td>
        <td><?php echo $row['stock']; ?></td>
        <td><?php echo $row['precio']; ?></td>
        <td><?php echo $row['categoria']; ?></td>
        <td><?php echo $row['materiales']; ?></td>
        <td><?php echo $row['dimensiones']; ?></td>
        <td><?php echo $row['colores_disponibles']; ?></td>
        <td>
            <form action="edit_product.php" method="GET" style="display:inline;">
                <input type="hidden" name="ID_producto" value="<?php echo $row['ID_producto']; ?>">
                <button type="submit" name="edit">Editar</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>
<?php endif; ?>

<?php
// Mostrar formulario de edición si se seleccionó un producto para editar
if (isset($_GET["ID_producto"])) {
    $ID_producto = $_GET["ID_producto"];
    $query_producto = "SELECT * FROM producto WHERE ID_producto=$ID_producto";
    $producto = $conexion->query($query_producto)->fetch_assoc();
    ?>
    <h2>Editar Producto</h2>
    <form action="edit_product.php" method="POST">
        <input type="hidden" name="ID_producto" value="<?php echo $producto['ID_producto']; ?>">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?php echo $producto['nombre']; ?>"><br>
        <label>Descripción:</label><br>
        <textarea name="descripcion"><?php echo $producto['descripcion']; ?></textarea><br>
        <label>Stock:</label><br>
        <input type="number" name="stock" value="<?php echo $producto['stock']; ?>"><br>
        <label>Precio:</label><br>
        <input type="number" step="0.01" name="precio" value="<?php echo $producto['precio']; ?>"><br>
        <label>Categoría:</label><br>
        <input type="text" name="categoria" value="<?php echo $producto['categoria']; ?>"><br>
        <label>Materiales:</label><br>
        <input type="text" name="materiales" value="<?php echo $producto['materiales']; ?>"><br>
        <label>Dimensiones:</label><br>
        <input type="text" name="dimensiones" value="<?php echo $producto['dimensiones']; ?>"><br>
        <label>Colores Disponibles:</label><br>
        <input type="text" name="colores_disponibles" value="<?php echo $producto['colores_disponibles']; ?>"><br><br>
        <button type="submit" name="editar">Guardar Cambios</button>
        <a href="dashboard.php" class="volver-btn">Volver</a>
    </form>
<?php
}
?>

</body>
</html>

<?php
$conexion->close();
?>
