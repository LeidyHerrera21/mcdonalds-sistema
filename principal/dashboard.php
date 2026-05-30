<?php
/*
// 1. GESTIÓN DE SESIÓN Y SEGURIDAD DIRECTA
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login/index.php");
    exit();
}
*/
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

    <a href="../mantenimientos/clientes/index.php">

        <i class="fas fa-users"></i>

                    Clientes

                </a>

            </li>



            <li>

    <a href="../mantenimientos/insumos/index.php">

        <i class="fas fa-boxes-stacked"></i>

        Insumos

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

    <a href="../mantenimientos/costos_producto/index.php">

        <i class="fa-solid fa-money-bill-wave"></i>

        Costos Producto

    </a>

</li>



            <li>

    <a href="../mantenimientos/categoria/index.php">

        <i class="fa-solid fa-layer-group"></i>

        Categoria

    </a>

</li>

 <li>

    <a href="../mantenimientos/precios/index.php">

        <i class="fa-solid fa-tags"></i>

        Precios

    </a>

</li>
           <li>

    <a href="../mantenimientos/proveedores/index.php">

        <i class="fa-solid fa-dolly"></i>

        Proveedores

    </a>

</li>

            

          <li>

    <a href="../mantenimientos/promociones/index.php">

        <i class="fas fa-chart-pie"></i>

        Promociones

    </a>

</li>
          <li>
    <a href="../mantenimientos/sucursales/index.php">
        <i class="fa-solid fa-store"></i>
        Sucursales
    </a>
</li>

<li>
    <a href="../mantenimientos/empleados/index.php">
        <i class="fa-regular fa-address-book"></i>
        Empleados
    </a>
</li>

<li>
    <a href="../mantenimientos/inventarios/index.php">
        <i class="fa-solid fa-cart-flatbed"></i>
        Inventarios
    </a>
</li>

<li>
    <a href="../mantenimientos/pagos/index.php">
    <i class="fa-solid fa-hand-holding-dollar"></i>
        Pagos
    </a>
</li>

<li>
    <a href="../mantenimientos/detalle/index.php">
    <i class="fa-regular fa-folder"></i>
        Detalle de Pedidos
    </a>
</li>
            <li>

                <a href="index.php?action=configuracion">

                    <i class="fas fa-cog"></i>

                    Configuración

                </a>

            </li>

           <li>

                <a href="index.php?action=configuracion">

                    <i class="fa-solid fa-right-from-bracket"></i>

                    Cerrar Sesion

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
    <div class="card" data-reporte="clientes"> 
        <i class="fas fa-users"></i>
        <h3>Clientes</h3>
        <h2 id="num-clientes">...</h2>
    </div>

    <div class="card" data-reporte="costo_productos">
        <i class="fa-solid fa-money-bill-wave"></i>
        <h3>Costo Productos</h3>
        <h2 id="num-costo_productos">...</h2>
    </div>

    <div class="card" data-reporte="categoria"> 
        <i class="fa-solid fa-layer-group"></i>
        <h3>Categoria</h3>
        <h2 id="num-categoria">...</h2>
    </div>

    <div class="card" data-reporte="insumos">
        <i class="fas fa-boxes-stacked"></i>
        <h3>Insumos</h3>
        <h2 id="num-insumos">...</h2>
    </div>

    <div class="card" data-reporte="precios">
        <i class="fa-solid fa-tags"></i>
        <h3>Precios</h3>
        <h2 id="num-precios">...</h2>
    </div>

    <div class="card" data-reporte="promociones">
        <i class="fas fa-chart-pie"></i>
        <h3>Promociones</h3>
        <h2 id="num-promociones">...</h2>
    </div>

    <div class="card" data-reporte="proveedores">
        <i class="fa-solid fa-dolly"></i>
        <h3>Proveedores</h3>
        <h2 id="num-proveedores">...</h2>
    </div>

    <div class="card" data-reporte="sucursales">
        <i class="fa-solid fa-store"></i>
        <h3>Sucursales</h3>
        <h2 id="num-sucursales">...</h2>
    </div>
    
    <div class="card" data-id="productos">
        <i class="fas fa-box"></i>
        <h3>Productos</h3>
        <h2 id="num-productos">...</h2>
    </div>

    <div class="card" data-id="pedidos">
        <i class="fas fa-shopping-cart"></i>
        <h3>Pedidos</h3>
        <h2 id="num-pedidos">...</h2>
    </div>

    <div class="card" data-id="ventas">
        <i class="fas fa-dollar-sign"></i>
        <h3>Ventas</h3>
        <h2 id="num-ventas">...</h2>
    </div>
    <div class="card" data-id="empleados">
    <i class="fa-regular fa-address-book"></i>
        <h3>Empleados</h3>
        <h2 id="num-empleados">...</h2>
    </div>
    <div class="card" data-id="inventarios">
         <i class="fa-solid fa-cart-flatbed"></i>
        <h3>Inventarios</h3>
        <h2 id="num-inventarios">...</h2>
    </div>
    <div class="card" data-id="pagos">
        <i class="fa-solid fa-hand-holding-dollar"></i>
        <h3>Pagos</h3>
        <h2 id="num-pagos">...</h2>
    </div>
    <div class="card" data-id="detalle">
        <i class="fa-regular fa-folder"></i>
        <h3> Detalle de Pedidos</h3>
        <h2 id="num-detalle">...</h2>
    </div>
</div>


  
   

<button class="chatbot-btn" onclick="toggleChatbot()">
    <i class="fas fa-comments"></i>
</button>

<div class="chatbot-container" id="chatbot">

    <div class="chatbot-header">
        <h2>🍔 McDonald's Bot</h2>
        <p>Asistente Virtual</p>
    </div>

    <div class="chatbot-box" id="chatbox">

        <div class="message bot">
            👋 Hola, bienvenido a McDonald's.<br>
            ¿Cómo puedo ayudarte?
        </div>

    </div>

    <div class="chatbot-input">
        <input type="text" id="userInput" placeholder="Escribe un mensaje...">

        <button onclick="sendMessage()">
            <i class="fas fa-paper-plane"></i>
        </button>

</div>

</div>

<script src="../assets/js/main.js"></script>


</body>

</html> 

