
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['usuario_id'])) {
    header("Location: ../principal/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Macdonald</title>
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <div class="container">
        <div class="login-box">
            <div class="logo">
                <img src="../assets/image/logo.png" alt="McDonald's Logo">
                <h1>MacDonald's</h1>
                <p>Sistema de Gestión</p>
            </div>

            <form action="validar.php" method="POST">

                <div class="input-box">
                    <i class="fas fa-user"></i>
                    <input type="text" name="usuario" placeholder="Usuario" required>
                </div>

                <div class="input-box">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Contraseña" required>
                </div>

                <button type="submit">
                    Ingresar
                </button>

            </form>
        </div>
    </div>

</body>
</html>