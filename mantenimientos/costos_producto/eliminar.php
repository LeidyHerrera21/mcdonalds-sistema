<?php

include("../../config/MysqlDB.php");

$id = $_GET['id'];

$sql = "DELETE FROM costos_producto
WHERE id='$id'";

mysqli_query($conn, $sql);

header("Location:index.php");

?>