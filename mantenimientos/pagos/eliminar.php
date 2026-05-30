<?php
include("../../config/MysqlDB.php");

// Verificar que el ID venga por la URL (Corresponde a IDPAGO)
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if (!empty($id)) {
    try {
        // Ejecución segura de eliminación con PDO usando la estructura real de PAGO
        $sql = "DELETE FROM PAGOS WHERE IDPAGO = :id";
        $stmt = $conn_mysql->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
    } catch (PDOException $e) {
        // Alerta en caso de que ocurra un error de restricción por llaves foráneas o base de datos
        echo "<script>
            alert('No se pudo eliminar el registro de pago debido a una restricción en el sistema.');
            window.location='index.php';
        </script>";
        exit();
    }
}

// Redireccionar al listado principal de pagos después de eliminar con éxito
header("Location: index.php");
exit();
?>