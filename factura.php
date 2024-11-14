<?php
session_start();

// Verifica si el cliente ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener datos del cliente de la sesión
$nombre_cliente = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : "Nombre no disponible";
$correo_cliente = isset($_SESSION['correo_cliente']) ? $_SESSION['correo_cliente'] : "Correo no disponible";
$direccion_envio = isset($_SESSION['direccion_envio']) ? $_SESSION['direccion_envio'] : "Dirección no proporcionada";

// Verificar si el carrito tiene productos
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "El carrito está vacío. No se puede generar la factura.";
    exit();
}

$total_a_pagar = 0;
$numero_factura = rand(10000, 99999); // Generar número aleatorio de factura
$fecha = date("d/m/Y"); // Fecha actual

// Obtener el costo de envío seleccionado desde la sesión (enviado desde envio.php)
$costo_envio = isset($_SESSION['costo_envio']) ? $_SESSION['costo_envio'] : 0; // Si no hay costo de envío, será 0
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de Compra</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Cargar la hoja de estilo de Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 40px;
            margin: 0;
        }

        .factura-container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }

        .factura-header, .datos-cliente, .detalles-envio, .productos-comprados, .opciones-pago {
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 12px;
        }

        th {
            background-color: #f8b400;
            color: white;
            text-align: left;
        }

        td {
            text-align: left;
            color: #333;
        }

        .total-pagar {
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
        }

        .input-container {
            position: relative;
            margin-bottom: 20px;
            text-align: left;
        }

        .input-container input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #f8b400;
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 16px;
            float: right;
        }

        button:hover {
            background-color: #d49400;
        }

        .fa {
            padding: 10px;
            font-size: 20px;
            width: 30px;
            text-align: center;
            text-decoration: none;
            margin-right: 5px;
        }

        #tarjeta-option {
    margin-top: 20px;
}

#tarjeta-option button {
    background-color: #f8b400;
    color: white;
    border: none;
    padding: 12px 20px;
    cursor: pointer;
    border-radius: 5px;
    margin-top: 20px;
    font-size: 16px;
    display: block;
    width: 100%;
    text-align: center;
}

#tarjeta-option button:hover {
    background-color: #d49400;
}

        .fa-cc-visa {color: navy;}
        .fa-cc-mastercard {color: red;}
        .fa-cc-amex {color: blue;}
        .fa-cc-discover {color: orange;}

        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            margin-top: 30px;
        }

        #map {
            height: 400px;
            width: 100%;
            margin-top: 20px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }
        #qr-option {
    text-align: center; /* Centra el contenido */
    margin-top: 20px;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

#qr-option h3 {
    font-size: 22px;
    color: #333;
    font-weight: bold;
}

#qr-option p {
    font-size: 16px;
    color: #555;
    margin-bottom: 15px;
}

