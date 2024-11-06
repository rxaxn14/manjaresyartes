<?php
session_start();
include('db.php'); // Conectar a la base de datos
// Asegúrate de que el usuario tenga un ID válido en la tabla cliente
$_SESSION['usuario_id'] = 1;  // Asignar temporalmente el ID 1 para pruebas

// Verifica si el cliente ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener el ID del pedido desde la URL
if (!isset($_GET['pedido_id'])) {
    die("ID de pedido no proporcionado.");
}

$pedido_id = $_GET['pedido_id'];

// Obtener la información del pedido
$pedido_query = "SELECT p.ID_pedido, p.estado, p.id_delivery, d.latitud, d.longitud, p.tiempo_en_camino, p.tiempo_entregado 
                 FROM pedido p
                 JOIN delivery d ON p.id_delivery = d.ID_usuario
                 WHERE p.ID_pedido = ?";
$stmt = $conexion->prepare($pedido_query);

if ($stmt === false) {
    die("Error en la consulta SQL: " . $conexion->error);
}

$stmt->bind_param('i', $pedido_id);
$stmt->execute();
$stmt->bind_result($id_pedido, $estado, $id_delivery, $latitud, $longitud, $tiempo_en_camino, $tiempo_entregado);
$stmt->fetch();
$stmt->close();

// Cálculo del tiempo total de entrega
if (!empty($tiempo_en_camino) && !empty($tiempo_entregado)) {
    $datetime1 = new DateTime($tiempo_en_camino);
    $datetime2 = new DateTime($tiempo_entregado);
    $interval = $datetime1->diff($datetime2);
    $tiempo_entrega = $interval->format('%h horas %i minutos %s segundos');
} else {
    $tiempo_entrega = "No disponible";
}

// Manejo de la valoración si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valoracion'])) {
    $valoracion = (int)$_POST['valoracion']; // Calificación recibida del 1 al 5
    if ($valoracion >= 1 && $valoracion <= 5) {
        // Actualizar la valoración del delivery
        $update_query = "UPDATE delivery SET valoracion = ? WHERE ID_usuario = ?";
        $stmt_update = $conexion->prepare($update_query);
        $stmt_update->bind_param('ii', $valoracion, $id_delivery);
        $stmt_update->execute();
        $stmt_update->close();
        echo "<script>alert('¡Gracias por tu valoración!');</script>";
    } else {
        echo "<script>alert('Valoración no válida.');</script>";
    }
}

