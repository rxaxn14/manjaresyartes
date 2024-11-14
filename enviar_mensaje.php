<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Asegúrate de que la ruta sea correcta

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombre = isset($_POST["nombre"]) ? htmlspecialchars($_POST["nombre"]) : '';
    $email = isset($_POST["correo"]) ? htmlspecialchars($_POST["correo"]) : ''; // 'correo' coincide con el formulario
    $mensaje = isset($_POST["mensaje"]) ? htmlspecialchars($_POST["mensaje"]) : '';

    if (!empty($nombre) && !empty($email) && !empty($mensaje)) {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';   // Servidor SMTP de Gmail
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tucorreo@gmail.com';   // Tu correo de Gmail
            $mail->Password   = 'tucontraseñadeaplicación'; // Tu contraseña de aplicación o contraseña normal si no tienes 2FA
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;  // Puerto SMTP de Gmail (587)

            // Remitente y destinatario
            $mail->setFrom($email, $nombre);  // De quién se envía
            $mail->addAddress('roxanacast14@gmail.com'); // Destinatario
            $mail->addReplyTo($email, $nombre);  // Responder a

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Nuevo mensaje de contacto';
            $mail->Body    = "<p><strong>Nombre:</strong> $nombre</p><p><strong>Correo:</strong> $email</p><p><strong>Mensaje:</strong><br>$mensaje</p>";

            // Enviar el mensaje
            if ($mail->send()) {
                echo "<p>¡Gracias, $nombre! Tu mensaje ha sido enviado correctamente.</p>";
            } else {
                echo "<p>Lo sentimos, ha ocurrido un error al enviar tu mensaje. Inténtalo de nuevo más tarde.</p>";
            }
        } catch (Exception $e) {
            echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
        }
    } else {
        echo "<p>Por favor, completa todos los campos del formulario.</p>";
    }
}
?>
