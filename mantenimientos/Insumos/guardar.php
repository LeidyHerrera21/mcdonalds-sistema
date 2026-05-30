<?php

include("../../config/MysqlDB.php");

$nombre = $_POST['nombre'];
$cantidad = $_POST['cantidad'];
$proveedor = $_POST['proveedor'];

$sql = "INSERT INTO Insumos
(nombre, cantidad, proveedor)

VALUES

('$nombre', '$cantidad', '$proveedor')";

mysqli_query($conn, $sql);

header("Location:index.php");

?>