<?php
session_start();
include('db.php'); // Conectar con la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_producto = mysqli_real_escape_string($conexion, $_POST['nombre_producto']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $precio = mysqli_real_escape_string($conexion, $_POST['precio']);
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria']);
    $materiales = mysqli_real_escape_string($conexion, $_POST['materiales']);
    $dimensiones = mysqli_real_escape_string($conexion, $_POST['dimensiones']);
    $colores_disponibles = mysqli_real_escape_string($conexion, $_POST['colores_disponibles']);
    $stock = mysqli_real_escape_string($conexion, $_POST['stock']);
    $metodos_envio = mysqli_real_escape_string($conexion, $_POST['metodos_envio']);
    $garantia = mysqli_real_escape_string($conexion, $_POST['garantia']);
    $id_usuario = $_SESSION['usuario_id'];

    // Manejar la carga de la imagen
    $imagen = $_FILES['imagen']['name'];
    $ruta_temporal = $_FILES['imagen']['tmp_name'];
    $carpeta_destino = 'uploads/' . basename($imagen);

    if (move_uploaded_file($ruta_temporal, $carpeta_destino)) {
        // Insertar los datos del producto en la base de datos
        $sql = "INSERT INTO producto (nombre, descripcion, precio, categoria, materiales, dimensiones, colores_disponibles, stock, metodos_envio, garantia, id_artesano) 
                VALUES ('$nombre_producto', '$descripcion', '$precio', '$categoria', '$materiales', '$dimensiones', '$colores_disponibles', '$stock', '$metodos_envio', '$garantia', '$id_usuario')";

        if (mysqli_query($conexion, $sql)) {
            echo "Producto subido exitosamente.";
            header('Location: ver_productos.php');
            exit();
        } else {
            echo "Error al subir el producto: " . mysqli_error($conexion);
        }
    } else {
        echo "Error al subir la imagen.";
    }
}
?>
