<?php
session_start(); // Iniciar la sesión

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd_artesanias";

// Crear la conexión
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error al conectar con la base de datos: " . $conexion->connect_error);
}

// Verificar si se ha añadido o eliminado un producto al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['agregar_carrito'])) {
        $id_producto = $_POST['id_producto'];
        $cantidad = 1; // Se agrega un producto al carrito

        // Reducir el stock en la base de datos
        $sql_update = "UPDATE producto SET stock = stock - ? WHERE id_producto = ? AND stock > 0";
        $stmt = $conexion->prepare($sql_update);
        $stmt->bind_param('ii', $cantidad, $id_producto);
        $stmt->execute();
        $stmt->close();

        // Añadir el producto al carrito (sesión)
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        $_SESSION['carrito'][$id_producto] = isset($_SESSION['carrito'][$id_producto]) ? $_SESSION['carrito'][$id_producto] + $cantidad : $cantidad;

        // Redirigir a la sección de carrito de compras
        header("Location: Productos.php#cart");
        exit();         
    }

    if (isset($_POST['eliminar_carrito'])) {
        $id_producto = $_POST['id_producto'];
        $cantidad = $_SESSION['carrito'][$id_producto];

        // Aumentar el stock en la base de datos
        $sql_update = "UPDATE producto SET stock = stock + ? WHERE id_producto = ?";
        $stmt = $conexion->prepare($sql_update);
        $stmt->bind_param('ii', $cantidad, $id_producto);
        $stmt->execute();
        $stmt->close();

        // Eliminar el producto del carrito
        unset($_SESSION['carrito'][$id_producto]);

        // Redirigir a la sección de carrito de compras
        header("Location: Productos.php#cart");
        exit();
    }
}

// Consulta SQL corregida
$sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.stock, p.categoria, p.materiales, p.dimensiones, p.colores_disponibles, 
        p.descuentos, p.metodos_envio, p.garantia, p.etiquetas, p.certificaciones, a.ID_usuario AS id_artesano, u.nombre AS nombre_artesano, c.nombre AS comunidad
        FROM producto p
        JOIN artesano a ON p.ID_artesano = a.ID_usuario
        JOIN usuario u ON a.ID_usuario = u.id_usuario
        LEFT JOIN pertenece pe ON u.id_usuario = pe.ID_usuario
        LEFT JOIN comunidad c ON pe.ID_comunidad = c.id_comunidad";

// Ejecutar la consulta
$resultado = $conexion->query($sql);

