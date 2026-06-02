<?php
include("../../config/MysqlDB.php");

// Verificar que el ID venga por la URL
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if (!empty($id)) {
    try {
        // Eliminación segura con PDO
        $sql = "DELETE FROM clientes WHERE id = :id";
        $stmt = $conn_mysql->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

    } catch (PDOException $e) {

        echo "<script>
            alert('No se puede eliminar el clientes.');
            window.location='index.php';
        </script>";
        exit();
    }
}

header("Location: index.php");
exit();
?>