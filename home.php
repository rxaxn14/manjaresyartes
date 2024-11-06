
<?php
session_start(); // Iniciar la sesión
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bienvenido a Manjares y Artes</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo1.jpg" alt="Logo de Manjares y Artes">
        </div>
        <nav>
            <ul>
                <li><a href="home.php">Inicio</a></li>
                <li><a href="Productos.php">Productos</a></li>

                <li><a href="#">Nosotros</a></li>
                <li><a href="#">Contacto</a></li>
                
                <!-- Mostrar enlaces según si el usuario ha iniciado sesión -->
                <?php if (!isset($_SESSION['usuario_id'])): ?>
                    <li><a href="login.php">Iniciar Sesión</a></li>
                    <li><a href="register.php">Registrarse</a></li>
                <?php else: ?>
                    <li><a href="perfil.php">Mi Perfil</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Bienvenido a Manjares y Artes</h1>
            <p>Artesanías únicas de las comunidades bolivianas</p>
            <a href="#productos" class="btn">Explorar Productos</a>
        </div>
    </section>

    <section id="productos" class="productos">
        <h2>Nuestros Productos</h2>
        <div class="grid">
            <div class="producto">
                <img src="artesania1.jpg" alt="Artesanía 1">
                <h3>Artesanía 1</h3>
                <p>Descripción breve del producto.</p>
                <a href="#" class="btn">Comprar</a>
            </div>
            <div class="producto">
                <img src="artesania2.jpg" alt="Artesanía 2">
                <h3>Artesanía 2</h3>
                <p>Descripción breve del producto.</p>
                <a href="#" class="btn">Comprar</a>
            </div>
            <div class="producto">
                <img src="artesania3.jpg" alt="Artesanía 3">
                <h3>Artesanía 3</h3>
                <p>Descripción breve del producto.</p>
                <a href="#" class="btn">Comprar</a>
            </div>
        </div>
    </section>

    <footer>
        <p>© 2024 Manjares y Artes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
