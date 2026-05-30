<?php
include("../../config/MysqlDB.php");

// Verificar que el ID venga por la URL (Corresponde a IDEMPLEADO)
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if (!empty($id)) {
    try {
        // Ejecución segura de eliminación con PDO usando la estructura real de EMPLEADO
        $sql = "DELETE FROM EMPLEADO WHERE IDEMPLEADO = :id";
        $stmt = $conn_mysql->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
    } catch (PDOException $e) {
        // Alerta en caso de que ocurra un error de restricción de integridad (por ejemplo, empleado con pedidos asociados)
        echo "<script>
            alert('No se pudo eliminar al empleado debido a que tiene registros asociados en el sistema.');
            window.location='index.php';
        </script>";
        exit();
    }
}

// Redireccionar al listado principal de empleados después de eliminar con éxito
header("Location: index.php");
exit();
?>