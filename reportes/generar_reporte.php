<?php
if (ob_get_length()) {
    ob_clean();
}
ob_start();

// Habilitar visualización de errores temporal por si tu servidor tiene otra configuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config/MysqlDB.php'; 
require '../library/fpdf/fpdf/fpdf.php'; 

// Validar que la variable de conexión PDO exista
if (!isset($conn_mysql)) {
    die("Error interno: La variable \$conn_mysql no está configurada.");
}

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

if (empty($tipo)) {
    die("Acceso denegado: No se especificó ningún reporte.");
}

// ==========================================
// CREACIÓN DE UNA CLASE EXTENDIDA PARA EL ESTILO CORPORATIVO
// ==========================================
class PDF_McD extends FPDF {
    // Encabezado de página formal con Logo corporativo
    function Header() {
        // Franja superior decorativa (Amarillo Dorado)
        $this->SetFillColor(255, 199, 44);
        $this->Rect(0, 0, 210, 4, 'F');

        // Insertar logo corporativo
        if (file_exists('../assets/image/logo.png')) {
            $this->Image('../assets/image/logo.png', 10, 6, 15);
            $this->SetXY(28, 8);
        } else {
            $this->SetXY(10, 8);
        }

        // Título de la Empresa en Rojo McDonald's
        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor(221, 16, 33);
        $this->Cell(100, 10, 'MCDONALD\'S SYSTEM', 0, 0, 'L');
        
        // Información interna a la derecha
        $this->SetFont('Arial', 'I', 9);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(72, 5, 'Reporte Interno de Operaciones', 0, 1, 'R');
        $this->SetX(128); 
        $this->Cell(72, 5, 'Fecha de Emision: ' . date('d/m/Y H:i'), 0, 1, 'R');
        
        // Línea divisoria elegante
        $this->SetDrawColor(221, 16, 33);
        $this->SetLineWidth(0.5);
        $this->Line(10, 25, 200, 25);
        $this->Ln(8);
    }

    // Pie de página (Requerido por AliasNbPages)
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(120, 120, 120);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
    
// Inicializar la clase FPDF y activar el conteo de páginas
$pdf = new PDF_McD();
$pdf->AliasNbPages(); 
$pdf->AddPage();

// ==========================================
// CONTROLADOR DE REPORTES (SWITCH CON PDO)
// ==========================================
try {
    switch ($tipo) {

        case 'categoria':
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell(190, 10, utf8_decode('LISTADO DE CATEGORÍAS DE PRODUCTOS'), 0, 1, 'L');
            $pdf->Ln(5);

            $pdf->SetFillColor(221, 16, 33);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(40, 8, 'ID', 1, 0, 'C', true);
            $pdf->Cell(150, 8, 'NOMBRE DE CATEGORIA', 1, 1, 'L', true);

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(50, 50, 50);
            
            $query = "SELECT id, nombre FROM categoria ORDER BY id ASC";
            $stmt = $conn_mysql->prepare($query);
            $stmt->execute();
            
            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdf->Cell(40, 8, $r['id'], 1, 0, 'C');
                $pdf->Cell(150, 8, utf8_decode($r['nombre']), 1, 1, 'L');
            }
            break;

        case 'clientes':
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell(190, 10, 'LISTADO DE CLIENTES REGISTRADOS', 0, 1, 'L');
            $pdf->Ln(5);

            $pdf->SetFillColor(221, 16, 33);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(20, 8, 'ID', 1, 0, 'C', true);
            $pdf->Cell(65, 8, 'NOMBRES Y APELLIDOS', 1, 0, 'L', true);
            $pdf->Cell(35, 8, 'TELEFONO', 1, 0, 'C', true);
            $pdf->Cell(70, 8, 'CORREO ELECTRONICO', 1, 1, 'L', true);

            $pdf->SetFont('Arial', '', 9);
            $pdf->SetTextColor(50, 50, 50);
            
            $query = "SELECT id, nombre, apellido, telefono, correo FROM clientes ORDER BY id ASC";
            $stmt = $conn_mysql->prepare($query);
            $stmt->execute();

            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $nombreCompleto = $r['nombre'] . ' ' . $r['apellido'];
                $pdf->Cell(20, 8, $r['id'], 1, 0, 'C');
                $pdf->Cell(65, 8, utf8_decode($nombreCompleto), 1);
                $pdf->Cell(35, 8, $r['telefono'], 1, 0, 'C');
                $pdf->Cell(70, 8, utf8_decode($r['correo']), 1, 1, 'L');
            }
            break;

