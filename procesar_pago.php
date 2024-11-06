<?php
session_start();

// Verifica si el carrito está vacío
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: Productos.php");
    exit();
}

$metodo_pago = $_POST['metodo_pago'] ?? null;

if (!$metodo_pago) {
    header("Location: factura.php");
    exit();
}

// Aquí puedes procesar el pago y guardar los detalles de la compra en la base de datos

// Limpia el carrito después de procesar la compra
unset($_SESSION['carrito']);

header("Location: confirmacion.php?metodo=$metodo_pago");
exit();
?>
