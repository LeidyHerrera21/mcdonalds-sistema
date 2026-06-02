<?php
include("../../config/MysqlDB.php");

// Verificamos que se hayan enviado los datos obligatorios por el formulario (POST)
if (isset($_POST['fecha_pedido']) && isset($_POST['total']) && isset($_POST['idcliente']) && isset($_POST['idempleado']) && isset($_POST['idsucursal'])) {
    
    $fecha_pedido = trim($_POST['fecha_pedido']);
    $total        = trim($_POST['total']);
    $idcliente    = trim($_POST['idcliente']);
    $idempleado   = trim($_POST['idempleado']);
    $idsucursal   = trim($_POST['idsucursal']);

    // Validamos que los campos no estén vacíos
    if (!empty($fecha_pedido) && !empty($total) && !empty($idcliente) && !empty($idempleado) && !empty($idsucursal)) {
        try {
            // Sentencia SQL con la tabla y columnas en minúsculas
            $sql = "INSERT INTO pedido (fecha_pedido, total, idcliente, idempleado, idsucursal) 
                    VALUES (:fecha_pedido, :total, :idcliente, :idempleado, :idsucursal)";
            
            $stmt = $conn_mysql->prepare($sql);
            
            // Vinculación de parámetros
            $stmt->bindParam(':fecha_pedido', $fecha_pedido, PDO::PARAM_STR); 
            $stmt->bindParam(':total', $total, PDO::PARAM_STR); 
            $stmt->bindParam(':idcliente', $idcliente, PDO::PARAM_INT);
            $stmt->bindParam(':idempleado', $idempleado, PDO::PARAM_INT);
            $stmt->bindParam(':idsucursal', $idsucursal, PDO::PARAM_INT);
            
            $stmt->execute();

        } catch (PDOException $e) {
            die("Error crítico al insertar el registro de pedido: " . $e->getMessage());
        }
    }
}

// Redireccionamos limpiamente de vuelta al index principal
header("Location: index.php");
exit();
?>