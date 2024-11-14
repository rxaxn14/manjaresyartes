<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Reporte de Productos');

// Encabezados
$sheet->setCellValue('A1', 'Nombre');
$sheet->setCellValue('B1', 'Stock');
$sheet->setCellValue('C1', 'Precio');

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "bd_artesanias");
$query = "SELECT * FROM producto";
$resultado = $conexion->query($query);

$rowNum = 2;
while ($row = $resultado->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, $row['nombre']);
    $sheet->setCellValue('B' . $rowNum, $row['stock']);
    $sheet->setCellValue('C' . $rowNum, $row['precio']);
    $rowNum++;
}

$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte.xlsx"');
header('Cache-Control: max-age=0');
$writer->save('php://output');

$conexion->close();
?>
