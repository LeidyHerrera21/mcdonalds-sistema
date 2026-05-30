<?php
include("../../config/MysqlDB.php");

// Verificar que el ID venga por la URL (Corresponde a IDINVENTARIO)
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if (!empty($id)) {
    try {
        // Ejecución segura de eliminación con PDO usando la estructura de INVENTARIO
        $sql = "DELETE FROM INVENTARIO WHERE IDINVENTARIO = :id";
        $stmt = $conn_mysql->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
    } catch (PDOException $e) {
        // Alerta en caso de que ocurra un error de restricción de integridad o base de datos
        echo "<script>
            alert('No se pudo eliminar el registro de inventario debido a un error en el sistema.');
            window.location='index.php';
        </script>";
        exit();
    }
}

// Redireccionar al listado principal después de eliminar con éxito
header("Location: index.php");
exit();
?>