// Manejo del comentario si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
    $comentario = trim($_POST['comentario']);
    $fecha_comentario = date("Y-m-d H:i:s");
    $usuario_id = $_SESSION['usuario_id']; // ID del cliente
    $valoracion = isset($_POST['valoracion']) ? (int)$_POST['valoracion'] : null; // Obtener la valoración si existe

    if (!empty($comentario)) {
        // Obtener el ID del producto relacionado con el pedido
        $query_producto = "SELECT ID_producto FROM detalle_pedido WHERE ID_pedido = ?";
        $stmt_producto = $conexion->prepare($query_producto);
        $stmt_producto->bind_param('i', $pedido_id);
        $stmt_producto->execute();
        $stmt_producto->bind_result($id_producto);
        $stmt_producto->fetch();
        $stmt_producto->close();

        // Verificar si se ha obtenido el ID_producto
        if ($id_producto) {
            // Insertar el comentario en la tabla 'comentario'
            $insert_comentario = "INSERT INTO comentario (ID_producto, ID_cliente, comentario, fecha, valoracion) 
                                  VALUES (?, ?, ?, ?, ?)";
            $stmt_comentario = $conexion->prepare($insert_comentario);
            if ($stmt_comentario === false) {
                die("Error en la consulta SQL de comentario: " . $conexion->error);
            }
            $stmt_comentario->bind_param('iissi', $id_producto, $usuario_id, $comentario, $fecha_comentario, $valoracion);
            
            // Ejecutar la inserción y comprobar si es exitosa
            if ($stmt_comentario->execute()) {
                echo "<script>alert('¡Gracias por tu comentario!');</script>";
            } else {
                echo "Error al insertar el comentario: " . $conexion->error;
            }
            $stmt_comentario->close();
        } else {
            echo "<script>alert('No se encontró un producto relacionado con este pedido.');</script>";
        }
    } else {
        echo "<script>alert('Por favor, escribe un comentario.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguir Mi Pedido</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f3e9;
            text-align: center;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #e67e22;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            color: #555;
            font-size: 18px;
        }

        .btn {
            background-color: #e67e22;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            display: inline-block;
        }

        .btn:hover {
            background-color: #d35400;
        }

        /* Estilos para diferentes estados del pedido */
        .estado-pendiente {
            color: #e67e22;
            font-weight: bold;
        }

        .estado-en-camino {
            color: #3498db;
            font-weight: bold;
        }

        .estado-entregado {
            color: #2ecc71;
            font-weight: bold;
        }

        /* Mapa */
        #map {
            height: 400px;
            width: 100%;
            margin-top: 20px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        /* Estilos para la calificación */
        .rating {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .rating label {
            font-size: 30px;
            color: #ddd;
            cursor: pointer;
        }

        .rating input {
            display: none;
        }

        .rating input:checked ~ label {
            color: #f8b400;
        }

        .rating label:hover,
        .rating label:hover ~ label {
            color: #f8b400;
        }

        .rating label:hover {
            transform: scale(1.2);
        }

        .rating-form {
            margin-top: 20px;
        }

        .rating-form button {
            background-color: #27ae60;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .rating-form button:hover {
            background-color: #219150;
        }

        /* Estilos para el formulario de comentarios */
        .comentario-container {
            margin-top: 20px;
        }

        .comentario-container textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .comentario-container button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .comentario-container button:hover {
            background-color: #2980b9;
        }
    </style>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>

<div class="container">
    <h1>Seguir Mi Pedido</h1>
    <p>
        <?php if ($estado === 'pendiente'): ?>
            <span class="estado-pendiente">¡Tu pedido está pendiente!</span>
        <?php elseif ($estado === 'en camino'): ?>
            <span class="estado-en-camino">¡Tu pedido está en camino!</span>
            <!-- Mostrar el mapa solo si el pedido está en camino -->
            <div id="map"></div>
        <?php elseif ($estado === 'entregado'): ?>
            <span class="estado-entregado">¡Tu pedido ha sido entregado con éxito!</span>
            <p><strong>Tiempo total de entrega:</strong> <?php echo $tiempo_entrega; ?></p>
            <p>Por favor, califica la calidad del servicio del repartidor:</p>
            <!-- Formulario de valoración -->
            <form action="seguir_mipedido.php?pedido_id=<?php echo $pedido_id; ?>" method="POST" class="rating-form">
                <div class="rating">
                    <input type="radio" id="star5" name="valoracion" value="5" />
                    <label for="star5" title="Excelente"><i class="fas fa-star"></i></label>

                    <input type="radio" id="star4" name="valoracion" value="4" />
                    <label for="star4" title="Muy bueno"><i class="fas fa-star"></i></label>

                    <input type="radio" id="star3" name="valoracion" value="3" />
                    <label for="star3" title="Bueno"><i class="fas fa-star"></i></label>

                    <input type="radio" id="star2" name="valoracion" value="2" />
                    <label for="star2" title="Regular"><i class="fas fa-star"></i></label>

                    <input type="radio" id="star1" name="valoracion" value="1" />
                    <label for="star1" title="Malo"><i class="fas fa-star"></i></label>
                </div>

                <!-- Botón para enviar la valoración -->
                <button type="submit">Enviar Valoración</button>
            </form>

            <!-- Formulario para el comentario -->
            <div class="comentario-container">
                <h3>Deja un comentario sobre tu experiencia</h3>
                <form action="seguir_mipedido.php?pedido_id=<?php echo $pedido_id; ?>" method="POST">
                    <textarea name="comentario" rows="5" placeholder="Escribe tu comentario aquí..."></textarea>
                    <button type="submit">Enviar Comentario</button>
                </form>
            </div>
        <?php endif; ?>
    </p>

    <a href="productos.php" class="btn">Volver a la Tienda</a>
</div>

<script>
    <?php if ($estado === 'en camino'): ?>
    // Inicializar el mapa con las coordenadas del delivery
    var map = L.map('map').setView([<?php echo $latitud; ?>, <?php echo $longitud; ?>], 13);

    // Añadir capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Crear un marcador para el repartidor
    var marker = L.marker([<?php echo $latitud; ?>, <?php echo $longitud; ?>]).addTo(map)
        .bindPopup('Ubicación del repartidor')
        .openPopup();

    // Función para actualizar la ubicación del repartidor en el mapa cada 10 segundos
    function actualizarUbicacion() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'obtener_ubicacion_delivery.php?delivery_id=<?php echo $id_delivery; ?>', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var respuesta = JSON.parse(xhr.responseText);
                var latitud = respuesta.latitud;
                var longitud = respuesta.longitud;

                // Actualizar la posición del marcador en el mapa
                marker.setLatLng([latitud, longitud]);

                // Ajustar la vista del mapa a la nueva ubicación
                map.setView([latitud, longitud], 13);
            }
        };
        xhr.send();
    }

    // Actualizar la ubicación del repartidor cada 10 segundos
    setInterval(actualizarUbicacion, 10000); // Cada 10 segundos
    <?php endif; ?>
</script>

</body>
</html>

<?php
$conexion->close();
?>
