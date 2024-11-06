<?php
session_start();
include('db.php'); // Conexión a la base de datos

if (isset($_POST['latitud']) && isset($_POST['longitud'])) {
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];
    $ubicacion = "Latitud: $latitud, Longitud: $longitud";
    
    $pedido_id = $_SESSION['pedido_id']; // ID del pedido actual

    // Actualizar la ubicación en la tabla 'pedido'
    $sql = "UPDATE pedido SET ubicacion = ? WHERE ID_pedido = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        die(json_encode(['success' => false, 'message' => 'Error en la consulta SQL']));
    }

    $stmt->bind_param('si', $ubicacion, $pedido_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar la ubicación']);
    }

    $stmt->close();
    $conexion->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Latitud o longitud no proporcionadas']);
}
