<?php
include("../../config/MysqlDB.php");

// Verificamos que la variable de conexión PDO exista
if (!isset($conn_mysql)) {
    die("Error: La variable \$conn_mysql no está definida en MysqlDB.php");
}

try {
    // Consulta adaptada a la estructura de tu tabla EMPLEADO
    $sql = "SELECT IDEMPLEADO, NOMBRE, IDSUCURSAL FROM EMPLEADO";
    $stmt = $conn_mysql->prepare($sql);
    $stmt->execute();
    // Obtenemos todas las filas en un array asociativo
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Mantenimiento de Empleados</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
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
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
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

/* FORMULARIO ADAPTADO MULTI-CAMPO */
form{
    display:flex;
    flex-wrap: wrap;
    gap:10px;
    margin-bottom:25px;
    background: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #eee;
}

input{
    padding:10px;
    border:1px solid #ccc;
    border-radius:5px;
}

.input-nombre{
    width:280px;
}

.input-sucursal{
    width:150px;
}

.btn-guardar{
    background:#009933;
    color:white;
    border:none;
    padding:10px 20px;
    border-radius:5px;
    cursor:pointer;
    font-weight: bold;
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
    color: #111;
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
    font-weight: bold;
    font-size: 14px;
}

.btn-eliminar{
    background:red;
    color:white;
    padding:6px 10px;
    text-decoration:none;
    border-radius:5px;
    font-weight: bold;
    font-size: 14px;
}

.logo{
    color:#ffd633;
    font-size:28px;
    margin-bottom:20px;
    display: flex;
    align-items: center;
    gap: 10px;
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
    <h1 class="titulo">Mantenimiento de Empleados</h1>
    <a class="btn-regresar" href="../../principal/dashboard.php">
        <i class="fa-solid fa-arrow-left"></i> Regresar
    </a>
</div>

<form action="registrar_empleado.php" method="POST">
    <input type="text" class="input-nombre" name="nombre" placeholder="Nombre completo del empleado" required>
    <input type="number" class="input-sucursal" name="idsucursal" placeholder="ID Sucursal" min="1" required>
    <button class="btn-guardar" type="submit">
        <i class="fa-solid fa-floppy-disk"></i> Grabar
    </button>
</form>

<table>
<tr>
    <th>ID Empleado</th>
    <th>Nombre Completo</th>
    <th>ID Sucursal</th>
    <th>Acciones</th>
</tr>

<?php if (!empty($resultados)): ?>
    <?php foreach ($resultados as $fila): ?>
    <tr>
        <td><?php echo htmlspecialchars($fila['IDEMPLEADO']); ?></td>
        <td><?php echo htmlspecialchars($fila['NOMBRE']); ?></td>
        <td><?php echo htmlspecialchars($fila['IDSUCURSAL']); ?></td>
        <td>
            <a class="btn-editar" href="editar_empleado.php?id=<?php echo $fila['IDEMPLEADO']; ?>">Editar</a>
            <a class="btn-eliminar" href="eliminar_empleado.php?id=<?php echo $fila['IDEMPLEADO']; ?>" onclick="return confirm('¿Estás seguro de eliminar a este empleado?');">Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" style="text-align: center; color: #666;">No se encontraron empleados registrados.</td>
    </tr>
<?php endif; ?>
</table>

</div>

</body>
</html>