<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$mensaje = ""; // Variable para almacenar el mensaje

// Registro de nuevos usuarios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('db.php');

    if (isset($_POST['usuario']) && isset($_POST['correo_electronico']) && isset($_POST['contrasena']) && isset($_POST['confirmar_contrasena']) && isset($_POST['direccion']) && isset($_POST['telefono'])) {
        
        $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
        $correo = mysqli_real_escape_string($conexion, $_POST['correo_electronico']);
        $contrasena = $_POST['contrasena'];
        $confirmar_contrasena = $_POST['confirmar_contrasena'];
        $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
        $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
        $rol = isset($_POST['rol']) ? mysqli_real_escape_string($conexion, $_POST['rol']) : 'cliente';

        // Verificar si las contraseñas coinciden y cumplen con los requisitos
        if ($contrasena !== $confirmar_contrasena) {
            $mensaje = "Las contraseñas no coinciden.";
        } else {
            // Expresión regular para validar la contraseña
            $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
            if (!preg_match($password_pattern, $contrasena)) {
                $mensaje = "La contraseña debe tener al menos 8 caracteres, incluyendo una letra mayúscula, una letra minúscula, un número y un carácter especial.";
            } else {
                // Verificar si el correo ya está registrado
                $query_correo = "SELECT * FROM Usuario WHERE Correo_Electronico = '$correo'";
                $result_correo = mysqli_query($conexion, $query_correo);
                if (mysqli_num_rows($result_correo) > 0) {
                    $mensaje = "El correo ya está registrado.";
                } else {
                    // Cifrar la contraseña
                    $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

                    // Generar token de verificación único
                    $token = bin2hex(random_bytes(50));

                    // Insertar usuario con el estado de verificación en 'FALSE'
                    $sql = "INSERT INTO Usuario (Nombre, Correo_Electronico, Contrasena, Direccion, Telefono, Rol, Verificado, Token) 
                            VALUES ('$usuario', '$correo', '$hashed_password', '$direccion', '$telefono', '$rol', FALSE, '$token')";

                    if (mysqli_query($conexion, $sql)) {
                        // Obtener el ID del usuario recién insertado
                        $id_usuario = mysqli_insert_id($conexion);

                        // Insertar en la tabla hija correspondiente según el rol
                        switch ($rol) {
                            case 'artesano':
                                $sql_hija = "INSERT INTO Artesano (ID_Usuario) VALUES ('$id_usuario')";
                                break;
                            case 'delivery':
                                $sql_hija = "INSERT INTO Delivery (ID_Usuario) VALUES ('$id_usuario')";
                                break;
                            case 'cliente':
                            default:
                                $sql_hija = "INSERT INTO Cliente (ID_Usuario) VALUES ('$id_usuario')";
                                break;
                        }

                        if (mysqli_query($conexion, $sql_hija)) {
                            // Enviar correo de verificación
                            $mail = new PHPMailer(true);

                            try {
                                // Configuración del servidor SMTP de Gmail
                                $mail->isSMTP();
                                $mail->Host = 'smtp.gmail.com';  // Servidor SMTP de Gmail
                                $mail->SMTPAuth = true;
                                $mail->Username = 'roxanacast14@gmail.com';  // Tu correo Gmail
                                $mail->Password = 'qhjjbxalcpqphyod';  // Contraseña de aplicación generada por Google
                                $mail->SMTPSecure = 'tls';  // Protocolo de encriptación
                                $mail->Port = 587;  // Puerto SMTP

                                // Remitente
                                $mail->setFrom('roxanacast14@gmail.com', 'Manjares y Artes');
                                // Destinatario
                                $mail->addAddress($correo);  // Dirección del destinatario

                                // Contenido del correo
                                $mail->isHTML(true);
                                $mail->Subject = 'Datos de tu cuenta - Manjares y Artes';

                                // Ruta del logo (enlace directo de Imgur)
                                $logo_url = 'https://i.imgur.com/mOyQRAq.jpg'; // Enlace directo del logo

                                // Cuerpo del correo en HTML con estilos
                                $mail->Body = "
                                <html>
                                <head>
                                    <style>
                                        .email-container {
                                            font-family: Arial, sans-serif;
                                            background-color: #f4f4f4;
                                            padding: 20px;
                                            border-radius: 10px;
                                            max-width: 600px;
                                            margin: auto;
                                        }
                                        .email-header {
                                            text-align: center;
                                            padding-bottom: 20px;
                                        }
                                        .email-header img {
                                            width: 150px;
                                            height: auto;
                                        }
                                        .email-content {
                                            background-color: #ffffff;
                                            padding: 20px;
                                            border-radius: 10px;
                                            color: #333;
                                        }
                                        .email-content h1 {
                                            color: #D2691E;
                                        }
                                        .email-footer {
                                            text-align: center;
                                            font-size: 12px;
                                            color: #999;
                                        }
                                        .btn-verificar {
                                            display: inline-block;
                                            padding: 10px 20px;
                                            margin-top: 20px;
                                            background-color: #D2691E;
                                            color: #fff;
                                            text-decoration: none;
                                            border-radius: 5px;
                                            text-align: center;
                                        }
                                        .btn-verificar:hover {
                                            background-color: #c5571b;
                                        }
                                    </style>
                                </head>
                                <body>
                                    <div class='email-container'>
                                        <div class='email-header'>
                                            <img src='$logo_url' alt='Manjares y Artes Logo'>
                                        </div>
                                        <div class='email-content'>
                                            <h1>Bienvenido a Manjares y Artes</h1>
                                            <p>Hola $usuario,</p>
                                            <p>Gracias por registrarte en <strong>Manjares y Artes</strong>. Aquí tienes tus datos de inicio de sesión:</p>
                                            <p><strong>Usuario:</strong> $usuario</p>
                                            <p>Para activar tu cuenta, por favor haz clic en el siguiente enlace:</p>
                                            <a href='http://localhost/PROYECTO_281/verificar.php?email=$correo&token=$token' class='btn-verificar'>Verificar Cuenta</a>
                                        </div>
                                        <div class='email-footer'>
                                            <p>© 2024 Manjares y Artes. Todos los derechos reservados.</p>
                                        </div>
                                    </div>
                                </body>
                                </html>";
                                

                                // Enviar el correo
                                $mail->send();
                                $mensaje = "Registro exitoso. Revisa tu correo para los detalles de tu cuenta.";
                                
                                // Redirigir a index1.html después de 3 segundos
                                header("refresh:3;url=login.php");

                            } catch (Exception $e) {
                                $mensaje = "Error al enviar el correo. Error: {$mail->ErrorInfo}";
                            }
                        } else {
                            $mensaje = "Error: No se pudo insertar en la tabla hija.";
                        }
                    } else {
                        $mensaje = "Error: " . mysqli_error($conexion);
                    }

                    mysqli_close($conexion);
                }
            }
        }
    } else {
        $mensaje = "Por favor, completa todos los campos.";
    }
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
            <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required>
            <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" placeholder="Confirmar Contraseña" required>
            <input type="text" name="direccion" placeholder="Dirección" required>
            <input type="text" name="telefono" placeholder="Teléfono" required>

            <!-- Checkbox para mostrar/ocultar contraseña -->
            <label>
                <input type="checkbox" onclick="togglePassword()"> Mostrar contraseña
            </label>

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

    <!-- Script para mostrar/ocultar contraseña -->
    <script>
        function togglePassword() {
            var passwordField = document.getElementById("contrasena");
            var confirmPasswordField = document.getElementById("confirmar_contrasena");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                confirmPasswordField.type = "text";
            } else {
                passwordField.type = "password";
                confirmPasswordField.type = "password";
            }
        }
    </script>
</body>
</html>
