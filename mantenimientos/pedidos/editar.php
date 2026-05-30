<?php
// Forzar visualización de errores por si necesitas diagnosticar algo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../config/MysqlDB.php");

if (!isset($conn_mysql)) {
    die("Error en el sistema: La variable de conexión \$conn_mysql no está definida.");
}

// 1. Recibimos el parámetro 'id' (IDPEDIDO) desde la URL
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if (empty($id)) {
    die("Error: No se proporcionó un ID de Pedido válido.");
}

try {
    // 2. Traemos los datos actuales desde MySQL usando la estructura de PEDIDO
    $sql = "SELECT IDPEDIDO, FECHA_PEDIDO, TOTAL, IDCLIENTE, IDEMPLEADO, IDSUCURSAL FROM PEDIDO WHERE IDPEDIDO = :id";
    $stmt = $conn_mysql->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fila) {
        die("Error: El registro de Pedido seleccionado no existe.");
    }

    // 3. Procesamos la actualización cuando el usuario presiona el botón "Actualizar"
    if (isset($_POST['actualizar'])) {
        $fecha_pedido = isset($_POST['fecha_pedido']) ? trim($_POST['fecha_pedido']) : '';
        $total        = isset($_POST['total']) ? trim($_POST['total']) : '';
        $idcliente    = isset($_POST['idcliente']) ? trim($_POST['idcliente']) : '';
        $idempleado   = isset($_POST['idempleado']) ? trim($_POST['idempleado']) : '';
        $idsucursal   = isset($_POST['idsucursal']) ? trim($_POST['idsucursal']) : '';

        // Validamos que los campos requeridos no estén vacíos y tengan formatos lógicos
        if (!empty($fecha_pedido) && !empty($total) && is_numeric($total) && !empty($idcliente) && !empty($idempleado) && !empty($idsucursal)) {
            
            $update = "UPDATE PEDIDO 
                       SET FECHA_PEDIDO = :fecha_pedido, TOTAL = :total, IDCLIENTE = :idcliente, IDEMPLEADO = :idempleado, IDSUCURSAL = :idsucursal 
                       WHERE IDPEDIDO = :id";
                       
            $stmtUpdate = $conn_mysql->prepare($update);
            $stmtUpdate->bindParam(':fecha_pedido', $fecha_pedido, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':total', $total, PDO::PARAM_STR); // DECIMAL como string para cuidar precisión
            $stmtUpdate->bindParam(':idcliente', $idcliente, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':idempleado', $idempleado, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':idsucursal', $idsucursal, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtUpdate->execute();

            // Redireccionamos de vuelta al index del mantenimiento de pedidos
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Por favor, complete todos los campos con formatos válidos.');</script>";
        }
    }

} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pedido</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #2b0000;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .edit-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .titulo {
            color: #b30000;
            margin-bottom: 20px;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-estatica {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #b30000;
            font-size: 14px;
            color: #555;
            line-height: 1.6;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #333;
            font-weight: bold;
            font-size: 14px;
        }

        input[type="text"], input[type="number"], input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
            margin-bottom: 15px;
        }

        .btn-group {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-actualizar {
            background: #009933;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-actualizar:hover {
            background: green;
        }

        .btn-cancelar {
            background: #111;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
        }

        .btn-cancelar:hover {
            background: #333;
        }
    </style>
</head>
<body>

<div class="edit-container">
    <h2 class="titulo">
        <i class="fa-solid fa-pen-to-square"></i> Editar Pedido
    </h2>

    <div class="info-estatica">
        <strong>ID Pedido:</strong> <?php echo htmlspecialchars($fila['IDPEDIDO']); ?>
    </div>

    <form method="POST">
        <label for="fecha_pedido">Fecha de Pedido:</label>
        <input type="date" 
               id="fecha_pedido"
               name="fecha_pedido" 
               value="<?php echo isset($fila['FECHA_PEDIDO']) ? htmlspecialchars($fila['FECHA_PEDIDO']) : ''; ?>" 
               required>

        <label for="total">Total (S/.):</label>
        <input type="number" 
               id="total"
               name="total" 
               step="0.01"
               min="0.00"
               value="<?php echo isset($fila['TOTAL']) ? htmlspecialchars($fila['TOTAL']) : ''; ?>" 
               required>

        <label for="idcliente">ID Cliente:</label>
        <input type="number" 
               id="idcliente"
               name="idcliente" 
               value="<?php echo isset($fila['IDCLIENTE']) ? htmlspecialchars($fila['IDCLIENTE']) : ''; ?>" 
               min="1"
               required>

        <label for="idempleado">ID Empleado:</label>
        <input type="number" 
               id="idempleado"
               name="idempleado" 
               value="<?php echo isset($fila['IDEMPLEADO']) ? htmlspecialchars($fila['IDEMPLEADO']) : ''; ?>" 
               min="1"
               required>

        <label for="idsucursal">ID Sucursal:</label>
        <input type="number" 
               id="idsucursal"
               name="idsucursal" 
               value="<?php echo isset($fila['IDSUCURSAL']) ? htmlspecialchars($fila['IDSUCURSAL']) : ''; ?>" 
               min="1"
               required>

        <div class="btn-group">
            <a class="btn-cancelar" href="index.php">
                Cancelar
            </a>
            <button class="btn-actualizar" type="submit" name="actualizar">
                <i class="fa-solid fa-sync"></i> Actualizar
            </button>
        </div>
    </form>
</div>

</body>
</html>