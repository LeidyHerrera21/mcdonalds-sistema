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
            // Insertamos directamente. IDDETALLE no se envía porque es AUTO_INCREMENT
            $sql = "INSERT INTO DETALLEPEDIDO (IDPEDIDO, IDPRODUCTO, CANTIDAD) 
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