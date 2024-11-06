<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'artesano') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Panel del Artesano</title>
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
            max-width: 800px;
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

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        form label {
            font-weight: bold;
            color: #333;
        }

        form input[type="text"], form textarea, form input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form textarea {
            grid-column: span 2;
            resize: vertical;
            height: 100px;
        }

        form button {
            grid-column: span 2;
            padding: 10px;
            background-color: #D2691E;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        form button:hover {
            background-color: #c5571b;
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
    </style>
</head>
<body>
    <header>
        <h1>Bienvenido, Artesano</h1>
        <nav>
            <ul>
                <li><a href="artesano.php">Subir Producto</a></li>
                <li><a href="ver_productos.php">Ver Mis Productos</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Sube tus productos para vender</h2>
        <form action="subir_producto.php" method="post" enctype="multipart/form-data">
            <label for="nombre_producto">Nombre del Producto:</label>
            <input type="text" id="nombre_producto" name="nombre_producto" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>

            <label for="precio">Precio:</label>
            <input type="text" id="precio" name="precio" required>

            <label for="categoria">Categoría:</label>
            <input type="text" id="categoria" name="categoria" required>

            <label for="materiales">Materiales:</label>
            <input type="text" id="materiales" name="materiales" required>

            <label for="dimensiones">Dimensiones:</label>
            <input type="text" id="dimensiones" name="dimensiones" required>

            <label for="colores_disponibles">Colores Disponibles:</label>
            <input type="text" id="colores_disponibles" name="colores_disponibles" required>

            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" required>

            <label for="metodos_envio">Métodos de Envío:</label>
            <input type="text" id="metodos_envio" name="metodos_envio" required>

            <label for="garantia">Garantía:</label>
            <textarea id="garantia" name="garantia" required></textarea>

            <label for="imagen">Imagen del Producto:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*" required>

            <button type="submit">Subir Producto</button>
        </form>
        <a href="artesano.php" class="btn-back">Atrás</a>
    </main>
</body>
</html>
