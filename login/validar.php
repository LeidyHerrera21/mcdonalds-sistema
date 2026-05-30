<?php
// Iniciamos la sesión de forma directa en este archivo
session_start();

// 1. Incluir el archivo de conexión a Oracle 
// (Ajusta la ruta según dónde guardaste el archivo de conexión de Oracle)
include '../config/conexion_oracle.php'; 

// 2. Capturar los datos enviados por el formulario
$usuario  = isset($_POST['usuario']) ? $_POST['usuario'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($usuario) || empty($password)) {
    header("Location: index.php");
    exit();
}

// 3. Preparar la consulta SQL para Oracle
// Nota: En Oracle es una buena práctica usar mayúsculas o comprobar exactamente el nombre de tus campos
$query = "SELECT ID_USUARIO, NOMBRE_USUARIO, ROL FROM USUARIOS WHERE USERNAME = :usr AND PASSWORD = :pass";

// 4. Parsear o preparar la consulta con la conexión de Oracle ($conn_oracle debe ser tu variable en conexion_oracle.php)
$stmt = oci_parse($conn_oracle, $query);

// 5. Vincular los parámetros para evitar inyecciones SQL
oci_bind_by_name($stmt, ':usr', $usuario);
oci_bind_by_name($stmt, ':pass', $password);

// 6. Ejecutar la sentencia
oci_execute($stmt);

// 7. Evaluar si se encontró el registro
if ($row = oci_fetch_array($stmt, OCI_ASSOC)) {
    // Si encuentra fila, las credenciales son correctas. Guardamos en la sesión:
    $_SESSION['usuario_id']     = $row['ID_USUARIO'];
    $_SESSION['usuario_nombre'] = $row['NOMBRE_USUARIO'];
    $_SESSION['rol']            = $row['ROL']; // Ej: "ADMINISTRADOR"

    // Liberar recursos de Oracle y redirigir al Dashboard Principal
    oci_free_statement($stmt);
    header("Location: ../principal/dashboard.php");
    exit();

} else {
    // Si no encuentra el usuario o la contraseña es incorrecta
    oci_free_statement($stmt);
    echo "
    <script>
        alert('Usuario o contraseña incorrectos en Oracle.');
        window.location='index.php';
    </script>
    ";
    exit();
}
?>