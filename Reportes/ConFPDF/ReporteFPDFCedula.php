<?php
require('fpdf/fpdf.php');
require('../../Models/db.php');

if (!isset($_GET['cedula'])) {
    die("Cedula no proporcionada.");
}

$cedula = mysqli_real_escape_string($conn, $_GET['cedula']);

$sql = "SELECT cedula, nombre, apellido, direccion, telefono 
        FROM estudiantes 
        WHERE cedula = '$cedula'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    die("No se encontró un estudiante con esa cédula.");
}

$row = mysqli_fetch_assoc($result);

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
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->SetMargins(20, 25, 20);
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 15);
$pdf->SetTextColor(165, 0, 0);
$pdf->Cell(0, 10, utf8_decode('Reporte Individual de Estudiante'), 0, 1, 'C');

$pdf->SetFont('Arial', '', 11);
$pdf->SetTextColor(70, 70, 70);
$pdf->Cell(0, 6, utf8_decode('Estudiante con cédula: ') . utf8_decode($row['cedula']), 0, 1, 'C');

$pdf->Ln(8);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(165, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(45, 9, utf8_decode('Campo'), 1, 0, 'C', true);
$pdf->Cell(115, 9, utf8_decode('Detalle'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 11);
$pdf->SetTextColor(0, 0, 0);

$campoW = 45;
$valorW = 115;

$pdf->SetFillColor(255, 255, 255);
$pdf->Cell($campoW, 8, utf8_decode('Cédula'), 1, 0, 'L', true);
$pdf->Cell($valorW, 8, utf8_decode($row['cedula']), 1, 1, 'L', true);

$pdf->SetFillColor(250, 219, 216);
$pdf->Cell($campoW, 8, utf8_decode('Nombre'), 1, 0, 'L', true);
$pdf->Cell($valorW, 8, utf8_decode($row['nombre']), 1, 1, 'L', true);

$pdf->SetFillColor(255, 255, 255);
$pdf->Cell($campoW, 8, utf8_decode('Apellido'), 1, 0, 'L', true);
$pdf->Cell($valorW, 8, utf8_decode($row['apellido']), 1, 1, 'L', true);

$pdf->SetFillColor(250, 219, 216);
$pdf->Cell($campoW, 8, utf8_decode('Dirección'), 1, 0, 'L', true);
$pdf->Cell($valorW, 8, utf8_decode($row['direccion']), 1, 1, 'L', true);

$pdf->SetFillColor(255, 255, 255);
$pdf->Cell($campoW, 8, utf8_decode('Teléfono'), 1, 0, 'L', true);
$pdf->Cell($valorW, 8, utf8_decode($row['telefono']), 1, 1, 'L', true);

mysqli_close($conn);

$pdf->Output();
exit;
?>
