<?php
include("../../config/MysqlDB.php");

// Verificamos que se hayan enviado los datos obligatorios por el formulario (POST)
if (isset($_POST['nombre']) && isset($_POST['precio']) && isset($_POST['idcategoria']) && isset($_POST['idproveedor'])) {
    
    $nombre      = trim($_POST['nombre']);
    $precio      = trim($_POST['precio']);
    $idcategoria = trim($_POST['idcategoria']);
    $idproveedor = trim($_POST['idproveedor']);

    // Validamos que los campos no estén vacíos
    if (!empty($nombre) && !empty($precio) && !empty($idcategoria) && !empty($idproveedor)) {
        try {
            // Insertamos directamente en la tabla PRODUCTO. IDPRODUCTO se genera automáticamente por el AUTO_INCREMENT
            $sql = "INSERT INTO PRODUCTO (NOMBRE, PRECIO, IDCATEGORIA, IDPROVEEDOR) 
                    VALUES (:nombre, :precio, :idcategoria, :idproveedor)";
            
            $stmt = $conn_mysql->prepare($sql);
            
            // Vinculamos cada parámetro con su tipo de dato correspondiente
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_STR); // DECIMAL se pasa como string para no perder precisión decimal
            $stmt->bindParam(':idcategoria', $idcategoria, PDO::PARAM_INT);
            $stmt->bindParam(':idproveedor', $idproveedor, PDO::PARAM_INT);
            
            $stmt->execute();

        } catch (PDOException $e) {
            die("Error crítico al insertar el registro de producto: " . $e->getMessage());
        }
    }
}

// Redireccionamos limpiamente de vuelta al index de productos
header("Location: index.php");
exit();
?>