<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Manjares y Artes</title>
    <style>
        /* CSS en línea para la página de contacto */

        /* Restablecer margen y padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Century Gothic', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
        }

        header {
            background-color: #301c17;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        .logo img {
            width: 100px;
        }

        nav ul {
            list-style-type: none;
            display: flex;
            gap: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #db9e61;
        }

        .contact-section {
            padding: 50px 20px;
            background-color: #ffffff;
            text-align: center;
        }

        .contact-section h1 {
            font-size: 36px;
            color: #301c17;
            margin-bottom: 20px;
        }

        .contact-section p {
            font-size: 18px;
            color: #555;
            max-width: 800px;
            margin: 0 auto 40px;
        }

        .contact-form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #f5f5f5;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .contact-form button {
            background-color: #db9e61;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .contact-form button:hover {
            background-color: #994e2b;
        }

        footer {
            background-color: #301c17;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo1.jpg" alt="Logo de Manjares y Artes">
        </div>
        <nav>
            <ul>
                <li><a href="home.php">Inicio</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="nosotros.php">Nosotros</a></li>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </nav>
    </header>

    <section class="contact-section">
        <h1>Contáctanos</h1>
        <p>Si tienes alguna pregunta o comentario, no dudes en comunicarte con nosotros. Completa el siguiente formulario y nos pondremos en contacto contigo lo antes posible.</p>
        
        <div class="contact-form">
    <form action="enviar_mensaje.php" method="POST">
        <input type="text" name="nombre" placeholder="Tu Nombre" required>
        <input type="email" name="correo" placeholder="Tu Correo Electrónico" required>
        <textarea name="mensaje" rows="5" placeholder="Escribe tu mensaje aquí..." required></textarea>
        <button type="submit">Enviar Mensaje</button>
    </form>
</div>

    </section>

    <footer>
        <p>© 2024 Manjares y Artes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
