<?php
// 1. INICIAR GESTIÓN DE SESIONES
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. CONTROL DE ACCESO
// Si el usuario ya inició sesión correctamente, lo mandamos al menú principal directamente
if (isset($_SESSION['usuario_id'])) {
    header("Location: principal/dashboard.php");
    exit();
} else {
    // Si no está logueado, lo mandamos directo al formulario de Login
    header("Location: login/index.php");
    exit();
}
?>