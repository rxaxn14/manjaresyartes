<?php
require 'vendor/autoload.php';

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Reporte de Ventas e Inventarios', 0, 1, 'C');
        $this->Ln(10);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "bd_artesanias");
$query = "SELECT * FROM producto";
$resultado = $conexion->query($query);

while ($row = $resultado->fetch_assoc()) {
    $pdf->Cell(40, 10, $row['nombre'], 1);
    $pdf->Cell(30, 10, $row['stock'], 1);
    $pdf->Cell(30, 10, '$' . $row['precio'], 1);
    $pdf->Ln();
}

// Cierra la conexión antes de enviar el PDF
$conexion->close();

// Enviar el PDF como descarga
$pdf->Output('D', 'reporte.pdf');
?>
