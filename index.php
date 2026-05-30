<?php
// 1. INICIAR GESTIÓN DE SESIONES
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. CONTROL DE ACCESO
// Si el usuario ya inició sesión correctamente, lo mandamos al dashboard
if (isset($_SESSION['usuario_id'])) {
    header("Location: principal/dashboard.php");
    exit();
}

// 3. SI NO ESTÁ LOGUEADO:
// Aquí abajo debes colocar tu código HTML del formulario de Login (el diseño de tu login)
// Ya no necesitas un header("Location: ...") hacia sí mismo porque este mismo archivo es tu login.
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema</title>
</head>
<body>
    <form action="procesar_login.php" method="POST">
        </form>
</body>
</html>
