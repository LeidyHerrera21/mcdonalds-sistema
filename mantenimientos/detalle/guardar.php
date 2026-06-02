<?php
include("../../config/MysqlDB.php");

// Verificamos que se hayan enviado los datos obligatorios por el formulario (POST)
if (isset($_POST['idpedido']) && isset($_POST['idproducto']) && isset($_POST['cantidad'])) {
    
    $idpedido  = trim($_POST['idpedido']);
    $idproducto = trim($_POST['idproducto']);
    $cantidad   = trim($_POST['cantidad']);

    // Validamos que los campos no estén vacíos y sean numéricos
    if (!empty($idpedido) && !empty($idproducto) && !empty($cantidad)) {
        try {
            // Consulta de inserción con la tabla y columnas en minúsculas
            $sql = "INSERT INTO detallepedido (idpedido, idproducto, cantidad) 
                    VALUES (:idpedido, :idproducto, :cantidad)";
            
            $stmt = $conn_mysql->prepare($sql);
            
            // Vinculamos los parámetros como enteros (PARAM_INT)
            $stmt->bindParam(':idpedido', $idpedido, PDO::PARAM_INT);
            $stmt->bindParam(':idproducto', $idproducto, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            
            $stmt->execute();

        } catch (PDOException $e) {
            die("Error crítico al insertar el detalle del pedido: " . $e->getMessage());
        }
    }
}

// Redireccionamos limpiamente de vuelta al index principal
header("Location: index.php");
exit();
?>