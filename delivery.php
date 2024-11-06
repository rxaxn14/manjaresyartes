<?php
session_start();
include('db.php'); // Conectar a la base de datos

// Verifica si el usuario ha iniciado sesión como delivery
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'delivery') {
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario que está logueado (el delivery)
$delivery_id = $_SESSION['usuario_id'];

// Obtener la información del delivery desde la tabla 'delivery'
$delivery_query = "SELECT tipo_vehiculo, disponibilidad FROM delivery WHERE ID_usuario = ?";
$delivery_stmt = $conexion->prepare($delivery_query);

if ($delivery_stmt === false) {
    die("Error en la consulta SQL: " . $conexion->error);
}

$delivery_stmt->bind_param('i', $delivery_id);
$delivery_stmt->execute();
$delivery_stmt->bind_result($tipo_vehiculo, $disponibilidad);
$delivery_stmt->fetch();
$delivery_stmt->close();

// Actualizar la disponibilidad si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['disponibilidad'])) {
    $nueva_disponibilidad = $_POST['disponibilidad'] === '1' ? 1 : 0;
    $update_query = "UPDATE delivery SET disponibilidad = ? WHERE ID_usuario = ?";
    $update_stmt = $conexion->prepare($update_query);

    if ($update_stmt === false) {
        die("Error en la consulta SQL: " . $conexion->error);
    }

    $update_stmt->bind_param('ii', $nueva_disponibilidad, $delivery_id);
    $update_stmt->execute();
    $update_stmt->close();

    // Refrescar la página para mostrar los cambios
    header("Location: delivery.php");
    exit();
}

// Obtener los pedidos asignados al delivery
$pedidos_query = "SELECT p.ID_pedido, p.total, p.metodo_pago, p.fecha, p.ubicacion, p.estado 
                  FROM pedido p
                  WHERE p.id_delivery = ?";
$pedidos_stmt = $conexion->prepare($pedidos_query);

if ($pedidos_stmt === false) {
    die("Error en la consulta SQL de pedidos: " . $conexion->error);
}

$pedidos_stmt->bind_param('i', $delivery_id); 
$pedidos_stmt->execute();
$pedidos_result = $pedidos_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Delivery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f3e9;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #301c17;
        }

        .historial-pedidos table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .historial-pedidos th, .historial-pedidos td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .historial-pedidos th {
            background-color: #f8b400;
            color: white;
        }

        .disponibilidad {
            margin-top: 20px;
        }

        .disponibilidad label {
            font-weight: bold;
        }

        .btn {
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Estilos para el botón Aceptar */
        .btn-aceptar {
            background-color: #27ae60;
            color: white;
            border: none;
        }

        .btn-aceptar:hover {
            background-color: #219150;
        }

        /* Estilos para el botón Rechazar */
        .btn-rechazar {
            background-color: #e74c3c;
            color: white;
            border: none;
        }

        .btn-rechazar:hover {
            background-color: #c0392b;
        }

        /* Estilo del botón entregado */
        .btn-entregado {
            background-color: #f8b400;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            display: inline-block;
        }

        .btn-entregado:hover {
            background-color: #d49400;
        }

        a {
            color: #f8b400;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        td {
            text-align: center;
        }

        /* Aumentar el ancho de la columna Estado */
        th.estado, td.estado {
            width: 180px; /* Ajusta este valor según lo necesites */
            text-align: center; /* Mantiene el texto centrado */
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Panel de Delivery</h1>

    <div class="disponibilidad">
        <form method="POST" action="delivery.php">
            <label for="disponibilidad">Disponibilidad: </label>
            <select name="disponibilidad" id="disponibilidad">
                <option value="1" <?php if ($disponibilidad == 1) echo 'selected'; ?>>Disponible</option>
                <option value="0" <?php if ($disponibilidad == 0) echo 'selected'; ?>>No Disponible</option>
            </select>
            <button type="submit" class="btn">Actualizar Disponibilidad</button>
        </form>
    </div>

    <div class="historial-pedidos">
        <h2>Pedidos Asignados</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Total (Bs)</th>
                    <th>Método de Pago</th>
                    <th>Fecha</th>
                    <th>Ubicación</th>
                    <th class="estado">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pedido = $pedidos_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $pedido['ID_pedido']; ?></td>
                        <td>Bs<?php echo number_format($pedido['total'], 2); ?></td>
                        <td><?php echo $pedido['metodo_pago']; ?></td>
                        <td><?php echo $pedido['fecha']; ?></td>
                        <td><a href="https://www.google.com/maps?q=<?php echo $pedido['ubicacion']; ?>" target="_blank"><?php echo $pedido['ubicacion']; ?></a></td>
                        <td class="estado">
                            <?php if ($pedido['estado'] === 'pendiente'): ?>
                                <!-- Mostrar opciones de Aceptar o Rechazar cuando el estado es pendiente -->
                                <form method="POST" action="actualizar_estado.php" style="display:inline;">
                                    <input type="hidden" name="id_pedido" value="<?php echo $pedido['ID_pedido']; ?>">
                                    <button type="submit" name="accion" value="aceptar" class="btn btn-aceptar">Aceptar</button>
                                </form>
                                <form method="POST" action="actualizar_estado.php" style="display:inline;">
                                    <input type="hidden" name="id_pedido" value="<?php echo $pedido['ID_pedido']; ?>">
                                    <button type="submit" name="accion" value="rechazar" class="btn btn-rechazar">Rechazar</button>
                                </form>
                            <?php elseif ($pedido['estado'] === 'en camino'): ?>
                                <!-- Mostrar el botón de "Entregado" cuando el pedido está en camino -->
                                <form method="POST" action="actualizar_estado.php" style="display:inline;">
                                    <input type="hidden" name="id_pedido" value="<?php echo $pedido['ID_pedido']; ?>">
                                    <button type="submit" name="accion" value="entregado" class="btn btn-entregado">Entregado</button>
                                </form>
                            <?php elseif ($pedido['estado'] === 'entregado'): ?>
                                Pedido entregado
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                
                <?php if ($pedidos_result->num_rows == 0): ?>
                    <tr>
                        <td colspan="6">No hay pedidos asignados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Script para obtener la ubicación del delivery y enviarla al servidor -->
<script>
function enviarUbicacion() {
    if (navigator.geolocation) {
        // Solicita la ubicación actual del navegador
        navigator.geolocation.getCurrentPosition(function(position) {
            var latitud = position.coords.latitude;
            var longitud = position.coords.longitude;

            // Enviar la ubicación al servidor usando AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delivery_geolocalizacion.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("latitud=" + latitud + "&longitud=" + longitud);

            console.log("Ubicación enviada: " + latitud + ", " + longitud);
        }, function(error) {
            // Maneja los errores de geolocalización
            if (error.code === error.PERMISSION_DENIED) {
                alert("Debes permitir el acceso a tu ubicación para compartirla.");
            } else {
                console.error("Error al obtener la ubicación: " + error.message);
            }
        });
    } else {
        alert("Geolocalización no soportada por este navegador.");
    }
}

// Actualizar la ubicación automáticamente cada 10 segundos
setInterval(enviarUbicacion, 10000);  // Cada 10 segundos
</script>

</body>
</html>

<?php
$conexion->close();
$pedidos_stmt->close();
?>
