<?php
include("../../config/MysqlDB.php");

$sql = "SELECT * FROM clientes";
$resultado = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Clientes</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    background:#2b0000;
    padding:30px;
}

.container{
    width:100%;
    background:white;
    border-radius:15px;
    padding:30px;
}

.titulo{
    color:#b30000;
    margin-bottom:20px;
}

.top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.btn-regresar{
    background:#111;
    color:white;
    padding:10px 15px;
    border-radius:8px;
    text-decoration:none;
}

form{
    display:flex;
    gap:10px;
    margin-bottom:20px;
    flex-wrap:wrap;
}

input{
    padding:10px;
    border:1px solid #ccc;
    border-radius:5px;
    width:220px;
}

.btn-guardar{
    background:#009933;
    color:white;
    border:none;
    padding:10px 20px;
    border-radius:5px;
    cursor:pointer;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#ffd633;
    padding:12px;
}

table td{
    padding:12px;
    border-bottom:1px solid #ccc;
}

.btn-editar{
    background:orange;
    color:black;
    padding:6px 10px;
    text-decoration:none;
    border-radius:5px;
}

.btn-eliminar{
    background:red;
    color:white;
    padding:6px 10px;
    text-decoration:none;
    border-radius:5px;
}

.logo{
    color:#ffd633;
    font-size:28px;
    margin-bottom:20px;
}

</style>

</head>
<body>

<div class="logo">
    <i class="fa-solid fa-burger"></i>
    McDonald's
</div>

<div class="container">

<div class="top">

<h1 class="titulo">
    Mantenimiento Clientes
</h1>

<a class="btn-regresar"
href="../../principal/dashboard.php">

<i class="fa-solid fa-arrow-left"></i>
Regresar

</a>

</div>

<form action="guardar.php" method="POST">

<input type="text"
name="nombre"
placeholder="Nombre"
required>

<input type="text"
name="apellido"
placeholder="Apellido"
required>

<input type="text"
name="telefono"
placeholder="Telefono"
required>

<input type="email"
name="correo"
placeholder="Correo"
required>

<button class="btn-guardar" type="submit">

<i class="fa-solid fa-floppy-disk"></i>
Grabar

</button>

</form>

<table>

<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Apellido</th>
    <th>Telefono</th>
    <th>Correo</th>
    <th>Acciones</th>
</tr>

<?php while($fila=mysqli_fetch_assoc($resultado)){ ?>

<tr>

<td><?php echo $fila['id']; ?></td>

<td><?php echo $fila['nombre']; ?></td>

<td><?php echo $fila['apellido']; ?></td>

<td><?php echo $fila['telefono']; ?></td>

<td><?php echo $fila['correo']; ?></td>

<td>

<a class="btn-editar"
href="editar.php?id=<?php echo $fila['id']; ?>">

Editar

</a>

<a class="btn-eliminar"
href="eliminar.php?id=<?php echo $fila['id']; ?>">

Eliminar

</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>