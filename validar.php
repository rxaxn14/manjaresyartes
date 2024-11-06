<?php
session_start();
include('db.php'); // Conectar con la base de datos

if (isset($_POST['correo']) && isset($_POST['contrasena'])) {
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $contrasena = $_POST['contrasena'];

    // Verificar si el usuario existe y está verificado
    $query = "SELECT * FROM Usuario WHERE correo_electronico = '$correo' AND Verificado = 1";
    $result = mysqli_query($conexion, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verificar la contraseña
        if (password_verify($contrasena, $user['contrasena'])) {
            // Guardar la información del usuario en la sesión
            $_SESSION['usuario_id'] = $user['ID_usuario'];
            $_SESSION['nombre'] = $user['nombre'];  // Guardar nombre
            $_SESSION['correo_cliente'] = $user['correo_electronico'];  // Guardar correo
            $_SESSION['direccion_envio'] = $user['direccion'] ?? "Dirección no proporcionada"; // Si no tiene dirección, mostrar un mensaje por defecto

            $_SESSION['rol'] = $user['Rol']; // Asegúrate de que el nombre de la columna sea 'Rol'

            // Redirigir según el rol
            switch ($user['Rol']) {
                case 'administrador':
                    header("Location: dashboard.php");
                    break;
                case 'artesano':
                    header("Location: artesano.php");
                    break;
                case 'cliente':
                    header("Location: home.php");
                    break;
                case 'delivery':
                    header("Location: delivery.php");
                    break;
                default:
                    echo "Rol desconocido.";
                    exit();
            }
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no registrado o no verificado.";
    }
} else {
    echo "Por favor, complete todos los campos.";
}
?>
