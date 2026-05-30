<?php

include("../../config/MysqlDB.php");

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];

$sql = "INSERT INTO clientes
(nombre, apellido, telefono, correo)

VALUES

('$nombre', '$apellido', '$telefono', '$correo')";

mysqli_query($conn, $sql);

header("Location:index.php");

?>