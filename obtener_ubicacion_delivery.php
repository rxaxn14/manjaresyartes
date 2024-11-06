<?php
include('db.php');

// Verificar que se haya proporcionado un ID de delivery
if (!isset($_GET['delivery_id'])) {
    die(json_encode(['error' => 'ID de delivery no proporcionado']));
}

$delivery_id = $_GET['delivery_id'];

// Consulta para obtener la latitud y longitud del delivery
$query = "SELECT latitud, longitud FROM delivery WHERE ID_usuario = ?";
$stmt = $conexion->prepare($query);

if ($stmt === false) {
    die(json_encode(['error' => 'Error en la consulta SQL']));
}

$stmt->bind_param('i', $delivery_id);
$stmt->execute();
$stmt->bind_result($latitud, $longitud);
$stmt->fetch();
$stmt->close();

// Devolver las coordenadas como JSON
echo json_encode(['latitud' => $latitud, 'longitud' => $longitud]);
