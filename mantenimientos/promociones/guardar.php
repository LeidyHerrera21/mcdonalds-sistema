<?php

include("../../config/MysqlDB.php");

$nombre = $_POST['nombre'];

$sql = "INSERT INTO promociones(nombre)
VALUES('$nombre')";

mysqli_query($conn, $sql);

header("Location:index.php");

?>