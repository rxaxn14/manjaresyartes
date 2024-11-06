<?php
if (isset($_GET['email']) && isset($_GET['token'])) {
    include('db.php');

    $correo = mysqli_real_escape_string($conexion, $_GET['email']);
    $token = mysqli_real_escape_string($conexion, $_GET['token']);

    // Verificar si el correo y el token coinciden
    $sql = "SELECT * FROM Usuario WHERE Correo_Electronico = ? AND Token = ? AND Verificado = FALSE";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $correo, $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Actualizar el estado de verificado
        $update_sql = "UPDATE Usuario SET Verificado = TRUE, Token = NULL WHERE Correo_Electronico = ?";
        $stmt_update = mysqli_prepare($conexion, $update_sql);
        mysqli_stmt_bind_param($stmt_update, "s", $correo);

        if (mysqli_stmt_execute($stmt_update)) {
            echo "Tu cuenta ha sido verificada exitosamente.";
            // Redireccionar automáticamente a la página de inicio de sesión
            header("Location: login.php");
            exit();
        } else {
            echo "Error al verificar la cuenta.";
        }
    } else {
        echo "El enlace de verificación es inválido o la cuenta ya ha sido verificada.";
    }

    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt_update);
    mysqli_close($conexion);
} else {
    echo "Parámetros inválidos.";
}
?>
