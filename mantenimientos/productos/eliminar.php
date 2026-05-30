<?php
include("../../config/MysqlDB.php");

// Verificar que el ID del producto venga por la URL
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if (!empty($id)) {
    try {
        // Ejecución segura de eliminación con PDO usando la estructura de PRODUCTO
        $sql = "DELETE FROM PRODUCTO WHERE IDPRODUCTO = :id";
        $stmt = $conn_mysql->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
    } catch (PDOException $e) {
        // Si arroja un error de restricción (por ejemplo, el producto ya está referenciado en un pedido), salta esta alerta
        echo "<script>
            alert('No se puede eliminar el producto porque está asociado a registros de pedidos o ventas activas.');
            window.location='index.php';
        </script>";
        exit();
    }
}

// Redireccionamos limpiamente de vuelta al index del mantenimiento de productos
header("Location: index.php");
exit();
?>