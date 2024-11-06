<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rol = mysqli_real_escape_string($conexion, $_POST['rol']);

    // Validar el rol seleccionado
    if (in_array($rol, ['administrador', 'artesano', 'cliente', 'delivery'])) {
        // Consultar los usuarios según el rol
        $query = "SELECT u.ID_Usuario, u.Nombre, u.Correo_Electronico FROM Usuario u
                  WHERE u.Rol = '$rol'";
        $result = mysqli_query($conexion, $query);

        echo "<h2>Lista de Usuarios - " . ucfirst($rol) . "</h2>";

        if (mysqli_num_rows($result) > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>ID Usuario</th>
                        <th>Nombre</th>
                        <th>Correo Electrónico</th>
                    </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['ID_Usuario']}</td>
                        <td>{$row['Nombre']}</td>
                        <td>{$row['Correo_Electronico']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No se encontraron usuarios con el rol seleccionado.</p>";
        }
    } else {
        echo "<p>Rol inválido seleccionado.</p>";
    }
} else {
    echo "<p>Método de solicitud no válido.</p>";
}

mysqli_close($conexion);
?>
