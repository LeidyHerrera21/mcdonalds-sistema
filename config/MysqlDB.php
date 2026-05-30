<?php
$host = "192.168.56.101"; // IP de tu CentOS 7
$db   = "macdonals";
$user = "root";
$pass = "Str0ngP@ssw0rd!"; // Tu contraseña real
$charset = "utf8mb4";

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $conn_mysql = new PDO($dsn, $user, $pass, $options);

} catch (\PDOException $e) {
     die("Error de conexión: " . $e->getMessage());
}
?>