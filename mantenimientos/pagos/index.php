<?php
include("../../config/MysqlDB.php");

// Verificamos que la variable de conexión PDO exista
if (!isset($conn_mysql)) {
    die("Error: La variable \$conn_mysql no está definida en MysqlDB.php");
}

try {
    // Consulta adaptada a minúsculas
    $sql = "SELECT idpago, monto, fechapago, idpedido, idmetodo FROM pago";
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

<title>Mantenimiento de Pagos</title>

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
    width:180px;
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
    <h1 class="titulo">Mantenimiento de Pagos</h1>
    <a class="btn-regresar" href="../../principal/dashboard.php">
        <i class="fa-solid fa-arrow-left"></i> Regresar
    </a>
</div>

<form action="registrar_pago.php" method="POST">
    <input type="number" name="monto" placeholder="Monto (S/. )" step="0.01" min="0.10" required>
    <input type="date" name="fechapago" required>
    <input type="number" name="idpedido" placeholder="ID Pedido" min="1" required>
    <input type="number" name="idmetodo" placeholder="ID Método" min="1" required>
    <button class="btn-guardar" type="submit">
        <i class="fa-solid fa-floppy-disk"></i> Grabar
    </button>
</form>

<table>
<tr>
    <th>ID Pago</th>
    <th>Monto</th>
    <th>Fecha Pago</th>
    <th>ID Pedido</th>
    <th>ID Método</th>
    <th>Acciones</th>
</tr>

<?php if (!empty($resultados)): ?>
    <?php foreach ($resultados as $fila): ?>
    <tr>
        <td><?php echo htmlspecialchars($fila['idpago']); ?></td>
        <td>S/. <?php echo htmlspecialchars(number_format($fila['monto'], 2)); ?></td>
        <td><?php echo htmlspecialchars($fila['fechapago']); ?></td>
        <td><?php echo htmlspecialchars($fila['idpedido']); ?></td>
        <td><?php echo htmlspecialchars($fila['idmetodo']); ?></td>
        <td>
            <a class="btn-editar" href="editar_pago.php?id=<?php echo $fila['idpago']; ?>">Editar</a>
            <a class="btn-eliminar" href="eliminar_pago.php?id=<?php echo $fila['idpago']; ?>" onclick="return confirm('¿Estás seguro de eliminar este registro de pago?');">Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="6" style="text-align: center; color: #666;">No se encontraron registros de pagos.</td>
    </tr>
<?php endif; ?>
</table>

</div>

</body>
</html>