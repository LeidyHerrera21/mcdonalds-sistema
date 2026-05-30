<?php
// Forzar visualización de errores por si necesitas diagnosticar algo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../config/MysqlDB.php");

if (!isset($conn_mysql)) {
    die("Error en el sistema: La variable de conexión \$conn_mysql no está definida.");
}

// 1. Recibimos los parámetros originales de la clave compuesta desde la URL (GET)
$idempleado_old = isset($_GET['idempleado']) ? trim($_GET['idempleado']) : '';
$idturno_old    = isset($_GET['idturno'])    ? trim($_GET['idturno'])    : '';

if (empty($idempleado_old) || empty($idturno_old)) {
    die("Error: No se proporcionaron los identificadores válidos para la asignación.");
}

try {
    // 2. Traemos los datos actuales desde MySQL para verificar que la asignación exista
    $sql = "SELECT IDEMPLEADO, IDTURNO FROM EMPLEADO_TURNO WHERE IDEMPLEADO = :idempleado_old AND IDTURNO = :idturno_old";
    $stmt = $conn_mysql->prepare($sql);
    $stmt->bindParam(':idempleado_old', $idempleado_old, PDO::PARAM_INT);
    $stmt->bindParam(':idturno_old', $idturno_old, PDO::PARAM_INT);
    $stmt->execute();
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fila) {
        die("Error: El registro de asignación seleccionado no existe.");
    }

    // 3. Procesamos la actualización cuando el usuario presiona el botón "Actualizar"
    if (isset($_POST['actualizar'])) {
        $idempleado_new = isset($_POST['idempleado']) ? trim($_POST['idempleado']) : '';
        $idturno_new    = isset($_POST['idturno'])    ? trim($_POST['idturno'])    : '';

        // Validamos que los campos requeridos no estén vacíos
        if (!empty($idempleado_new) && !empty($idturno_new)) {
            
            // El UPDATE modifica las llaves usando las llaves antiguas en el WHERE
            $update = "UPDATE EMPLEADO_TURNO 
                       SET IDEMPLEADO = :idempleado_new, IDTURNO = :idturno_new 
                       WHERE IDEMPLEADO = :idempleado_old AND IDTURNO = :idturno_old";
                       
            $stmtUpdate = $conn_mysql->prepare($update);
            
            // Nuevos valores a asignar
            $stmtUpdate->bindParam(':idempleado_new', $idempleado_new, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':idturno_new', $idturno_new, PDO::PARAM_INT);
            
            // Valores antiguos para ubicar la fila exacta
            $stmtUpdate->bindParam(':idempleado_old', $idempleado_old, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':idturno_old', $idturno_old, PDO::PARAM_INT);
            
            $stmtUpdate->execute();

            // Redireccionamos de vuelta al index del mantenimiento
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Por favor, complete todos los campos con formatos válidos.');</script>";
        }
    }

} catch (PDOException $e) {
    // Captura errores por si se intenta duplicar una combinación existente o violar llaves foráneas
    die("Error en la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Asignación de Turno</title>
    
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
            padding: 12px;
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

        input[type="number"] {
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
        <i class="fa-solid fa-pen-to-square"></i> Editar Asignación
    </h2>

    <div class="info-estatica">
        <strong>Valores Actuales:</strong><br>
        ID Empleado: <?php echo htmlspecialchars($fila['IDEMPLEADO']); ?><br>
        ID Turno: <?php echo htmlspecialchars($fila['IDTURNO']); ?>
    </div>

    <form method="POST">
        <label for="idempleado">Nuevo ID Empleado:</label>
        <input type="number" 
               id="idempleado"
               name="idempleado" 
               value="<?php echo htmlspecialchars($fila['IDEMPLEADO']); ?>" 
               min="1"
               required>

        <label for="idturno">Nuevo ID Turno:</label>
        <input type="number" 
               id="idturno"
               name="idturno" 
               value="<?php echo htmlspecialchars($fila['IDTURNO']); ?>" 
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