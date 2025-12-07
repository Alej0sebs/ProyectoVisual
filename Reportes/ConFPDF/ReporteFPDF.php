<?php
require('fpdf/fpdf.php');
require('../../Models/db.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFillColor(165, 0, 0);
        $this->Rect(0, 0, 210, 20, 'F');
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(255, 255, 255);
        $this->SetY(5);
        $this->Cell(0, 6, utf8_decode('Universidad Técnica de Ambato'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, utf8_decode('Reporte del sistema de estudiantes'), 0, 1, 'C');
        $this->Ln(3);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(120, 120, 120);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->SetMargins(15, 25, 15);
$pdf->AddPage();

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(165, 0, 0);
$pdf->Cell(0, 10, utf8_decode('Reporte General de Estudiantes'), 0, 1, 'C');

$pdf->SetFont('Arial', '', 11);
$pdf->SetTextColor(80, 80, 80);
$pdf->Cell(0, 6, utf8_decode('Listado completo de estudiantes registrados en el sistema'), 0, 1, 'C');

$pdf->Ln(6);

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(165, 0, 0);
$pdf->SetTextColor(255, 255, 255);

$wCed = 25;
$wNom = 35;
$wApe = 35;
$wDir = 55;
$wTel = 30;

$pdf->Cell($wCed, 9, utf8_decode('Cédula'), 1, 0, 'C', true);
$pdf->Cell($wNom, 9, utf8_decode('Nombre'), 1, 0, 'C', true);
$pdf->Cell($wApe, 9, utf8_decode('Apellido'), 1, 0, 'C', true);
$pdf->Cell($wDir, 9, utf8_decode('Dirección'), 1, 0, 'C', true);
$pdf->Cell($wTel, 9, utf8_decode('Teléfono'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);

$sql = "SELECT cedula, nombre, apellido, telefono, direccion FROM estudiantes";
$result = mysqli_query($conn, $sql);

$fill = false;

while ($row = mysqli_fetch_assoc($result)) {

    if ($fill) {
        $pdf->SetFillColor(250, 219, 216);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }

    $pdf->Cell($wCed, 8, utf8_decode($row['cedula']), 1, 0, 'C', true);
    $pdf->Cell($wNom, 8, utf8_decode($row['nombre']), 1, 0, 'L', true);
    $pdf->Cell($wApe, 8, utf8_decode($row['apellido']), 1, 0, 'L', true);
    $pdf->Cell($wDir, 8, utf8_decode($row['direccion']), 1, 0, 'L', true);
    $pdf->Cell($wTel, 8, utf8_decode($row['telefono']), 1, 1, 'C', true);

    $fill = !$fill;
}

mysqli_close($conn);

$pdf->Output();
exit;
?>
