<?php
include("../../config/MysqlDB.php");

$sql = "SELECT * FROM promociones";
$resultado = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Promociones</title>

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

/* CONTENEDOR */

.container{
    width:100%;
    background:white;
    border-radius:15px;
    padding:30px;
}

/* TITULO */

.titulo{
    color:#b30000;
    margin-bottom:20px;
}

/* BOTONES */

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

.btn-regresar:hover{
    background:#333;
}

/* FORMULARIO */

form{
    display:flex;
    gap:10px;
    margin-bottom:20px;
}

input{
    padding:10px;
    border:1px solid #ccc;
    border-radius:5px;
    width:300px;
}

.btn-guardar{
    background:#009933;
    color:white;
    border:none;
    padding:10px 20px;
    border-radius:5px;
    cursor:pointer;
}

.btn-guardar:hover{
    background:green;
}

/* TABLA */

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#ffd633;
    padding:12px;
    text-align:left;
}

table td{
    padding:12px;
    border-bottom:1px solid #ccc;
}

/* BOTONES TABLA */

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
    Mantenimiento Promociones
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
placeholder="Ingrese promoción"
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
    <th>Acciones</th>
</tr>

<?php while($fila=mysqli_fetch_assoc($resultado)){ ?>

<tr>

<td>
<?php echo $fila['id']; ?>
</td>

<td>
<?php echo $fila['nombre']; ?>
</td>

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