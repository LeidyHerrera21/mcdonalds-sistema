<?php
include("../../config/MysqlDB.php");

// Verificamos que se hayan enviado los datos obligatorios por el formulario (POST)
if (isset($_POST['monto']) && isset($_POST['fechapago']) && isset($_POST['idpedido']) && isset($_POST['idmetodo'])) {
    
    $monto     = trim($_POST['monto']);
    $fechapago = trim($_POST['fechapago']);
    $idpedido  = trim($_POST['idpedido']);
    $idmetodo  = trim($_POST['idmetodo']);

    // Validamos que los campos no estén vacíos
    if (!empty($monto) && !empty($fechapago) && !empty($idpedido) && !empty($idmetodo)) {
        try {
            // Insertamos directamente en la tabla PAGO. IDPAGO se genera automáticamente por el AUTO_INCREMENT
            $sql = "INSERT INTO PAGO (MONTO, FECHAPAGO, IDPEDIDO, IDMETODO) 
                    VALUES (:monto, :fechapago, :idpedido, :idmetodo)";
            
            $stmt = $conn_mysql->prepare($sql);
            
            // Vinculamos cada parámetro con su tipo de dato correspondiente
            $stmt->bindParam(':monto', $monto, PDO::PARAM_STR); // DECIMAL se pasa como string para no perder precisión
            $stmt->bindParam(':fechapago', $fechapago, PDO::PARAM_STR); // Las fechas se vinculan como string
            $stmt->bindParam(':idpedido', $idpedido, PDO::PARAM_INT);
            $stmt->bindParam(':idmetodo', $idmetodo, PDO::PARAM_INT);
            
            $stmt->execute();

        } catch (PDOException $e) {
            die("Error crítico al insertar el registro de pago: " . $e->getMessage());
        }
    }
}

// Redireccionamos limpiamente de vuelta al index de pagos
header("Location: index.php");
exit();
?>