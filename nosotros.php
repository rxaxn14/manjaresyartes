<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nosotros - Manjares y Artes</title>
    <style>
        /* CSS en línea para la página Nosotros */

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

        .about-section {
            padding: 50px 20px;
            background-color: #ffffff;
            text-align: center;
        }

        .about-section h1 {
            font-size: 36px;
            color: #301c17;
            margin-bottom: 20px;
        }

        .about-section p {
            font-size: 18px;
            line-height: 1.6;
            color: #555;
            max-width: 800px;
            margin: 0 auto;
        }

        .team {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .team-member {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 250px;
            text-align: center;
        }

        .team-member img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .team-member h3 {
            font-size: 20px;
            color: #301c17;
            margin-bottom: 10px;
        }

        .team-member p {
            font-size: 16px;
            color: #777;
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

    <section class="about-section">
        <h1>Acerca de Nosotros</h1>
        <p>Manjares y Artes es una plataforma dedicada a promover las artesanías y productos únicos de las comunidades bolivianas. Nuestro objetivo es preservar y dar a conocer la rica cultura y tradición que cada pieza representa, conectando a los artesanos con amantes de la cultura en todo el mundo.</p>
        
        <div class="team">
            <div class="team-member">
                <img src="imagenes/rox.jpeg" alt="Miembro del equipo">
                <h3>Roxana Castillo Mamani</h3>
                <p>Product Owner</p>
            </div>
            <div class="team-member">
                <img src="imagenes/pao.jpg" alt="Miembro del equipo">
                <h3>Paola Andrea Choque Quispe</h3>
                <p>Scrum Master</p>
            </div>
            <div class="team-member">
                <img src="imagenes/jhess.jpg" alt="Miembro del equipo">
                <h3>Jhessel Brisheyka Merlo Flores</h3>
                <p>Development Team</p>
            </div>
        </div>
    </section>

    <footer>
        <p>© 2024 Manjares y Artes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
