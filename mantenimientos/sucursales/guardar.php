<?php

include("../../config/MysqlDB.php");

$nombre = mysqli_real_escape_string($conn, $_POST['nombre']);

$sql = "INSERT INTO sucursales(nombre)
VALUES('$nombre')";

mysqli_query($conn, $sql);

header("Location:index.php");

?>