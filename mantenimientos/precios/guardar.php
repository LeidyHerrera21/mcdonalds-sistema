<?php

include("../../config/MysqlDB.php");

$producto = $_POST['producto'];
$precio = $_POST['precio'];

$sql = "INSERT INTO precios
(producto, precio)

VALUES

('$producto', '$precio')";

mysqli_query($conn, $sql);

header("Location:index.php");

?>