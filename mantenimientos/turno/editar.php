<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../config/MysqlDB.php");

if (!isset($conn_mysql)) {
    die("Error en el sistema: La variable de conexión \$conn_mysql no está definida.");
}

$idempleado_old = isset($_GET['idempleado']) ? trim($_GET['idempleado']) : '';
$idturno_old    = isset($_GET['idturno'])    ? trim($_GET['idturno'])    : '';

if (empty($idempleado_old) || empty($idturno_old)) {
    die("Error: No se proporcionaron los identificadores válidos para la asignación.");
}

try {
    $sql = "SELECT idempleado, idturno FROM empleado_turno WHERE idempleado = :idempleado_old AND idturno = :idturno_old";
    $stmt = $conn_mysql->prepare($sql);
    $stmt->bindParam(':idempleado_old', $idempleado_old, PDO::PARAM_INT);
    $stmt->bindParam(':idturno_old', $idturno_old, PDO::PARAM_INT);
    $stmt->execute();
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fila) {
        die("Error: El registro de asignación seleccionado no existe.");
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
    <title>Editar Asignación de Turno</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #2b0000; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .edit-container { background: white; padding: 30px; border-radius: 15px; width: 100%; max-width: 450px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
        .titulo { color: #b30000; margin-bottom: 20px; font-size: 24px; display: flex; align-items: center; gap: 10px; }
        .info-estatica { background: #f5f5f5; padding: 12px; border-radius: 5px; margin-bottom: 15px; border-left: 4px solid #ffd633; font-size: 14px; color: #555; line-height: 1.6; }
        label { display: block; margin-bottom: 6px; color: #333; font-weight: bold; font-size: 14px; }
        input[type="number"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 15px; margin-bottom: 15px; }
        .btn-group { display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px; }
        .btn-actualizar { background: #009933; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn-actualizar:hover { background: green; }
        .btn-cancelar { background: #111; color: white; padding: 10px 15px; border-radius: 8px; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; }
        .btn-cancelar:hover { background: #333; }
    </style>
</head>
<body>

<div class="edit-container">
    <h2 class="titulo">
        <i class="fa-solid fa-pen-to-square"></i> Editar Asignación
    </h2>

    <div class="info-estatica">
        <strong>Valores Actuales:</strong><br>
        ID Empleado: <?php echo htmlspecialchars($fila['idempleado']); ?><br>
        ID Turno: <?php echo htmlspecialchars($fila['idturno']); ?>
    </div>

    <form action="guardar.php" method="POST">
        <input type="hidden" name="idempleado_old" value="<?php echo htmlspecialchars($fila['idempleado']); ?>">
        <input type="hidden" name="idturno_old" value="<?php echo htmlspecialchars($fila['idturno']); ?>">

        <label for="idempleado">Nuevo ID Empleado:</label>
        <input type="number" id="idempleado" name="idempleado" value="<?php echo htmlspecialchars($fila['idempleado']); ?>" min="1" required>

        <label for="idturno">Nuevo ID Turno:</label>
        <input type="number" id="idturno" name="idturno" value="<?php echo htmlspecialchars($fila['idturno']); ?>" min="1" required>

        <div class="btn-group">
            <a class="btn-cancelar" href="index.php">Cancelar</a>
            <button class="btn-actualizar" type="submit" name="actualizar">
                <i class="fa-solid fa-sync"></i> Actualizar
            </button>
        </div>
    </form>
</div>

</body>
</html>