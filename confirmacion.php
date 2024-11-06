<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodo_pago = isset($_POST['metodo_pago']) ? $_POST['metodo_pago'] : "No seleccionado";
    $numero_factura = rand(10000, 99999); // Generar un número de factura único
    $fecha_compra = date("Y-m-d H:i:s"); // Fecha de compra
    $usuario_id = $_SESSION['usuario_id']; // Obtener el ID del comprador
    $costo_envio = isset($_SESSION['costo_envio']) ? $_SESSION['costo_envio'] : 0; // Obtener el costo de envío
    $delivery_id = isset($_SESSION['delivery_id']) ? $_SESSION['delivery_id'] : 1; // Asignar ID del delivery (actualízalo según tu lógica)
    $ubicacion_cliente = isset($_POST['ubicacion']) ? $_POST['ubicacion'] : 'Ubicación no proporcionada'; // Guardar ubicación

    // Información de la compra
    $total_a_pagar = 0;
    $productos_comprados = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];

    // Cálculo del total de la compra
    include('db.php'); // Conectar a la base de datos
    foreach ($productos_comprados as $id_producto => $cantidad) {
        $sql = "SELECT nombre, precio FROM producto WHERE id_producto = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $id_producto);
        $stmt->execute();
        $stmt->bind_result($nombre_producto, $precio_unitario);
        $stmt->fetch();
        $stmt->close();

        $subtotal = $precio_unitario * $cantidad;
        $total_a_pagar += $subtotal;
    }

    // Sumar el costo de envío al total
    $total_a_pagar += $costo_envio;

  // Registrar el pedido en la tabla 'pedido' con estado 'pendiente'
$estado = 'pendiente'; // Estado inicial del pedido
$insert_pedido = "INSERT INTO pedido (total, metodo_pago, fecha, id_comprador, id_delivery, ubicacion, estado) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt_pedido = $conexion->prepare($insert_pedido);

if ($stmt_pedido === false) {
    die("Error en la consulta SQL de pedido: " . $conexion->error);
}

$stmt_pedido->bind_param('dssisss', $total_a_pagar, $metodo_pago, $fecha_compra, $usuario_id, $delivery_id, $ubicacion_cliente, $estado);
$stmt_pedido->execute();
$pedido_id = $stmt_pedido->insert_id; // Obtener el ID del pedido recién insertado
$stmt_pedido->close();

// AÑADIR AQUÍ EL CÓDIGO PARA REGISTRAR LOS PRODUCTOS EN 'detalle_pedido'

