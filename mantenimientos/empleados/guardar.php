<?php
include("../../config/MysqlDB.php");

// Verificamos que se hayan enviado los datos obligatorios por el formulario (POST)
if (isset($_POST['nombre']) && isset($_POST['idsucursal'])) {
    
    $nombre     = trim($_POST['nombre']);
    $idsucursal = trim($_POST['idsucursal']);

    // Validamos que los campos no estén vacíos
    if (!empty($nombre) && !empty($idsucursal)) {
        try {
            // Insertamos en minúsculas en la tabla empleado
            $sql = "INSERT INTO empleado (nombre, idsucursal) 
                    VALUES (:nombre, :idsucursal)";
            
            $stmt = $conn_mysql->prepare($sql);
            
            // Vinculamos los parámetros con su tipo de dato correspondiente
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':idsucursal', $idsucursal, PDO::PARAM_INT);
            
            $stmt->execute();

        } catch (PDOException $e) {
            die("Error crítico al insertar el registro del empleado: " . $e->getMessage());
        }
    }
}

// Redireccionamos limpiamente de vuelta al index de empleados
header("Location: index.php");
exit();
?>