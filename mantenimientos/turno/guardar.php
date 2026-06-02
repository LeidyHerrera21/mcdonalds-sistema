<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../config/MysqlDB.php");

if (!isset($conn_mysql)) {
    die("Error en el sistema: La variable de conexión \$conn_mysql no está definida.");
}

// CASO 1: PROCESAR ACTUALIZACIÓN (UPDATE)
if (isset($_POST['actualizar'])) {
    $idempleado_old = isset($_POST['idempleado_old']) ? trim($_POST['idempleado_old']) : '';
    $idturno_old    = isset($_POST['idturno_old'])    ? trim($_POST['idturno_old'])    : '';
    $idempleado_new = isset($_POST['idempleado'])     ? trim($_POST['idempleado'])     : '';
    $idturno_new    = isset($_POST['idturno'])        ? trim($_POST['idturno'])        : '';

    if (!empty($idempleado_old) && !empty($idturno_old) && !empty($idempleado_new) && !empty($idturno_new)) {
        try {
            $sql = "UPDATE empleado_turno 
                    SET idempleado = :idempleado_new, idturno = :idturno_new 
                    WHERE idempleado = :idempleado_old AND idturno = :idturno_old";
            
            $stmt = $conn_mysql->prepare($sql);
            $stmt->bindParam(':idempleado_new', $idempleado_new, PDO::PARAM_INT);
            $stmt->bindParam(':idturno_new', $idturno_new, PDO::PARAM_INT);
            $stmt->bindParam(':idempleado_old', $idempleado_old, PDO::PARAM_INT);
            $stmt->bindParam(':idturno_old', $idturno_old, PDO::PARAM_INT);
            $stmt->execute();

        } catch (PDOException $e) {
            die("Error crítico al actualizar la asignación: " . $e->getMessage());
        }
    }
} 
// CASO 2: PROCESAR INSERCIÓN NUEVA (INSERT)
elseif (isset($_POST['idempleado']) && isset($_POST['idturno'])) {
    $idempleado = trim($_POST['idempleado']);
    $idturno    = trim($_POST['idturno']);

    if (!empty($idempleado) && !empty($idturno)) {
        try {
            $sql = "INSERT INTO empleado_turno (idempleado, idturno) VALUES (:idempleado, :idturno)";
            $stmt = $conn_mysql->prepare($sql);
            $stmt->bindParam(':idempleado', $idempleado, PDO::PARAM_INT);
            $stmt->bindParam(':idturno', $idturno, PDO::PARAM_INT);
            $stmt->execute();

        } catch (PDOException $e) {
            die("Error crítico al insertar la asignación: " . $e->getMessage());
        }
    }
}

// Redireccionamos limpiamente a la pantalla principal
header("Location: index.php");
exit();
?>