        case 'insumos':
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell(190, 10, 'CONTROL DE INSUMOS EN ALMACEN', 0, 1, 'L');
            $pdf->Ln(5);

            $pdf->SetFillColor(221, 16, 33);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(25, 8, 'ID', 1, 0, 'C', true);
            $pdf->Cell(70, 8, 'NOMBRE INSUMO', 1, 0, 'L', true);
            $pdf->Cell(40, 8, 'CANTIDAD', 1, 0, 'C', true);
            $pdf->Cell(55, 8, 'PROVEEDOR', 1, 1, 'L', true);

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(50, 50, 50);
            
            $query = "SELECT id, nombre, cantidad, proveedor FROM insumos ORDER BY id ASC";
            $stmt = $conn_mysql->prepare($query);
            $stmt->execute();

            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdf->Cell(25, 8, $r['id'], 1, 0, 'C');
                $pdf->Cell(70, 8, utf8_decode($r['nombre']), 1);
                $pdf->Cell(40, 8, number_format($r['cantidad'], 2), 1, 0, 'C');
                $pdf->Cell(55, 8, utf8_decode($r['proveedor']), 1, 1, 'L');
            }
            break;

        case 'proveedores':
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell(190, 10, 'LISTADO DE PROVEEDORES HOMOLOGADOS', 0, 1, 'L');
            $pdf->Ln(5);

            $pdf->SetFillColor(221, 16, 33);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(40, 8, 'ID', 1, 0, 'C', true);
            $pdf->Cell(150, 8, 'NOMBRE / RAZON SOCIAL', 1, 1, 'L', true);

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(50, 50, 50);
            
            $query = "SELECT id, nombre FROM proveedores ORDER BY id ASC";
            $stmt = $conn_mysql->prepare($query);
            $stmt->execute();

            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdf->Cell(40, 8, $r['id'], 1, 0, 'C');
                $pdf->Cell(150, 8, utf8_decode($r['nombre']), 1, 1, 'L');
            }
            break;

        case 'promociones':
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell(190, 10, 'PANEL DE PROMOCIONES VIGENTES', 0, 1, 'L');
            $pdf->Ln(5);

            $pdf->SetFillColor(221, 16, 33);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(40, 8, 'ID', 1, 0, 'C', true);
            $pdf->Cell(150, 8, 'DESCRIPCION DE PROMOCION', 1, 1, 'L', true);

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(50, 50, 50);
            
            $query = "SELECT id, nombre FROM promociones ORDER BY id ASC";
            $stmt = $conn_mysql->prepare($query);
            $stmt->execute();

            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdf->Cell(40, 8, $r['id'], 1, 0, 'C');
                $pdf->Cell(150, 8, utf8_decode($r['nombre']), 1, 1, 'L');
            }
            break;

        case 'sucursales':
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell(190, 10, 'SUCURSALES AUTORIZADAS', 0, 1, 'L');
            $pdf->Ln(5);

            $pdf->SetFillColor(221, 16, 33);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(40, 8, 'ID', 1, 0, 'C', true);
            $pdf->Cell(150, 8, 'NOMBRE DE SUCURSAL', 1, 1, 'L', true);

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(50, 50, 50);
            
            $query = "SELECT id, nombre FROM sucursales ORDER BY id ASC";
            $stmt = $conn_mysql->prepare($query);
            $stmt->execute();

            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdf->Cell(40, 8, $r['id'], 1, 0, 'C');
                $pdf->Cell(150, 8, utf8_decode($r['nombre']), 1, 1, 'L');
            }
            break;

        case 'precios':
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell(190, 10, 'LISTADO OFICIAL DE PRECIOS AL PUBLICO', 0, 1, 'L');
            $pdf->Ln(5);

            $pdf->SetFillColor(221, 16, 33);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(35, 8, 'ID', 1, 0, 'C', true);
            $pdf->Cell(105, 8, 'CONCEPTO / PRODUCTO', 1, 0, 'L', true);
            $pdf->Cell(50, 8, 'PRECIO DE VENTA', 1, 1, 'R', true);

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(50, 50, 50);
            
            $query = "SELECT id, nombre, precio FROM precios ORDER BY id ASC";
            $stmt = $conn_mysql->prepare($query);
            $stmt->execute();

            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdf->Cell(35, 8, $r['id'], 1, 0, 'C');
                $pdf->Cell(105, 8, utf8_decode($r['nombre']), 1);
                $pdf->Cell(50, 8, 'S/ ' . number_format($r['precio'], 2), 1, 1, 'R');
            }
            break;

        case 'costo_productos':
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell(190, 10, 'ANALISIS DE COSTOS DE PRODUCCION', 0, 1, 'L');
            $pdf->Ln(5);

            $pdf->SetFillColor(221, 16, 33);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(35, 8, 'ID', 1, 0, 'C', true);
            $pdf->Cell(105, 8, 'PRODUCTO BASE', 1, 0, 'L', true);
            $pdf->Cell(50, 8, 'COSTO NETO', 1, 1, 'R', true);

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(50, 50, 50);
            
            $query = "SELECT id, nombre, costo FROM costos_producto ORDER BY id ASC";
            $stmt = $conn_mysql->prepare($query);
            $stmt->execute();

            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdf->Cell(35, 8, $r['id'], 1, 0, 'C');
                $pdf->Cell(105, 8, utf8_decode($r['nombre']), 1);
                $pdf->Cell(50, 8, 'S/ ' . number_format($r['costo'], 2), 1, 1, 'R');
            }
            break;

        case 'turno':
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell(190, 10, 'REPORTES DE TURNOS CONFIGURADOS', 0, 1, 'L');
            $pdf->Ln(5);

            $pdf->SetFillColor(221, 16, 33);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(40, 8, 'ID TURNO', 1, 0, 'C', true);
            $pdf->Cell(150, 8, 'NOMBRE DEL TURNO', 1, 1, 'L', true);

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(50, 50, 50);
            
            $query = "SELECT idturno, nombre FROM turno ORDER BY idturno ASC";
            $stmt = $conn_mysql->prepare($query);
            $stmt->execute();

            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdf->Cell(40, 8, $r['idturno'], 1, 0, 'C');
                $pdf->Cell(150, 8, utf8_decode($r['nombre']), 1, 1, 'L');
            }
            break;

        case 'productos':
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell(190, 10, 'REPORTE GENERAL DE PRODUCTOS', 0, 1, 'L');
            $pdf->Ln(5);

            $pdf->SetFillColor(221, 16, 33);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(30, 8, 'ID PROD.', 1, 0, 'C', true);
            $pdf->Cell(110, 8, 'NOMBRE DEL PRODUCTO', 1, 0, 'L', true);
            $pdf->Cell(50, 8, 'PRECIO BASE', 1, 1, 'R', true);

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(50, 50, 50);
            
            $query = "SELECT idproducto, nombre, precio FROM producto ORDER BY idproducto ASC";
            $stmt = $conn_mysql->prepare($query);
            $stmt->execute();

            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdf->Cell(30, 8, $r['idproducto'], 1, 0, 'C');
                $pdf->Cell(110, 8, utf8_decode($r['nombre']), 1, 0, 'L');
                $pdf->Cell(50, 8, 'S/ ' . number_format($r['precio'], 2), 1, 1, 'R');
            }
            break;

        case 'pedidos':
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell(190, 10, 'HISTORIAL DE PEDIDOS PROCESADOS', 0, 1, 'L');
            $pdf->Ln(5);

            $pdf->SetFillColor(221, 16, 33);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(40, 8, 'ID PEDIDO', 1, 0, 'C', true);
            $pdf->Cell(100, 8, 'FECHA PEDIDO', 1, 0, 'L', true);
            $pdf->Cell(50, 8, 'TOTAL', 1, 1, 'R', true);

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(50, 50, 50);
            
            $query = "SELECT idpedido, fecha_pedido, total FROM pedido ORDER BY idpedido DESC";
            $stmt = $conn_mysql->prepare($query);
            $stmt->execute();

            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdf->Cell(40, 8, $r['idpedido'], 1, 0, 'C');
                $pdf->Cell(100, 8, $r['fecha_pedido'], 1, 0, 'L');
                $pdf->Cell(50, 8, 'S/ ' . number_format($r['total'], 2), 1, 1, 'R');
            }
            break;

        case 'pagos':
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell(190, 10, 'REGISTRO DE PAGOS Y TRANSACCIONES', 0, 1, 'L');
            $pdf->Ln(5);

            $pdf->SetFillColor(221, 16, 33);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(40, 8, 'ID PAGO', 1, 0, 'C', true);
            $pdf->Cell(100, 8, 'FECHA DE PAGO', 1, 0, 'L', true);
            $pdf->Cell(50, 8, 'MONTO', 1, 1, 'R', true);

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(50, 50, 50);
            
            $query = "SELECT idpago, fechapago, monto FROM pago ORDER BY idpago DESC";
            $stmt = $conn_mysql->prepare($query);
            $stmt->execute();

            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdf->Cell(40, 8, $r['idpago'], 1, 0, 'C');
                $pdf->Cell(100, 8, $r['fechapago'], 1, 0, 'L');
                $pdf->Cell(50, 8, 'S/ ' . number_format($r['monto'], 2), 1, 1, 'R');
            }
            break;

        case 'inventarios':
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell(190, 10, 'ESTADO DE INVENTARIOS GENERAL', 0, 1, 'L');
            $pdf->Ln(5);

            $pdf->SetFillColor(221, 16, 33);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(40, 8, 'ID INVENT.', 1, 0, 'C', true);
            $pdf->Cell(50, 8, 'ID PRODUCTO', 1, 0, 'C', true);
            $pdf->Cell(50, 8, 'ID SUCURSAL', 1, 0, 'C', true);
            $pdf->Cell(50, 8, 'CANTIDAD (STOCK)', 1, 1, 'C', true);

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(50, 50, 50);
            
            $query = "SELECT idinventario, idproducto, idsucursal, cantidad FROM inventario ORDER BY idinventario ASC";
            $stmt = $conn_mysql->prepare($query);
            $stmt->execute();

            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdf->Cell(40, 8, $r['idinventario'], 1, 0, 'C');
                $pdf->Cell(50, 8, $r['idproducto'], 1, 0, 'C');
                $pdf->Cell(50, 8, $r['idsucursal'], 1, 0, 'C');
                $pdf->Cell(50, 8, $r['cantidad'], 1, 1, 'C');
            }
            break;

        case 'empleados':
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell(190, 10, 'NOMINA DE EMPLEADOS', 0, 1, 'L');
            $pdf->Ln(5);

            $pdf->SetFillColor(221, 16, 33);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(40, 8, 'ID EMPLEADO', 1, 0, 'C', true);
            $pdf->Cell(100, 8, 'NOMBRE', 1, 0, 'L', true);
            $pdf->Cell(50, 8, 'ID SUCURSAL', 1, 1, 'C', true);

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(50, 50, 50);
            
            $query = "SELECT idempleado, nombre, idsucursal FROM empleado ORDER BY idempleado ASC";
            $stmt = $conn_mysql->prepare($query);
            $stmt->execute();

            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdf->Cell(40, 8, $r['idempleado'], 1, 0, 'C');
                $pdf->Cell(100, 8, utf8_decode($r['nombre']), 1, 0, 'L');
                $pdf->Cell(50, 8, $r['idsucursal'], 1, 1, 'C');
            }
            break;

        case 'detalle':
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell(190, 10, 'DETALLES DE PEDIDOS', 0, 1, 'L');
            $pdf->Ln(5);

            $pdf->SetFillColor(221, 16, 33);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(40, 8, 'ID DETALLE', 1, 0, 'C', true);
            $pdf->Cell(50, 8, 'ID PEDIDO', 1, 0, 'C', true);
            $pdf->Cell(50, 8, 'ID PRODUCTO', 1, 0, 'C', true);
            $pdf->Cell(50, 8, 'CANTIDAD', 1, 1, 'C', true);

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(50, 50, 50);
            
            $query = "SELECT iddetalle, idpedido, idproducto, cantidad FROM detallepedido ORDER BY iddetalle DESC";
            $stmt = $conn_mysql->prepare($query);
            $stmt->execute();

            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdf->Cell(40, 8, $r['iddetalle'], 1, 0, 'C');
                $pdf->Cell(50, 8, $r['idpedido'], 1, 0, 'C');
                $pdf->Cell(50, 8, $r['idproducto'], 1, 0, 'C');
                $pdf->Cell(50, 8, $r['cantidad'], 1, 1, 'C');
            }
            break;

        default:
            die("Error: El reporte solicitado no es válido.");
    }
} catch (PDOException $e) {
    die("Error crítico al extraer datos para el reporte: " . $e->getMessage());
}

ob_end_clean();
$pdf->Output();
?>
