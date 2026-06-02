<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Si el usuario ya tiene una sesión activa, va directo al menú principal
if (isset($_SESSION['usuario_id'])) {
    header("Location: principal/dashboard.php");
    exit();
}

// 2. SI NO ESTÁ LOGUEADO: Redirección automática al login
header("Location: login/index.php");
exit();
?>