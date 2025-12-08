<?php
header('Content-Type: application/json');
require 'db.php';

$curso_id = isset($_GET['curso_id']) ? (int)$_GET['curso_id'] : 0;

if ($curso_id <= 0) {
    echo json_encode(['success' => false, 'errorMsg' => 'ID de curso no válido.']);
    exit;
}

// Obtener información del curso
$stmtCurso = $conn->prepare("SELECT id, nombre FROM cursos WHERE id = ?");
$stmtCurso->bind_param("i", $curso_id);
$stmtCurso->execute();
$resultCurso = $stmtCurso->get_result();

if ($resultCurso->num_rows === 0) {
    echo json_encode(['success' => false, 'errorMsg' => 'Curso no encontrado.']);
    $stmtCurso->close();
    $conn->close();
    exit;
}

$curso = $resultCurso->fetch_assoc();
$stmtCurso->close();

// Obtener estudiantes inscritos en el curso
$sql = "SELECT e.cedula, e.nombre, e.apellido, e.telefono, e.direccion, ec.fecha_inscripcion
        FROM estudiantes e
        INNER JOIN estudiante_curso ec ON e.id = ec.estudiante_id
        WHERE ec.curso_id = ?
        ORDER BY e.apellido, e.nombre";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $curso_id);
$stmt->execute();
$result = $stmt->get_result();

$estudiantes = [];
while ($row = $result->fetch_assoc()) {
    $estudiantes[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode([
    'success' => true,
    'curso' => $curso,
    'estudiantes' => $estudiantes,
    'total' => count($estudiantes)
]);
?>
