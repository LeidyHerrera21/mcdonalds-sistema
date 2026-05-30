<?php

include("../../config/MysqlDB.php");

$id = $_GET['id'];

$sql = "SELECT * FROM proveedores
WHERE id='$id'";

$resultado = mysqli_query($conn, $sql);

$fila = mysqli_fetch_assoc($resultado);

if(isset($_POST['actualizar'])){

    $nombre = $_POST['nombre'];

    $update = "UPDATE proveedores
    SET nombre='$nombre'
    WHERE id='$id'";

    mysqli_query($conn, $update);

    header("Location:index.php");
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar</title>
</head>
<body>

<h1>Editar Proveedores</h1>

<form method="POST">

    <input type="text"
    name="nombre"
    value="<?php echo $fila['nombre']; ?>">

    <button type="submit" name="actualizar">
        Actualizar
    </button>

</form>

</body>
</html>