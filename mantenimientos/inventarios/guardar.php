<?php
include("../../config/MysqlDB.php");

// Verificamos que se hayan enviado los datos obligatorios por el formulario (POST)
if (isset($_POST['idproducto']) && isset($_POST['idsucursal']) && isset($_POST['cantidad'])) {
    
    $idproducto = trim($_POST['idproducto']);
    $idsucursal = trim($_POST['idsucursal']);
    $cantidad   = trim($_POST['cantidad']);

    // Validamos que los campos no estén vacíos y que la cantidad sea un número válido (mayor o igual a 0)
    if (!empty($idproducto) && !empty($idsucursal) && $cantidad !== '' && is_numeric($cantidad) && $cantidad >= 0) {
        try {
            // Sentencia INSERT mapeada completamente a minúsculas
            $sql = "INSERT INTO inventario (idproducto, idsucursal, cantidad) 
                    VALUES (:idproducto, :idsucursal, :cantidad)";
            
            $stmt = $conn_mysql->prepare($sql);
            
            // Vinculamos los parámetros como enteros (PARAM_INT)
            $stmt->bindParam(':idproducto', $idproducto, PDO::PARAM_INT);
            $stmt->bindParam(':idsucursal', $idsucursal, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            
            $stmt->execute();

        } catch (PDOException $e) {
            die("Error crítico al insertar el registro de inventario: " . $e->getMessage());
        }
    }
}

// Redireccionamos limpiamente de vuelta al index del inventario
header("Location: index.php");
exit();
?>