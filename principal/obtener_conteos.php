<?php
// Habilitamos visualización de errores para ver qué pasa si algo falla
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conectamos a tu archivo real de MySQL
include '../config/MysqlDB.php'; 

// Preparamos el array base con tus tablas mapeadas
$conteos = [
    'clientes'        => 0,
    'costo_productos' => 0,
    'categoria'       => 0,
    'insumos'         => 0,
    'precios'         => 0,
    'promociones'     => 0,
    'proveedores'     => 0,
    'sucursales'      => 0
];

// REVISIÓN CRÍTICA: En los archivos de conexión de XAMPP es muy común usar
// la variable $conn o la variable $conexion. Evaluamos cuál tienes activa:
$link = null;
if (isset($conn)) { $link = $conn; } 
elseif (isset($conexion)) { $link = $conexion; }

if ($link) {
    // 1. Contar Clientes
    $res = mysqli_query($link, "SELECT COUNT(*) AS total FROM clientes");
    if ($res) { $r = mysqli_fetch_assoc($res); $conteos['clientes'] = $r['total']; }

    // 2. Contar Categorías
    $res = mysqli_query($link, "SELECT COUNT(*) AS total FROM categoria");
    if ($res) { $r = mysqli_fetch_assoc($res); $conteos['categoria'] = $r['total']; }

    // 3. Contar Insumos
    $res = mysqli_query($link, "SELECT COUNT(*) AS total FROM insumos");
    if ($res) { $r = mysqli_fetch_assoc($res); $conteos['insumos'] = $r['total']; }

    // 4. Contar Precios
    $res = mysqli_query($link, "SELECT COUNT(*) AS total FROM precios");
    if ($res) { $r = mysqli_fetch_assoc($res); $conteos['precios'] = $r['total']; }

    // 5. Contar Promociones
    $res = mysqli_query($link, "SELECT COUNT(*) AS total FROM promociones");
    if ($res) { $r = mysqli_fetch_assoc($res); $conteos['promociones'] = $r['total']; }

    // 6. Contar Proveedores
    $res = mysqli_query($link, "SELECT COUNT(*) AS total FROM proveedores");
    if ($res) { $r = mysqli_fetch_assoc($res); $conteos['proveedores'] = $r['total']; }

    // 7. Contar Sucursales
    $res = mysqli_query($link, "SELECT COUNT(*) AS total FROM sucursales");
    if ($res) { $r = mysqli_fetch_assoc($res); $conteos['sucursales'] = $r['total']; }

    // 8. Contar Costos Producto (Tu tabla se llama exactamente 'costos_producto' en phpMyAdmin)
    $res = mysqli_query($link, "SELECT COUNT(*) AS total FROM costos_producto");
    if ($res) { $r = mysqli_fetch_assoc($res); $conteos['costo_productos'] = $r['total']; }

    mysqli_close($link);
} else {
    // Si no detectó la conexión, dejamos un mensaje de aviso oculto en el JSON
    $conteos['error'] = "No se detectó la variable de conexión ($conn o $conexion) en MysqlDB.php";
}

// Retornamos el JSON limpio para JavaScript
header('Content-Type: application/json');
echo json_encode($conteos);
?>