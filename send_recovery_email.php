<?php
include('db.php');

// Verificar si el formulario se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo_electronico'];
    
    // Verificar si el correo electrónico existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE correo_electronico='$correo' AND verificado=1";
    $resultado = mysqli_query($conexion, $sql);
    
    if (mysqli_num_rows($resultado) > 0) {
        // Generar un token único para la recuperación de la contraseña
        $token = bin2hex(random_bytes(50));
        $sql = "UPDATE usuarios SET token='$token' WHERE correo_electronico='$correo'";
        mysqli_query($conexion, $sql);

        // Enviar el correo de recuperación
        $to = $correo;
        $subject = "Recuperación de Contraseña";
        $message = "Hola,\n\nRecibimos una solicitud para restablecer la contraseña de tu cuenta en Manjares y Artes. Por favor, haz clic en el siguiente enlace para restablecer tu contraseña:\n\n";
        $message .= "http://localhost/PROYECTO_281/LOGIN/reset_password.php?token=$token\n\n";
        $message .= "Si no solicitaste este cambio, por favor ignora este mensaje.\n\nSaludos,\nEl equipo de Manjares y Artes";
        $headers = "From: no-reply@manjaresyartes.com\r\n";
        $headers .= "Reply-To: no-reply@manjaresyartes.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($to, $subject, $message, $headers)) {
            echo "Te hemos enviado un correo con un enlace para recuperar tu contraseña.";
        } else {
            echo "Error al enviar el correo de recuperación.";
        }
    } else {
        echo "El correo electrónico no está registrado o no está verificado.";
    }

    mysqli_close($conexion);
}
?>
