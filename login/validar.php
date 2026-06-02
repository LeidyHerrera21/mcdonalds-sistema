<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// 1. Incluir la conexión PDO
include '../config/MysqlDB.php'; 

// 2. Capturar los datos enviados por el formulario
$usuario  = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (empty($usuario) || empty($password)) {
    header("Location: ../index.php");
    exit();
}

try {
    // 3. Consulta SQL corregida según tu base de datos (ID_EMPLEADO y CLAVE)
    $query = "SELECT ID_EMPLEADO, CLAVE FROM usuarios WHERE ID_EMPLEADO = :usr";
    
    if (!isset($conn_mysql)) {
        throw new Exception("La variable de conexión \$conn_mysql no está definida. Revisa config/MysqlDB.php");
    }

    $stmt = $conn_mysql->prepare($query);
    // Corregido el error de sintaxis del signo '$' asignándole la variable $usuario
    $stmt->bindParam(':usr', $usuario, PDO::PARAM_STR);
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // 4. Evaluar credenciales usando los campos reales de la tabla
    if ($row && $password === $row['CLAVE']) {
        
        // Guardamos los datos reales en la sesión
        $_SESSION['usuario_id']     = $row['ID_EMPLEADO'];
        $_SESSION['usuario_nombre'] = $row['ID_EMPLEADO']; // Usamos ID_EMPLEADO ya que no hay columna 'nombre' o 'usuario'

        // Redirigir al Dashboard Principal
        header("Location: ../principal/dashboard.php");
        exit();

    } else {
        // Alerta de error y regreso al index
        echo "
        <script>
            alert('Usuario o contraseña incorrectos.');
            window.location='../index.php';
        </script>
        ";
        exit();
    }

} catch (Exception $e) {
    echo "Error en el sistema: " . $e->getMessage();
    exit();
}
?>