#qr-option img {
    width: 150px; /* Tamaño más pequeño */
    height: auto;
    margin-top: 15px;
    border: 2px solid #f8b400; /* Borde del código QR */
    padding: 10px;
    border-radius: 10px;
}

        .btn-envio {
            background-color: #f8b400;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            float: right;
            margin-top: 20px;
            margin-right: 10px;
        }

        .btn-envio:hover {
            background-color: #d49400;
        }

        .location-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .location-buttons button {
            background-color: #f8b400;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .location-buttons button:hover {
            background-color: #d49400;
        }

        .envio-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .opciones-pago {
            margin-top: 30px;
        }

        #qr-option, #tarjeta-option {
            display: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="factura-container">
        <h1>Factura de Compra</h1>

        <div class="factura-header">
            <p><strong>Número de Factura:</strong> #<?php echo $numero_factura; ?></p>
            <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
        </div>

        <div class="datos-cliente">
            <h2>Datos del Cliente</h2>
            <p><strong>Nombre:</strong> <?php echo $nombre_cliente; ?></p>
            <p><strong>Correo:</strong> <?php echo $correo_cliente; ?></p>
        </div>

        <div class="detalles-envio">
            <h2>Detalles de Envío</h2>
            <p><strong>Dirección:</strong> <?php echo $direccion_envio; ?></p>
        </div>

        <div class="productos-comprados">
            <h2>Productos Comprados</h2>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario (Bs)</th>
                        <th>Subtotal (Bs)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include('db.php');
                    foreach ($_SESSION['carrito'] as $id_producto => $cantidad) {
                        $sql = "SELECT nombre, precio FROM producto WHERE id_producto = ?";
                        $stmt = $conexion->prepare($sql);
                        $stmt->bind_param('i', $id_producto);
                        $stmt->execute();
                        $stmt->bind_result($nombre_producto, $precio_unitario);
                        $stmt->fetch();
                        $stmt->close();

                        $subtotal = $precio_unitario * $cantidad;
                        $total_a_pagar += $subtotal;

                        echo "<tr>";
                        echo "<td>$nombre_producto</td>";
                        echo "<td>$cantidad</td>";
                        echo "<td>Bs" . number_format($precio_unitario, 2) . "</td>";
                        echo "<td>Bs" . number_format($subtotal, 2) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Mostrar el costo de envío y botón de método de envío en la misma línea -->
            <div class="envio-info">
                <p class="total-pagar"><strong>Costo de Envío:</strong> Bs<?php echo number_format($costo_envio, 2); ?></p>
                <a href="envio.php" class="btn-envio">Método de Envío</a>
            </div>

            <!-- Total con costo de envío incluido -->
            <p class="total-pagar"><strong>Total a pagar:</strong> Bs<?php echo number_format($total_a_pagar + $costo_envio, 2); ?></p>
        </div>

        <div class="opciones-pago">
    <h2>Método de Pago</h2>

    <label for="metodo_pago">Seleccionar Método de Pago:</label>
    <select id="metodo_pago">
        <option value="0">Seleccione un método</option>
        <option value="1">Pago con QR</option>
        <option value="2">Pago con Tarjeta</option>
    </select>

    <!-- Opción de pago con QR -->
    <div id="qr-option" style="display:none;">
        <h3>Pago con QR</h3>
        <p>Escanea el código QR para completar el pago.</p>
        <!-- Aquí puedes poner el código QR -->
        <img src="Imagenes/qr.jpg" alt="Código QR" style="width: 150px; height: auto; border: 2px solid #f8b400; padding: 10px; border-radius: 10px;">
        </div>

    <!-- Opción de pago con tarjeta -->
    <div id="tarjeta-option" style="display:none;">
        <h3>Pago con Tarjeta</h3>
        <form action="procesar_pago.php" method="POST">

            <!-- Métodos de pago -->
            <div class="payment-methods">
                <i class="fa fa-cc-visa" aria-hidden="true"></i>
                <i class="fa fa-cc-mastercard" aria-hidden="true"></i>
                <i class="fa fa-cc-amex" aria-hidden="true"></i>
                <i class="fa fa-cc-discover" aria-hidden="true"></i>
            </div>

            <div class="input-container">
                <label for="nombre">Nombre del Titular:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ingrese el nombre del titular" required>
            </div>

            <div class="input-container">
                <label for="tarjeta">Número de Tarjeta:</label>
                <input type="text" id="tarjeta" name="tarjeta" placeholder="1234 5678 1234 5678" required>
            </div>

            <div class="input-container">
                <label for="vencimiento">Fecha de Vencimiento (MM/AAAA):</label>
                <input type="text" id="vencimiento" name="vencimiento" placeholder="MM/AAAA" required>
            </div>

            <div class="input-container">
                <label for="cvv">Código de Seguridad (CVV):</label>
                <input type="password" id="cvv" name="cvv" placeholder="CVV" required>
            </div>

            <button type="submit">Pagar con tarjeta</button>
        </form>
    </div>

    <!-- Aquí añado el mapa para seleccionar ubicación -->
    <h2>Seleccionar Ubicación de Envío</h2>
    <div id="map"></div>
    <input type="hidden" id="ubicacion" name="ubicacion" value="Ubicación no seleccionada">

    <!-- Botones adicionales para ubicación -->
    <div class="location-buttons">
        <button type="button" id="getLocation">Obtener mi Ubicación Actual</button>
        <button type="submit">Guardar Ubicación</button>
    </div>

    <!-- Botón para completar la compra -->
    <button type="submit">Completar Compra</button>
</div>

<script>
    // Mostrar u ocultar las opciones de pago dependiendo de la selección
    document.getElementById('metodo_pago').addEventListener('change', function() {
        var metodoPago = this.value;
        
        // Ocultar todas las opciones
        document.getElementById('qr-option').style.display = 'none';
        document.getElementById('tarjeta-option').style.display = 'none';

        // Mostrar la opción seleccionada
        if (metodoPago == '1') {
            document.getElementById('qr-option').style.display = 'block';
        } else if (metodoPago == '2') {
            document.getElementById('tarjeta-option').style.display = 'block';
        }
    });
</script>


        <footer class="footer">
            <p>© 2024 Tienda en Línea. Todos los derechos reservados.</p>
        </footer>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        let map;
        let marker;
        let selectedLocation = document.getElementById('ubicacion');

        function initMap() {
            // Inicializar el mapa de OpenStreetMap con Leaflet
            map = L.map('map').setView([-16.500, -68.150], 13); // Coordenadas iniciales

            // Añadir capa de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Evento para añadir un marcador al hacer clic en el mapa
            map.on('click', function (e) {
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }
                selectedLocation.value = `${e.latlng.lat},${e.latlng.lng}`; // Guardar la ubicación
            });
        }

        // Función para obtener la ubicación actual del usuario
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const latlng = [position.coords.latitude, position.coords.longitude];
                    map.setView(latlng, 15); // Centrar el mapa en la ubicación actual
                    if (marker) {
                        marker.setLatLng(latlng);
                    } else {
                        marker = L.marker(latlng).addTo(map);
                    }
                    selectedLocation.value = `${latlng[0]},${latlng[1]}`; // Guardar la ubicación actual
                }, function (error) {
                    alert('No se pudo obtener tu ubicación');
                });
            } else {
                alert('La geolocalización no es compatible con este navegador');
            }
        }

        document.getElementById('getLocation').addEventListener('click', function () {
            getLocation(); // Obtener la ubicación actual
        });

        // Inicializar el mapa al cargar la página
        window.onload = function() {
            initMap();
        };
    </script>

</body>
</html>

<?php
$conexion->close();
?>
