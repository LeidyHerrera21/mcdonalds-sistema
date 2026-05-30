<?php
if (ob_get_length()) {
    ob_clean();
}
ob_start();

include '../config/MysqlDB.php'; 
require '../library/fpdf/fpdf/fpdf.php'; 

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

if (empty($tipo)) {
    die("Acceso denegado: No se especificó ningún reporte.");
}

// ==========================================
// CREACIÓN DE UNA CLASE EXTENDIDA PARA EL ESTILO CORPORATIVO
// ==========================================
class PDF_McD extends FPDF {
    // Encabezado de página formal
   // Encabezado de página formal con Logo corporativo
    function Header() {
        // Franja superior decorativa (Amarillo Dorado)
        $this->SetFillColor(255, 199, 44);
        $this->Rect(0, 0, 210, 4, 'F');

        // ==========================================
        // INSERTAR LOGO EN LA ESQUINA SUPERIOR IZQUIERDA
        // Ruta: Salimos de 'reportes' con '../', entramos a 'assets/image/logo.png'
        // Parámetros: Ruta, X=10, Y=8, Ancho=15 (el alto se calcula automático)
        // ==========================================
        if (file_exists('../assets/image/logo.png')) {
            $this->Image('../assets/image/logo.png', 10, 6, 15);
            // Movemos el título a la derecha (X=28) para que no se empalme con el logo
            $this->SetXY(28, 8);
        } else {
            // Si no encuentra el logo, deja el cursor en la posición normal
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
        $this->SetX(128); // Alineamos la segunda línea de texto a la derecha
        $this->Cell(72, 5, 'Fecha de Emision: ' . date('d/m/Y H:i'), 0, 1, 'R');
        
        // Línea divisoria elegante bajada un poco para dar aire al logo (Y=25)
        $this->SetDrawColor(221, 16, 33);
        $this->SetLineWidth(0.5);
        $this->Line(10, 25, 200, 25);
        $this->Ln(8);
    }
    }
    
// Inicializar la nueva clase y activar el conteo de páginas totales
$pdf = new PDF_McD();
$pdf->AliasNbPages(); 
$pdf->AddPage();

// ==========================================
// CONTROLADOR DE REPORTES (SWITCH)
// ==========================================
switch ($tipo) {

    case 'categoria':
        // Título del Reporte
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->Cell(190, 10, utf8_decode('LISTADO DE CATEGORÍAS DE PRODUCTOS'), 0, 1, 'L');
        $pdf->Ln(5);

        // Estilo de Cabecera: Fondo Rojo corporativo, Texto Blanco
        $pdf->SetFillColor(221, 16, 33);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(40, 8, 'ID', 1, 0, 'C', true);
        $pdf->Cell(150, 8, 'NOMBRE DE CATEGORIA', 1, 1, 'L', true);

        // Datos de la Tabla
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(50, 50, 50); // Texto gris oscuro para lectura cómoda
        
        $query = "SELECT id, nombre FROM categoria ORDER BY id ASC";
        $result = mysqli_query($conn, $query);

        while ($r = mysqli_fetch_assoc($result)) {
            $pdf->Cell(40, 8, $r['id'], 1, 0, 'C');
            $pdf->Cell(150, 8, utf8_decode($r['nombre']), 1, 1, 'L');
        }
        mysqli_free_result($result);
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
        $result = mysqli_query($conn, $query);

        while ($r = mysqli_fetch_assoc($result)) {
            $nombreCompleto = $r['nombre'] . ' ' . $r['apellido'];
            $pdf->Cell(20, 8, $r['id'], 1, 0, 'C');
            $pdf->Cell(65, 8, utf8_decode($nombreCompleto), 1);
            $pdf->Cell(35, 8, $r['telefono'], 1, 0, 'C');
            $pdf->Cell(70, 8, utf8_decode($r['correo']), 1, 1, 'L');
        }
        mysqli_free_result($result);
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
        $result = mysqli_query($conn, $query);

        while ($r = mysqli_fetch_assoc($result)) {
            $pdf->Cell(25, 8, $r['id'], 1, 0, 'C');
            $pdf->Cell(70, 8, utf8_decode($r['nombre']), 1);
            $pdf->Cell(40, 8, number_format($r['cantidad'], 2), 1, 0, 'C');
            $pdf->Cell(55, 8, utf8_decode($r['proveedor']), 1, 1, 'L');
        }
        mysqli_free_result($result);
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
        $result = mysqli_query($conn, $query);

        while ($r = mysqli_fetch_assoc($result)) {
            $pdf->Cell(40, 8, $r['id'], 1, 0, 'C');
            $pdf->Cell(150, 8, utf8_decode($r['nombre']), 1, 1, 'L');
        }
        mysqli_free_result($result);
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
        $result = mysqli_query($conn, $query);

        while ($r = mysqli_fetch_assoc($result)) {
            $pdf->Cell(40, 8, $r['id'], 1, 0, 'C');
            $pdf->Cell(150, 8, utf8_decode($r['nombre']), 1, 1, 'L');
        }
        mysqli_free_result($result);
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
        $result = mysqli_query($conn, $query);

        while ($r = mysqli_fetch_assoc($result)) {
            $pdf->Cell(40, 8, $r['id'], 1, 0, 'C');
            $pdf->Cell(150, 8, utf8_decode($r['nombre']), 1, 1, 'L');
        }
        mysqli_free_result($result);
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
        $result = mysqli_query($conn, $query);

        while ($r = mysqli_fetch_assoc($result)) {
            $pdf->Cell(35, 8, $r['id'], 1, 0, 'C');
            $pdf->Cell(105, 8, utf8_decode($r['nombre']), 1);
            $pdf->Cell(50, 8, 'S/ ' . number_format($r['precio'], 2), 1, 1, 'R');
        }
        mysqli_free_result($result);
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
        $result = mysqli_query($conn, $query);

        while ($r = mysqli_fetch_assoc($result)) {
            $pdf->Cell(35, 8, $r['id'], 1, 0, 'C');
            $pdf->Cell(105, 8, utf8_decode($r['nombre']), 1);
            $pdf->Cell(50, 8, 'S/ ' . number_format($r['costo'], 2), 1, 1, 'R');
        }
        mysqli_free_result($result);
        break;

    default:
        die("Error: El reporte solicitado no es válido.");
}

mysqli_close($conn);
ob_end_clean();

$pdf->Output();
?>