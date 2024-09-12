<?php
$mensaje = ""; // Variable para almacenar el mensaje

// Registro de nuevos usuarios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('db.php');

    $usuario = $_POST['usuario'];
    $correo = $_POST['correo_electronico'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Cifrar la contraseña
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $rol = $_POST['rol']; // Rol (cliente, artesano, delivery)

    // Consulta para insertar nuevo usuario
    $sql = "INSERT INTO usuarios (usuario, correo_electronico, contraseña, direccion, telefono, rol) 
            VALUES ('$usuario', '$correo', '$contraseña', '$direccion', '$telefono', '$rol')";
    
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Registro exitoso. Ahora puedes <a href='index1.html'>iniciar sesión</a>."; // Mensaje de éxito
    } else {
        $mensaje = "Error: " . $sql . "<br>" . mysqli_error($conexion); // Mensaje de error
    }

    mysqli_close($conexion);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrarse - Manjares y Artes</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="container">
        <h1>Registro de Usuarios</h1>
        <form action="register.php" method="post">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="email" name="correo_electronico" placeholder="Correo Electrónico" required>
            <input type="password" name="contraseña" placeholder="Contraseña" required>
            <input type="text" name="direccion" placeholder="Dirección" required>
            <input type="text" name="telefono" placeholder="Teléfono" required>
            <!-- Selección de roles -->
            <select name="rol" required>
                <option value="cliente">Cliente</option>
                <option value="artesano">Artesano</option>
                <option value="delivery">Delivery</option>
            </select>
            <button type="submit">Registrarse</button>
        </form>

        <!-- Mostrar mensaje debajo del formulario -->
        <?php if ($mensaje != ""): ?>
            <div class="mensaje">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
