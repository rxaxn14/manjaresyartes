<?php
// Conectar a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'bd_artesanias');

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error al conectar con la base de datos: " . $conexion->connect_error);
}

$artesano = null;

// Verificar si se ha recibido el ID del artesano
if (isset($_GET['id_artesano'])) {
    $id_artesano = $_GET['id_artesano'];

    // Consulta para obtener la información del artesano
    $sql = "SELECT u.nombre, u.apellidos, u.direccion, u.telefono, c.nombre AS comunidad
            FROM artesano a
            JOIN usuario u ON a.ID_usuario = u.ID_usuario
            LEFT JOIN pertenece p ON u.ID_usuario = p.ID_usuario
            LEFT JOIN comunidad c ON a.id_comunidad = c.id_comunidad
            WHERE a.ID_usuario = ?";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param('i', $id_artesano);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Verificar si se encontró un resultado
        if ($resultado->num_rows > 0) {
            $artesano = $resultado->fetch_assoc();
        } else {
            echo "No se encontró el artesano.";
        }

        $stmt->close();
    } else {
        echo "Error en la consulta SQL: " . $conexion->error;
    }

    // Consulta para obtener los productos del artesano
    $sql_productos = "SELECT nombre, descripcion, precio FROM producto WHERE id_artesano = ?";
    if ($stmt_productos = $conexion->prepare($sql_productos)) {
        $stmt_productos->bind_param('i', $id_artesano);
        $stmt_productos->execute();
        $resultado_productos = $stmt_productos->get_result();
    } else {
        echo "Error en la consulta SQL de productos: " . $conexion->error;
    }
} else {
    echo "No se recibió un ID válido.";
}

// Cerrar la conexión
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Artesano</title>
    <link rel="stylesheet" href="css/perfil_artesano.css">
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
        </ul>
    </nav>
</header>

<!-- Contenedor del perfil del artesano -->
<div class="container"><div class="spacer-text" style="height: 120px; background-color: white;"></div>
    

    <img src="Imagenes/Benjamin.webp" alt="Foto del Artesano" class="profile-photo">
    
    <!-- Información del artesano -->
    <div class="profile-container">
        <h1>
            <?php 
            if (isset($artesano)) {
                echo $artesano['nombre'] . ' ' . $artesano['apellidos'];
            } else {
                echo "Nombre no disponible";
            }
            ?>
        </h1>
        <p>
        Comunidad: 
        <a href="https://en.wikipedia.org/wiki/Capinota" target="_blank" style="color: #994e2b; text-decoration: underline;">
            <?php echo isset($artesano['comunidad']) ? $artesano['comunidad'] : "No disponible"; ?>
        </a>
    </p>
        <p>Dirección: <?php echo isset($artesano['direccion']) ? $artesano['direccion'] : "No disponible"; ?></p>
        <p>Teléfono: <?php echo isset($artesano['telefono']) ? $artesano['telefono'] : "No disponible"; ?></p>
    </div>

    <!-- Lista de productos -->
    <div class="products">
        <h2>Productos fabricados</h2>
        <div class="product-list">
            <?php
            if (isset($resultado_productos) && $resultado_productos->num_rows > 0) {
                while ($fila = $resultado_productos->fetch_assoc()) {
                    echo "<div class='product-item'>";
                    echo "<h3>" . $fila["nombre"] . "</h3>";
                    echo "<p>Descripción: " . $fila["descripcion"] . "</p>";
                    echo "<p>Precio: Bs" . number_format($fila["precio"], 2) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Este artesano aún no tiene productos registrados.</p>";
            }
            ?>
        </div>
    </div>
</div>




<footer>
    © 2024 Manjares y Artes. Todos los derechos reservados.
</footer>

</body>
</html>
