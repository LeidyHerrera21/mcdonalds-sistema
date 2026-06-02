<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../config/MysqlDB.php");

// Verificar conexión
if (!isset($conn_mysql)) {
    die("Error en el sistema: La variable de conexión \$conn_mysql no está definida.");
}

// Obtener ID del cliente
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if (empty($id)) {
    die("Error: No se proporcionó un ID de clientes válido.");
}

try {

    // Buscar cliente
    $sql = "SELECT id, nombre 
            FROM clientes 
            WHERE id = :id";

    $stmt = $conn_mysql->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar existencia
    if (!$fila) {
        die("Error: El cliente no existe.");
    }

    // Actualizar cliente
    if (isset($_POST['actualizar'])) {

        $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';

        if (!empty($nombre)) {

            $update = "UPDATE clientes
                       SET nombre = :nombre
                       WHERE id = :id";

            $stmtUpdate = $conn_mysql->prepare($update);

            $stmtUpdate->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':id', $id, PDO::PARAM_INT);

            $stmtUpdate->execute();

            header("Location: index.php");
            exit();

        } else {

            echo "<script>
                    alert('Complete el nombre del cliente.');
                  </script>";
        }
    }

} catch (PDOException $e) {

    die("Error en la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }

        body{
            background:#2b0000;
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
            padding:20px;
        }

        .edit-container{
            background:white;
            padding:30px;
            border-radius:15px;
            width:100%;
            max-width:450px;
            box-shadow:0 4px 15px rgba(0,0,0,0.3);
        }

        .titulo{
            color:#b30000;
            margin-bottom:20px;
            font-size:24px;
            display:flex;
            align-items:center;
            gap:10px;
        }

        .info-estatica{
            background:#f5f5f5;
            padding:10px;
            border-radius:5px;
            margin-bottom:15px;
            border-left:4px solid #b30000;
            font-size:14px;
            color:#555;
            line-height:1.6;
        }

        label{
            display:block;
            margin-bottom:6px;
            color:#333;
            font-weight:bold;
            font-size:14px;
        }

        input[type="text"]{
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:5px;
            font-size:15px;
            margin-bottom:15px;
        }

        .btn-group{
            display:flex;
            justify-content:flex-end;
            gap:10px;
            margin-top:10px;
        }

        .btn-actualizar{
            background:#009933;
            color:white;
            border:none;
            padding:10px 20px;
            border-radius:5px;
            cursor:pointer;
            font-weight:bold;
        }

        .btn-actualizar:hover{
            background:green;
        }

        .btn-cancelar{
            background:#111;
            color:white;
            padding:10px 15px;
            border-radius:8px;
            text-decoration:none;
            font-size:14px;
            display:inline-flex;
            align-items:center;
        }

        .btn-cancelar:hover{
            background:#333;
        }

    </style>
</head>
<body>

<div class="edit-container">

    <h2 class="titulo">
        <i class="fa-solid fa-user-pen"></i>
        Editar Cliente
    </h2>

    <div class="info-estatica">
        <strong>ID Cliente:</strong>
        <?php echo htmlspecialchars($fila['id']); ?>
    </div>

    <form method="POST">

        <label for="nombre">
            Nombre del Cliente:
        </label>

        <input
            type="text"
            id="nombre"
            name="nombre"
            value="<?php echo htmlspecialchars($fila['nombre']); ?>"
            required
        >

        <div class="btn-group">

            <a class="btn-cancelar" href="index.php">
                Cancelar
            </a>

            <button class="btn-actualizar"
                    type="submit"
                    name="actualizar">

                <i class="fa-solid fa-sync"></i>
                Actualizar

            </button>

        </div>

    </form>

</div>

</body>
</html>