<?php
include("../../config/MysqlDB.php");

if (isset($_POST['nombre'])) {

    $nombre = trim($_POST['nombre']);

    if (!empty($nombre)) {

        try {

            // 1. Buscar el ID más alto actual en la tabla clientes
            $sqlMax = "SELECT MAX(id) AS max_id FROM clientes";

            $stmtMax = $conn_mysql->prepare($sqlMax);
            $stmtMax->execute();

            $row = $stmtMax->fetch(PDO::FETCH_ASSOC);

            // 2. Generar nuevo ID
            $nuevoId = ($row['max_id'] !== null)
                        ? $row['max_id'] + 1
                        : 1;

            // 3. Insertar cliente
            $sql = "INSERT INTO clientes (id, nombre)
                    VALUES (:id, :nombre)";

            $stmt = $conn_mysql->prepare($sql);

            $stmt->bindParam(':id', $nuevoId, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);

            $stmt->execute();

        } catch (PDOException $e) {

            die("Error crítico al insertar: " . $e->getMessage());
        }
    }
}

// Redireccionar al index
header("Location: index.php");
exit();
?>