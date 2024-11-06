<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seleccionar Tipo de Usuario</title>
    <!-- Enlaces CSS -->
</head>
<body>
    <div class="container">
        <h1>Seleccionar Tipo de Usuario</h1>
        <form action="mostrar_usuarios.php" method="post">
            <label for="rol">Selecciona un tipo de usuario:</label>
            <select name="rol" id="rol" required>
                <option value="">-- Selecciona --</option>
                <option value="administrador">Administrador</option>
                <option value="artesano">Artesano</option>
                <option value="cliente">Cliente</option>
                <option value="delivery">Delivery</option>
            </select>
            <button type="submit">Mostrar Usuarios</button>
        </form>
    </div>
</body>
</html>
