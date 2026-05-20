<?php
// 1. GESTIÓN DE SESIÓN Y SEGURIDAD DIRECTA
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si el usuario no ha iniciado sesión, se le deniega el acceso y se le expulsa al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login/index.php");
    exit();
}
?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistema de Gestion Macdonald</title>



    <link rel="stylesheet" href="../assets/css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>



<div class="container">



    <div class="sidebar">



        <div class="logo">

           <img src="../assets/image/logo.png" alt="Logo McDonald's">

            <h2>acDonald's</h2>

        </div>



        <ul class="menu">

            <li class="active">

                <a href="index.php?action=dashboard">

                    <i class="fas fa-chart-line"></i>

                    Dashboard

                </a>

            </li>



            <li>

                <a href="index.php?action=productos">

                    <i class="fas fa-box"></i>

                    Productos

                </a>

            </li>



            <li>

                <a href="index.php?action=clientes">

                    <i class="fas fa-users"></i>

                    Clientes

                </a>

            </li>



            <li>

                <a href="index.php?action=empleados">

                    <i class="fas fa-user-tie"></i>

                    Empleados

                </a>

            </li>



            <li>

                <a href="index.php?action=pedidos">

                    <i class="fas fa-shopping-cart"></i>

                    Pedidos

                </a>

            </li>



            <li>

                <a href="index.php?action=facturacion">

                    <i class="fas fa-file-invoice-dollar"></i>

                    Facturación

                </a>

            </li>



            <li>

                <a href="index.php?action=inventario">

                    <i class="fas fa-warehouse"></i>

                    Inventario

                </a>

            </li>



            <li>

                <a href="index.php?action=reporte_ventas">

                    <i class="fas fa-chart-pie"></i>

                    Reportes

                </a>

            </li>



            <li>

                <a href="index.php?action=configuracion">

                    <i class="fas fa-cog"></i>

                    Configuración

                </a>

            </li>

           

            </ul>



    </div>



    <div class="main-content">



        <div class="navbar">

            <h1>Sistema de Gestión McDonald's</h1>

            <div class="top-info">

                <div id="clock"></div>

                <div class="user">

                    <i class="fas fa-user-circle"></i>

                    <span>

                        <?php echo isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : 'Admin'; ?>

                    </span>

                </div>

            </div>

        </div>



        <div class="welcome-box">

            <h2>Panel de Gestión McDonald's</h2>

            <p>Gestiona tu empresa de manera inteligente y profesional.</p>

        </div>



        <div class="cards">

            <div class="card">

                <i class="fas fa-users"></i>

                <h3>Clientes</h3>

                <h2>520</h2>

            </div>



            <div class="card">

                <i class="fas fa-box"></i>

                <h3>Productos</h3>

                <h2>210</h2>

            </div>



            <div class="card">

                <i class="fas fa-shopping-cart"></i>

                <h3>Pedidos</h3>

                <h2>87</h2>

            </div>



            <div class="card">

                <i class="fas fa-dollar-sign"></i>

                <h3>Ventas</h3>

                <h2>S/ 12,500</h2>

            </div>

            </div>



    </div> </div> <div class="btn-salir-flotante">

    <a href="index.php?action=logout">

        <i class="fas fa-sign-out-alt"></i>

        SALIR

    </a>

</div>


<script src="../assets/js/main.js"></script>


</body>

</html> 