// Registrar cada producto en la tabla 'detalle_pedido'
foreach ($productos_comprados as $id_producto => $cantidad) {
    // Obtener el precio del producto
    $sql_precio = "SELECT precio FROM producto WHERE id_producto = ?";
    $stmt_precio = $conexion->prepare($sql_precio);
    $stmt_precio->bind_param('i', $id_producto);
    $stmt_precio->execute();
    $stmt_precio->bind_result($precio_unitario);
    $stmt_precio->fetch();
    $stmt_precio->close();

    // Insertar cada detalle de producto en la tabla 'detalle_pedido'
    $insert_detalle_pedido = "INSERT INTO detalle_pedido (ID_pedido, ID_producto, cantidad, precio_unitario) 
                              VALUES (?, ?, ?, ?)";
    $stmt_detalle_pedido = $conexion->prepare($insert_detalle_pedido);
    if ($stmt_detalle_pedido === false) {
        die("Error en la consulta SQL de detalle_pedido: " . $conexion->error);
    }
    $stmt_detalle_pedido->bind_param('iiid', $pedido_id, $id_producto, $cantidad, $precio_unitario);
    $stmt_detalle_pedido->execute();
    $stmt_detalle_pedido->close();
}



    // Enviar correo de confirmación
    $nombre_cliente = $_SESSION['nombre_cliente'] ?? 'Cliente desconocido';
    $correo_cliente = $_SESSION['correo_cliente'] ?? 'correo@ejemplo.com';

    // Crear una nueva instancia de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'roxanacast14@gmail.com';  // Tu correo Gmail
        $mail->Password = 'qhjjbxalcpqphyod';  // Contraseña de aplicación generada por Google
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Remitente y destinatario
        $mail->setFrom('roxanacast14@gmail.com', 'Manjares y Artes');
        $mail->addAddress($correo_cliente, $nombre_cliente);

        // Asunto del correo
        $mail->Subject = 'Factura de tu compra en Manjares y Artes';

        // Cuerpo del mensaje en HTML
        $mensaje = "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f9f9f9;
                    margin: 0;
                    padding: 0;
                }
                .email-container {
                    background-color: #ffffff;
                    padding: 20px;
                    margin: auto;
                    max-width: 600px;
                    border-radius: 10px;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                    font-size: 16px;
                    color: #333;
                }
                h1 {
                    font-size: 24px;
                    color: #444;
                    text-align: center;
                }
                p {
                    line-height: 1.6;
                    margin-bottom: 10px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                th, td {
                    padding: 10px;
                    border: 1px solid #ddd;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
                .total {
                    font-weight: bold;
                    color: #000;
                }
                .footer {
                    text-align: center;
                    margin-top: 20px;
                    font-size: 12px;
                    color: #888;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <h1>Factura de Compra</h1>
                <p>Gracias por tu compra en <strong>Manjares y Artes</strong>. Aquí están los detalles de tu pedido:</p>
                <p><strong>Número de Factura:</strong> #$numero_factura</p>
                <p><strong>Fecha de Compra:</strong> $fecha_compra</p>

                <h3>Detalles de la Compra:</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario (Bs)</th>
                            <th>Subtotal (Bs)</th>
                        </tr>
                    </thead>
                    <tbody>";

        // Añadir productos comprados al correo
        foreach ($productos_comprados as $id_producto => $cantidad) {
            $mensaje .= "
                <tr>
                    <td>Producto #$id_producto</td>
                    <td>$cantidad</td>
                    <td>Bs$precio_unitario</td>
                    <td>Bs" . number_format($subtotal, 2) . "</td>
                </tr>";
        }

        $mensaje .= "
                    <tr class='total'>
                        <td colspan='3'>Total a Pagar</td>
                        <td>Bs" . number_format($total_a_pagar, 2) . "</td>
                    </tr>
                </tbody>
                </table>
                <div class='footer'>
                    <p>Gracias por confiar en nosotros.</p>
                    <p>© 2024 Manjares y Artes. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>";

        $mail->isHTML(true);
        $mail->Body = $mensaje;

        $mail->send();
        echo 'El correo de confirmación se ha enviado con éxito.';
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }

    // Vaciar el carrito de compras después de la confirmación
    unset($_SESSION['carrito']);
    unset($_SESSION['costo_envio']);
} else {
    echo "No se ha seleccionado una ubicación.";
    exit();
}
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f3e9;
            padding: 20px;
            text-align: center;
        }
        .confirmation-container {
            background-color: white;
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #301c17;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            margin: 10px 0;
        }
        .total-pago {
            font-weight: bold;
            margin-top: 20px;
        }
        .btn-container {
            display: flex;
            justify-content: center;
            gap: 20px; /* Añadir espacio entre los botones */
            margin-top: 20px;
        }
        button, a {
            padding: 10px 20px;
            background-color: #db9e61;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover, a:hover {
            background-color: #b58350;
        }
    </style>
</head>
<body>

<div class="confirmation-container">
    <h1>¡Gracias por tu compra!</h1>
    <p>Tu pedido ha sido procesado con éxito.</p>
    <p><strong>Número de Factura:</strong> #<?php echo $numero_factura; ?></p>
    <p><strong>Fecha:</strong> <?php echo $fecha_compra; ?></p>
    <p><strong>Total a pagar:</strong> Bs<?php echo number_format($total_a_pagar, 2); ?></p>

    <!-- Contenedor de los botones -->
    <div class="btn-container">
        <!-- Botón para volver a productos -->
        <a href="productos.php">Volver a la Tienda</a>

        <!-- Botón para seguir el pedido con el ID del pedido -->
        <a href="seguir_mipedido.php?pedido_id=<?php echo $pedido_id; ?>">Seguir mi Pedido</a>
    </div>
</div>

</body>
</html>
