<?php
session_start();
include('db.php'); // Conectar a la base de datos

// Verificar si el usuario ha iniciado sesión como delivery
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'delivery') {
    http_response_code(403); // No autorizado
    echo "No autorizado";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ID del delivery desde la sesión
    $delivery_id = $_SESSION['usuario_id'];

    // Obtener la latitud y longitud enviadas desde el frontend
    $latitud = isset($_POST['latitud']) ? $_POST['latitud'] : null;
    $longitud = isset($_POST['longitud']) ? $_POST['longitud'] : null;

    if ($latitud && $longitud) {
        // Actualizar la ubicación del delivery en la tabla 'delivery'
        $query = "UPDATE delivery SET latitud = ?, longitud = ? WHERE ID_usuario = ?";
        $stmt = $conexion->prepare($query);

        if ($stmt === false) {
            http_response_code(500); // Error interno del servidor
            echo "Error en la consulta SQL: " . $conexion->error;
            exit();
        }

        $stmt->bind_param('ddi', $latitud, $longitud, $delivery_id);
        $stmt->execute();
        $stmt->close();

        echo "Ubicación actualizada";
    } else {
        http_response_code(400); // Solicitud incorrecta
        echo "Faltan datos de ubicación.";
    }
} else {
    http_response_code(405); // Método no permitido
    echo "Método no permitido";
}

$conexion->close();
?>
