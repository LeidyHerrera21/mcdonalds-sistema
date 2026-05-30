<?php

include("../../config/MysqlDB.php");

$nombre_producto = $_POST['nombre_producto'];
$costo = $_POST['costo'];
$precio = $_POST['precio'];

$sql = "INSERT INTO costos_producto
(nombre_producto, costo, precio)

VALUES

('$nombre_producto', '$costo', '$precio')";

mysqli_query($conn, $sql);

header("Location:index.php");

?>