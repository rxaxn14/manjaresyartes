<?php
// Conexión a la base de datos
include('db.php');

// Procesamiento de acciones de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['usuario_id']) && isset($_POST['accion'])) {
        $usuario_id = intval($_POST['usuario_id']);
        $accion = $_POST['accion'];

        if ($accion === 'activar') {
            $sql = "UPDATE usuario SET estado = 'activo' WHERE ID_usuario = ?";
        } elseif ($accion === 'desactivar') {
            $sql = "UPDATE usuario SET estado = 'inactivo' WHERE ID_usuario = ?";
        } elseif ($accion === 'eliminar') {
            $sql = "DELETE FROM usuario WHERE ID_usuario = ?";
        }

        // Preparación y ejecución de la consulta
        if (isset($sql)) {
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $usuario_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Cerrar la conexión al finalizar
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Dashboard</title>
    <style>
        /* Estilos básicos para la interfaz */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            margin: 0;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #333;
            color: #fff;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            height: 100vh;
        }
        .sidebar button {
            background-color: #444;
            color: #fff;
            border: none;
            padding: 10px;
            margin-top: 10px;
            cursor: pointer;
            width: 100%;
            text-align: left;
        }
        .sidebar button:hover {
            background-color: #555;
        }
        .main-content, .estadistic-section, .users-section {
            flex: 1;
            padding: 20px;
            background-color: #f9f9f9;
            display: none;
            overflow-y: auto;
        }
        .product-list {
            display: flex;
            flex-direction: column;
        }
        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #0e4d92;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .acciones-form {
            display: inline;
        }
        button {
            padding: 8px 16px;
            margin: 5px;
            border: none;
            color: #fff;
            cursor: pointer;
        }
        .activar {
            background-color: #4caf50;
        }
        .activar:hover {
            background-color: #45a049;
        }
        .desactivar {
            background-color: #f44336;
        }
        .desactivar:hover {
            background-color: #e53935;
        }
        .eliminar {
            background-color: #ff5722;
        }
        .eliminar:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Panel de Administración</h2>
        <button onclick="showProductSection()">Editar Productos</button>
        <button onclick="showEstadisticSection()">Estadísticas</button>
        <button onclick="showUsersSection()">Usuarios</button>
    </div>

    <!-- Contenido principal de los productos -->
    <div class="main-content" id="product-content">
        <h2>Lista de Productos</h2>
        <div class="product-list">
            <!-- Aquí se cargarán los productos mediante AJAX -->
        </div>
        <div id="editForm" class="edit-form">
            <h2>Editar Producto</h2>
        </div>
    </div>

    <!-- Sección de estadísticas -->
    <div class="estadistic-section">
        <?php include('estadisticas.php'); ?>
    </div>

    <!-- Sección de usuarios -->
    <div class="users-section" id="users-content">
        <h2>Gestión de Usuarios</h2>

        <?php
        // Conexión a la base de datos
        include('db.php');

        // Obtener la lista de usuarios
        $sql = "SELECT ID_usuario, nombre, apellidos, correo_electronico, estado FROM usuario";
        $resultado = $conexion->query($sql);

        if ($resultado && $resultado->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($usuario = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $usuario['ID_usuario']; ?></td>
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['correo_electronico']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['estado']); ?></td>
                            <td>
                                <form method="POST" class="acciones-form" style="display:inline;">
                                    <input type="hidden" name="usuario_id" value="<?php echo $usuario['ID_usuario']; ?>">
                                    
                                    <?php if ($usuario['estado'] === 'inactivo'): ?>
                                        <button type="submit" name="accion" value="activar" class="activar">Activar</button>
                                    <?php else: ?>
                                        <button type="submit" name="accion" value="desactivar" class="desactivar">Desactivar</button>
                                    <?php endif; ?>
                                    
                                    <button type="submit" name="accion" value="eliminar" class="eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay usuarios registrados.</p>
        <?php endif; ?>

        <?php $conexion->close(); ?>
    </div>

    <script>
         function showProductSection() {
            document.querySelector('.main-content').style.display = 'block';
            document.querySelector('.estadistic-section').style.display = 'none';
            document.querySelector('.users-section').style.display = 'none';

            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'edit_product.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.querySelector('.product-list').innerHTML = xhr.responseText;
                } else {
                    document.querySelector('.product-list').innerHTML = "Error al cargar los productos.";
                }
            };
            xhr.send();
        }
        function showEstadisticSection() {
            document.querySelector('.estadistic-section').style.display = 'block';
            document.querySelector('.main-content').style.display = 'none';
            document.querySelector('.users-section').style.display = 'none';
        }

        function showUsersSection() {
            document.querySelector('.users-section').style.display = 'block';
            document.querySelector('.main-content').style.display = 'none';
            document.querySelector('.estadistic-section').style.display = 'none';
        }
        function agregarListenersBotones() {
            document.querySelectorAll('.acciones-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(form);

                    fetch('usuarios.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.mensaje);
                        showUsersSection();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Hubo un problema al realizar la operación.');
                    });
                });
            });
        }
</script>
</body>
</html>
