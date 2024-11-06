<?php
session_start();
include('db.php'); // Conectar a la base de datos

// Verifica si el cliente ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Simulamos una dirección del cliente (esta puede venir de la base de datos o la sesión)
$direccion_cliente = isset($_SESSION['direccion']) ? $_SESSION['direccion'] : "Calle 21 de junio";

// Definir costos y tiempos de entrega según el tipo de vehículo
$costos = [
    'Moto' => 10,
    'Auto' => 20,
    'Bicicleta' => 5
];

$tiempos_entrega = [
    'Moto' => 30, // minutos
    'Auto' => 20, // minutos
    'Bicicleta' => 45 // minutos
];

// Consulta para obtener todos los deliverys
$sql = "SELECT u.nombre, u.apellidos, d.ID_usuario, d.tipo_vehiculo, d.disponibilidad, d.valoracion
        FROM delivery d
        JOIN usuario u ON d.ID_usuario = u.ID_usuario";
$resultado = $conexion->query($sql);

// Verificar si la consulta fue exitosa
if (!$resultado) {
    echo "Error en la consulta SQL: " . $conexion->error;
    exit(); 
}

// Procesar selección del delivery
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delivery_id'])) {
    $delivery_id = $_POST['delivery_id'];
    $vehiculo = $_POST['tipo_vehiculo'];
    $costo_envio = isset($costos[$vehiculo]) ? $costos[$vehiculo] : 0;

    // Guardar el delivery seleccionado y el costo de envío en la sesión
    $_SESSION['delivery_id'] = $delivery_id;
    $_SESSION['costo_envio'] = $costo_envio;

    // Redirigir al usuario a la página de factura para finalizar la compra
    header("Location: factura.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opciones de Envío</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f3e9;
            padding: 20px;
            text-align: center;
        }

        .envio-container {
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

        .delivery-list {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #db9e61;
            color: white;
        }

        td {
            text-align: center;
        }

        .unavailable {
            color: red;
            font-weight: bold;
        }

        .available {
            color: green;
            font-weight: bold;
        }

        .valoracion {
            color: #FFD700;
            font-size: 18px;
        }

        .btn-select {
            background-color: #db9e61;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-select:hover {
            background-color: #b58350;
        }
    </style>
</head>
<body>

<div class="envio-container">
    <h1>Opciones de Envío</h1>
    <p>Dirección de entrega: <strong><?php echo $direccion_cliente; ?></strong></p>
    
    <div class="delivery-list">
        <h2>Deliverys Disponibles</h2>
        <form method="POST" action="">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Vehículo</th>
                        <th>Disponibilidad</th>
                        <th>Tiempo de Entrega (min)</th>
                        <th>Costo de Envío (Bs)</th>
                        <th>Valoración</th>
                        <th>Seleccionar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $resultado->fetch_assoc()): 
                        $vehiculo = $fila['tipo_vehiculo'];
                        $costo_envio = isset($costos[$vehiculo]) ? $costos[$vehiculo] : 0;
                        $tiempo_entrega = isset($tiempos_entrega[$vehiculo]) ? $tiempos_entrega[$vehiculo] : 'Desconocido';
                    ?>
                    <tr>
                        <td><?php echo $fila['nombre'] . " " . $fila['apellidos']; ?></td>
                        <td><?php echo $vehiculo; ?></td>
                        <td>
                            <?php if ($fila['disponibilidad'] == 1): ?>
                                <span class="available">Disponible</span>
                            <?php else: ?>
                                <span class="unavailable">No Disponible</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $tiempo_entrega; ?> min</td>
                        <td>Bs<?php echo number_format($costo_envio, 2); ?></td>
                        <td><span class="valoracion"><?php echo $fila['valoracion']; ?> ★</span></td>
                        <td>
                            <?php if ($fila['disponibilidad'] == 1): ?>
                                <button type="submit" name="delivery_id" value="<?php echo $fila['ID_usuario']; ?>" class="btn-select">Seleccionar</button>
                                <input type="hidden" name="tipo_vehiculo" value="<?php echo $vehiculo; ?>">
                            <?php else: ?>
                                No disponible
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </form>
    </div>
</div>

</body>
</html>

<?php
$conexion->close();
?>