// Verificar si la consulta fue exitosa
if (!$resultado) {
    echo "Error en la consulta SQL: " . $conexion->error;
    exit(); // Detener la ejecución si hay un error
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Tienda en Línea</title>
    <link rel="stylesheet" href="css/Productos.css">
    <style>
        /* Estilos personalizados */
        .product-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 450px;
            height: 750px; /* Ajustar el tamaño del cuadro del producto */
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 420px; /* Ajustar el tamaño de la imagen */
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .product-info {
            text-align: left;
            padding-left: 15px;
        }

        .product-info h4 {
            font-size: 24px;
            margin-bottom: 15px;
            text-align: center;
        }

        .product-info p {
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
        }

        .product-info p span {
            font-weight: bold;
            color: #b58350; /* Títulos en otro color */
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* Mostrar dos productos por fila */
            gap: 30px;
            width: 100%;
        }

        .add-to-cart-btn {
            margin-top: 10px;
            background-color: #db9e61;
            border: none;
            padding: 10px 15px;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-to-cart-btn:hover {
            background-color: #b58350;
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
    <div class="logo">
        <img src="logo1.jpg" alt="Logo de Manjares y Artes">
    </div>
    <nav>
        <ul>
            <li><a href="home.php">Inicio</a></li>
            <li><a href="Productos.php">Productos</a></li>
            <li><a href="#">Nosotros</a></li>
            <li><a href="#">Contacto</a></li>
            <?php if (!isset($_SESSION['usuario_id'])): ?>
                <li><a href="login.php">Iniciar Sesión</a></li>
                <li><a href="register.php">Registrarse</a></li>
            <?php else: ?>
                <li><a href="perfil.php">Mi Perfil</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<!-- Sección de Productos -->
<div class="main-container">
    <div class="products-container">
        <h1>Sección de Productos</h1>
        <div class="products-grid">
            <?php
            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<div class='product-card'>"; // Contenedor del producto

                    // Título del producto
                    echo "<h2>" . $fila["nombre"] . "</h2>";

                    // Cuadro que contiene la imagen
                    echo "<div class='image-container'>";
                    $nombre_imagen = $fila["nombre"] . ".jpg"; // Cambiado a .jpg según tu solicitud
                    $ruta_imagen = "Imagenes/" . $nombre_imagen;

                    if (file_exists($ruta_imagen)) {
                        echo "<img src='" . $ruta_imagen . "' class='product-image' alt='" . $fila["nombre"] . "'>";
                    } else {
                        echo "<img src='https://via.placeholder.com/250' class='product-image' alt='Imagen por defecto'>";
                    }
                    echo "</div>"; // Cierre del contenedor de la imagen

                    // Información del producto
                    echo "<div class='product-info'>";
                    echo "<p><span>Descripción:</span> " . $fila["descripcion"] . "</p>";
                    echo "<p><span>Materiales:</span> " . $fila["materiales"] . "</p>";
                    echo "<p><span>Dimensiones:</span> " . $fila["dimensiones"] . "</p>";
                    echo "<p><span>Colores:</span> " . $fila["colores_disponibles"] . "</p>";
                    echo "<p><span>Precio:</span> Bs" . number_format($fila["precio"], 2) . "</p>";
                    echo "<p><span>Total:</span> Bs" . number_format($fila["precio"], 2) . "</p>";
                    echo "</div>";

                    // Botón de añadir al carrito
                    if ($fila["stock"] > 0) {
                        echo "<form method='POST' action=''>";
                        echo "<input type='hidden' name='id_producto' value='" . $fila["id_producto"] . "'>";
                        echo "<button type='submit' name='agregar_carrito' class='add-to-cart-btn'>Añadir al carrito</button>";
                        echo "</form>";
                    } else {
                        echo "<p class='out-of-stock'>Producto agotado</p>";
                    }

                    // Link al perfil del artesano
                    echo "<p>Artesano: <a href='perfil_artesano.php?id_artesano=" . $fila["id_artesano"] . "'>Ver perfil de " . $fila["nombre_artesano"] . "</a></p>";

                    echo "</div>"; // Cierre del contenedor del producto
                }
            } else {
                echo "<p>No se encontraron productos.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Carrito de compras -->
<div class="cart-container">
    <h2>Carrito de Compras</h2>
    <div id="cart-items">
        <?php
        if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
            $total = 0;
            echo "<div class='cart-items-list'>";
            foreach ($_SESSION['carrito'] as $id_producto => $cantidad) {
                $sql = "SELECT nombre, precio FROM producto WHERE id_producto = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param('i', $id_producto);
                $stmt->execute();
                $stmt->bind_result($nombre, $precio);
                $stmt->fetch();
                $stmt->close();

                $subtotal = $precio * $cantidad;
                $total += $subtotal;

                echo "<div class='cart-item'>";
                echo "<p><strong>Producto:</strong> " . $nombre . "</p>";
                echo "<p><strong>Cantidad:</strong> <input type='number' value='" . $cantidad . "' min='1' class='quantity-input'></p>";
                echo "<p><strong>Precio Unitario (Bs):</strong> Bs" . number_format($precio, 2) . "</p>";
                echo "<p><strong>Subtotal (Bs):</strong> Bs" . number_format($subtotal, 2) . "</p>";
                echo "<form method='POST' action=''><input type='hidden' name='id_producto' value='" . $id_producto . "'><button type='submit' name='eliminar_carrito' class='delete-btn'>Eliminar</button></form>";
                echo "</div>";
            }
            echo "</div>";
            echo "<p class='cart-total'>Total a pagar: <strong>Bs" . number_format($total, 2) . "</strong></p>";

            echo "<div class='cart-buttons'><a href='Productos.php' class='continue-shopping-btn'>Continuar Comprando</a> <a href='factura.php' class='checkout-btn'>Finalizar Compra</a></div>";
        } else {
            echo "<p>El carrito está vacío.</p>";
        }
        ?>
    </div>
</div>

</div>

<footer>
    © 2024 Tienda en Línea. Todos los derechos reservados.
</footer>

</body>
</html>

<?php
$conexion->close();
?>
