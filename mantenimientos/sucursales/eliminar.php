<?php
include("../../config/MysqlDB.php");

// Verificar que el ID venga por la URL
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if (!empty($id)) {
    try {
        // Ejecución segura de eliminación con PDO
        $sql = "DELETE FROM sucursales WHERE id = :id";
        $stmt = $conn_mysql->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        // Si arroja error por llave foránea (categoría en uso), salta esta alerta
        echo "<script>
            alert('No se puede eliminar los sucursales.');
            window.location='index.php';
        </script>";
        exit();
    }
}

header("Location: index.php");
exit();
?>