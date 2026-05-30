<?php
include("../../config/MysqlDB.php");

// Verificar que el ID venga por la URL (Corresponde a IDPEDIDO)
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if (!empty($id)) {
    try {
        // Ejecución segura de eliminación con PDO usando la estructura real de PEDIDO
        $sql = "DELETE FROM PEDIDO WHERE IDPEDIDO = :id";
        $stmt = $conn_mysql->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
    } catch (PDOException $e) {
        // Alerta si el pedido está amarrado a un pago o a un detalle de pedido (Llaves foráneas)
        echo "<script>
            alert('No se puede eliminar el pedido porque tiene un historial de pagos o detalles vinculados.');
            window.location='index.php';
        </script>";
        exit();
    }
}

// Redireccionar al listado principal de pedidos después de eliminar con éxito
header("Location: index.php");
exit();
?>