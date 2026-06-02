<?php
include("../../config/MysqlDB.php");

// Verificar que ambos identificadores vengan por la URL (Clave compuesta)
$idempleado = isset($_GET['idempleado']) ? trim($_GET['idempleado']) : '';
$idturno    = isset($_GET['idturno'])    ? trim($_GET['idturno'])    : '';

if (!empty($idempleado) && !empty($idturno)) {
    try {
        // Ejecución segura de eliminación con PDO usando la estructura de EMPLEADO_TURNO
        $sql = "DELETE FROM EMPLEADO_TURNO WHERE IDEMPLEADO = :idempleado AND IDTURNO = :idturno";
        $stmt = $conn_mysql->prepare($sql);
        
        // Vinculamos ambos parámetros como enteros
        $stmt->bindParam(':idempleado', $idempleado, PDO::PARAM_INT);
        $stmt->bindParam(':idturno', $idturno, PDO::PARAM_INT);
        
        $stmt->execute();
        
    } catch (PDOException $e) {
        // Si arroja un error de restricción por llaves foráneas con otras tablas, salta esta alerta
        echo "<script>
            alert('No se puede eliminar la asignación de turno porque está vinculada a otros registros del sistema.');
            window.location='index.php';
        </script>";
        exit();
    }
}

// Redireccionamos limpiamente de vuelta al index del mantenimiento de empleados por turno
header("Location: index.php");
exit();
?>