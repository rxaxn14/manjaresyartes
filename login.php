<?php
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 1:
            echo "<p style='color:red;'>Contraseña incorrecta. Por favor, inténtelo de nuevo.</p>";
            break;
        case 2:
            echo "<p style='color:red;'>Usuario no registrado o no verificado. Revise su correo para la verificación.</p>";
            break;
        case 3:
            echo "<p style='color:red;'>Por favor, complete todos los campos.</p>";
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Iniciar Sesión - Manjares y Artes</title>
    <link rel="stylesheet" href="css/csslogin.css"> <!-- Incluye tu archivo CSS para estilos -->
    <style>
        /* Estilos para el contenedor */
        .container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f7f3e9;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Estilos para los inputs */
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #994e2b;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #301c17;
        }

        .recover-password {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #994e2b;
            text-decoration: none;
        }

        .recover-password:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Iniciar Sesión</h1>
        <form action="validar.php" method="post">
            <input type="email" name="correo" placeholder="Correo Electrónico" required>
            <div>
                <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required>
                <input type="checkbox" onclick="togglePassword()"> Mostrar contraseña
            </div>
            <button type="submit">Ingresar</button>
        </form>
        <a href="recover_password.html" class="recover-password">Olvidé mi contraseña</a>
    </div>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("contrasena");
            passwordField.type = (passwordField.type === "password") ? "text" : "password";
        }
    </script>
</body>
</html>
