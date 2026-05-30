<?php
include("../../config/MysqlDB.php");

// Verificamos que se hayan enviado los datos obligatorios por el formulario (POST)
if (isset($_POST['idempleado']) && isset($_POST['idturno'])) {
    
    $idempleado = trim($_POST['idempleado']);
    $idturno    = trim($_POST['idturno']);

    // Validamos que los campos no estén vacíos
    if (!empty($idempleado) && !empty($idturno)) {
        try {
            // Insertamos directamente en la tabla intermedia EMPLEADO_TURNO
            // Al ser una clave primaria compuesta manual, se deben enviar ambos IDs obligatoriamente
            $sql = "INSERT INTO EMPLEADO_TURNO (IDEMPLEADO, IDTURNO) 
                    VALUES (:idempleado, :idturno)";
            
            $stmt = $conn_mysql->prepare($sql);
            
            // Vinculamos cada parámetro firmemente como entero (PARAM_INT)
            $stmt->bindParam(':idempleado', $idempleado, PDO::PARAM_INT);
            $stmt->bindParam(':idturno', $idturno, PDO::PARAM_INT);
            
            $stmt->execute();

        } catch (PDOException $e) {
            // Si intentas registrar un par (IDEMPLEADO, IDTURNO) que ya existe, saltará el error de llave primaria duplicada
            die("Error crítico al asignar el turno al empleado: " . $e->getMessage());
        }
    }
}

// Redireccionamos limpiamente de vuelta al index del mantenimiento de turnos
header("Location: index.php");
exit();
?>