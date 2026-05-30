<?php
// Forzar visualización de errores por si necesitas diagnosticar algo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../config/MysqlDB.php");

if (!isset($conn_mysql)) {
    die("Error en el sistema: La variable de conexión \$conn_mysql no está definida.");
}

// 1. Recibimos el parámetro 'id' (IDPRODUCTO) desde la URL
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if (empty($id)) {
    die("Error: No se proporcionó un ID de Producto válido.");
}

try {
    // 2. Traemos los datos actuales desde MySQL usando la estructura de PRODUCTO
    $sql = "SELECT IDPRODUCTO, NOMBRE, PRECIO, IDCATEGORIA, IDPROVEEDOR FROM PRODUCTO WHERE IDPRODUCTO = :id";
    $stmt = $conn_mysql->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fila) {
        die("Error: El registro de Producto seleccionado no existe.");
    }

    // 3. Procesamos la actualización cuando el usuario presiona el botón "Actualizar"
    if (isset($_POST['actualizar'])) {
        $nombre      = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
        $precio      = isset($_POST['precio']) ? trim($_POST['precio']) : '';
        $idcategoria = isset($_POST['idcategoria']) ? trim($_POST['idcategoria']) : '';
        $idproveedor = isset($_POST['idproveedor']) ? trim($_POST['idproveedor']) : '';

        // Validamos que los campos requeridos no estén vacíos y tengan formatos válidos
        if (!empty($nombre) && !empty($precio) && is_numeric($precio) && !empty($idcategoria) && !empty($idproveedor)) {
            
            $update = "UPDATE PRODUCTO 
                       SET NOMBRE = :nombre, PRECIO = :precio, IDCATEGORIA = :idcategoria, IDPROVEEDOR = :idproveedor 
                       WHERE IDPRODUCTO = :id";
                       
            $stmtUpdate = $conn_mysql->prepare($update);
            $stmtUpdate->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':precio', $precio, PDO::PARAM_STR); // DECIMAL se pasa como string para mantener precisión
            $stmtUpdate->bindParam(':idcategoria', $idcategoria, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':idproveedor', $idproveedor, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtUpdate->execute();

            // Redireccionamos de vuelta al index del mantenimiento de productos
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
    <title>Editar Producto</title>
    
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
            border-left: 4px solid #ffd633;
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

        input[type="text"], input[type="number"] {
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
        <i class="fa-solid fa-pen-to-square"></i> Editar Producto
    </h2>

    <div class="info-estatica">
        <strong>ID Producto:</strong> <?php echo htmlspecialchars($fila['IDPRODUCTO']); ?>
    </div>

    <form method="POST">
        <label for="nombre">Nombre del Producto:</label>
        <input type="text" 
               id="nombre"
               name="nombre" 
               value="<?php echo isset($fila['NOMBRE']) ? htmlspecialchars($fila['NOMBRE']) : ''; ?>" 
               placeholder="Ej. Big Mac Clásica"
               required>

        <label for="precio">Precio (S/.):</label>
        <input type="number" 
               id="precio"
               name="precio" 
               step="0.01"
               min="0.00"
               value="<?php echo isset($fila['PRECIO']) ? htmlspecialchars($fila['PRECIO']) : ''; ?>" 
               required>

        <label for="idcategoria">ID Categoría:</label>
        <input type="number" 
               id="idcategoria"
               name="idcategoria" 
               value="<?php echo isset($fila['IDCATEGORIA']) ? htmlspecialchars($fila['IDCATEGORIA']) : ''; ?>" 
               min="1"
               required>

        <label for="idproveedor">ID Proveedor:</label>
        <input type="number" 
               id="idproveedor"
               name="idproveedor" 
               value="<?php echo isset($fila['IDPROVEEDOR']) ? htmlspecialchars($fila['IDPROVEEDOR']) : ''; ?>" 
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