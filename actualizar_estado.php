<?php
session_start();
include('db.php'); // Conectar a la base de datos

// Verifica si el usuario ha iniciado sesión como delivery
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'delivery') {
    header("Location: login.php");
    exit();
}

// Verificar si se recibió una solicitud POST válida
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pedido'], $_POST['accion'])) {
    $id_pedido = $_POST['id_pedido'];
    $accion = $_POST['accion'];

    // Preparar la consulta SQL dependiendo de la acción
    if ($accion === 'aceptar') {
        // Cambiar el estado a "en camino" y registrar el tiempo actual
        $tiempo_en_camino = date('Y-m-d H:i:s'); // Tiempo actual en formato de fecha y hora
        $update_query = "UPDATE pedido SET estado = 'en camino', tiempo_en_camino = ? WHERE ID_pedido = ?";
        
        // Ejecutar la actualización
        $update_stmt = $conexion->prepare($update_query);
        if ($update_stmt === false) {
            die("Error en la consulta SQL: " . $conexion->error);
        }
        $update_stmt->bind_param('si', $tiempo_en_camino, $id_pedido);

    } elseif ($accion === 'rechazar') {
        // Cambiar el estado a "rechazado" (el pedido desaparece de la lista)
        $update_query = "UPDATE pedido SET estado = 'rechazado' WHERE ID_pedido = ?";
        
        // Ejecutar la actualización
        $update_stmt = $conexion->prepare($update_query);
        if ($update_stmt === false) {
            die("Error en la consulta SQL: " . $conexion->error);
        }
        $update_stmt->bind_param('i', $id_pedido);

    } elseif ($accion === 'entregado') {
        // Cambiar el estado a "entregado" y registrar el tiempo actual
        $tiempo_entregado = date('Y-m-d H:i:s'); // Tiempo actual en formato de fecha y hora
        $update_query = "UPDATE pedido SET estado = 'entregado', tiempo_entregado = ? WHERE ID_pedido = ?";
        
        // Ejecutar la actualización
        $update_stmt = $conexion->prepare($update_query);
        if ($update_stmt === false) {
            die("Error en la consulta SQL: " . $conexion->error);
        }
        $update_stmt->bind_param('si', $tiempo_entregado, $id_pedido);
    }

    // Ejecutar la consulta de actualización
    $update_stmt->execute();
    $update_stmt->close();

    // Redirigir de vuelta al panel de delivery
    header("Location: delivery.php");
    exit();
} else {
    // Si no se envió una solicitud válida, redirigir al panel
    header("Location: delivery.php");
    exit();
}
?>
