<?php
// Forzar visualización de errores por si necesitas diagnosticar algo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../config/MysqlDB.php");

if (!isset($conn_mysql)) {
    die("Error en el sistema: La variable de conexión \$conn_mysql no está definida.");
}

// 1. Recibimos el parámetro 'id' (IDDETALLE) desde la URL
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if (empty($id)) {
    die("Error: No se proporcionó un ID de Detalle de Pedido válido.");
}

try {
    // 2. Traemos los datos actuales desde MySQL usando la estructura de DETALLEPEDIDO
    $sql = "SELECT IDDETALLE, IDPEDIDO, IDPRODUCTO, CANTIDAD FROM DETALLEPEDIDO WHERE IDDETALLE = :id";
    $stmt = $conn_mysql->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fila) {
        die("Error: El Detalle de Pedido seleccionado no existe.");
    }

    // 3. Procesamos la actualización cuando el usuario presiona el botón "Actualizar"
    if (isset($_POST['actualizar'])) {
        $cantidad = isset($_POST['cantidad']) ? trim($_POST['cantidad']) : '';

        if (!empty($cantidad) && is_numeric($cantidad)) {
            $update = "UPDATE DETALLEPEDIDO SET CANTIDAD = :cantidad WHERE IDDETALLE = :id";
            $stmtUpdate = $conn_mysql->prepare($update);
            $stmtUpdate->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtUpdate->execute();

            // Redireccionamos de vuelta al index del mantenimiento
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('La cantidad debe ser un número válido y no puede estar vacía.');</script>";
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
    <title>Editar Detalle de Pedido</title>
    
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
            height: 100vh;
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
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .btn-group {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
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
        <i class="fa-solid fa-pen-to-square"></i> Editar Detalle Pedido
    </h2>

    <div class="info-estatica">
        <strong>ID Detalle:</strong> <?php echo htmlspecialchars($fila['IDDETALLE']); ?><br>
        <strong>ID Pedido:</strong> <?php echo htmlspecialchars($fila['IDPEDIDO']); ?><br>
        <strong>ID Producto:</strong> <?php echo htmlspecialchars($fila['IDPRODUCTO']); ?>
    </div>

    <form method="POST">
        <label for="cantidad">Cantidad del Producto:</label>
        <input type="number" 
               id="cantidad"
               name="cantidad" 
               value="<?php echo isset($fila['CANTIDAD']) ? htmlspecialchars($fila['CANTIDAD']) : ''; ?>" 
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