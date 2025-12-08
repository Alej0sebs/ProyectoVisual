<?php
require('fpdf/fpdf.php');
require('../../Models/db.php');

// Validar parámetro curso_id
$curso_id = isset($_GET['curso_id']) ? (int)$_GET['curso_id'] : 0;

if ($curso_id <= 0) {
    die('ID de curso no válido.');
}

// Obtener información del curso
$stmtCurso = $conn->prepare("SELECT id, nombre FROM cursos WHERE id = ?");
$stmtCurso->bind_param("i", $curso_id);
$stmtCurso->execute();
$resultCurso = $stmtCurso->get_result();

if ($resultCurso->num_rows === 0) {
    die('Curso no encontrado.');
}

$curso = $resultCurso->fetch_assoc();
$nombreCurso = $curso['nombre'];
$stmtCurso->close();

class PDF extends FPDF
{
    private $nombreCurso;

    function __construct($nombreCurso)
    {
        parent::__construct('P', 'mm', 'A4');
        $this->nombreCurso = $nombreCurso;
    }

    function Header()
    {
        $this->SetFillColor(165, 0, 0);
        $this->Rect(0, 0, 210, 20, 'F');
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(255, 255, 255);
        $this->SetY(5);
        $this->Cell(0, 6, utf8_decode('Universidad Técnica de Ambato'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, utf8_decode('Reporte de Estudiantes por Curso'), 0, 1, 'C');
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

$pdf = new PDF($nombreCurso);
$pdf->AliasNbPages();
$pdf->SetMargins(15, 25, 15);
$pdf->AddPage();

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(165, 0, 0);
$pdf->Cell(0, 10, utf8_decode('Estudiantes Inscritos'), 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 13);
$pdf->SetTextColor(80, 80, 80);
$pdf->Cell(0, 7, utf8_decode('Curso: ' . $nombreCurso), 0, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 5, utf8_decode('Fecha de generación: ' . date('d/m/Y H:i:s')), 0, 1, 'C');

$pdf->Ln(8);

// Obtener estudiantes inscritos
$sql = "SELECT e.cedula, e.nombre, e.apellido, e.telefono, ec.fecha_inscripcion
        FROM estudiantes e
        INNER JOIN estudiante_curso ec ON e.id = ec.estudiante_id
        WHERE ec.curso_id = ?
        ORDER BY e.apellido, e.nombre";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $curso_id);
$stmt->execute();
$result = $stmt->get_result();

$totalEstudiantes = $result->num_rows;

// Mostrar total de estudiantes
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(165, 0, 0);
$pdf->Cell(0, 7, utf8_decode('Total de estudiantes inscritos: ' . $totalEstudiantes), 0, 1, 'L');
$pdf->Ln(3);

// Encabezados de tabla
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(165, 0, 0);
$pdf->SetTextColor(255, 255, 255);

$wCed = 28;
$wNom = 40;
$wApe = 40;
$wTel = 35;
$wFecha = 37;

$pdf->Cell($wCed, 9, utf8_decode('Cédula'), 1, 0, 'C', true);
$pdf->Cell($wNom, 9, utf8_decode('Nombre'), 1, 0, 'C', true);
$pdf->Cell($wApe, 9, utf8_decode('Apellido'), 1, 0, 'C', true);
$pdf->Cell($wTel, 9, utf8_decode('Teléfono'), 1, 0, 'C', true);
$pdf->Cell($wFecha, 9, utf8_decode('Fecha Inscripción'), 1, 1, 'C', true);

// Datos de estudiantes
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);

$fill = false;

if ($totalEstudiantes > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($fill) {
            $pdf->SetFillColor(250, 219, 216);
        } else {
            $pdf->SetFillColor(255, 255, 255);
        }

        $fechaInscripcion = date('d/m/Y H:i', strtotime($row['fecha_inscripcion']));

        $pdf->Cell($wCed, 8, utf8_decode($row['cedula']), 1, 0, 'C', true);
        $pdf->Cell($wNom, 8, utf8_decode($row['nombre']), 1, 0, 'L', true);
        $pdf->Cell($wApe, 8, utf8_decode($row['apellido']), 1, 0, 'L', true);
        $pdf->Cell($wTel, 8, utf8_decode($row['telefono']), 1, 0, 'C', true);
        $pdf->Cell($wFecha, 8, utf8_decode($fechaInscripcion), 1, 1, 'C', true);

        $fill = !$fill;
    }
} else {
    $pdf->SetFont('Arial', 'I', 11);
    $pdf->SetTextColor(120, 120, 120);
    $pdf->Cell(0, 10, utf8_decode('No hay estudiantes inscritos en este curso.'), 1, 1, 'C');
}

$stmt->close();
mysqli_close($conn);

$pdf->Output();
exit;
?>
