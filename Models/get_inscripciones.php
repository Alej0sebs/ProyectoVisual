<?php
header('Content-Type: application/json');
require 'db.php';

$estudiante_id = isset($_POST['estudiante_id']) ? (int)$_POST['estudiante_id'] : 0;

if ($estudiante_id <= 0) {
    echo json_encode(['success' => false, 'errorMsg' => 'ID de estudiante no válido.']);
    exit;
}

// Obtener cursos inscritos del estudiante
$sqlInscritos = "SELECT c.id, c.nombre, ec.fecha_inscripcion
    FROM cursos c
    INNER JOIN estudiante_curso ec ON c.id = ec.curso_id
    WHERE ec.estudiante_id = ?
    ORDER BY c.nombre";

$stmtInscritos = $conn->prepare($sqlInscritos);
$stmtInscritos->bind_param("i", $estudiante_id);
$stmtInscritos->execute();
$resultInscritos = $stmtInscritos->get_result();

$inscritos = [];
while ($row = $resultInscritos->fetch_assoc()) {
    $row['fecha_inscripcion'] = date('d/m/Y H:i', strtotime($row['fecha_inscripcion']));
    $inscritos[] = $row;
}

// Obtener cursos disponibles (no inscritos)
$sqlDisponibles = "SELECT c.id, c.nombre
    FROM cursos c
    WHERE c.id NOT IN (
        SELECT curso_id FROM estudiante_curso WHERE estudiante_id = ?
    )
    ORDER BY c.nombre";

$stmtDisponibles = $conn->prepare($sqlDisponibles);
$stmtDisponibles->bind_param("i", $estudiante_id);
$stmtDisponibles->execute();
$resultDisponibles = $stmtDisponibles->get_result();

$disponibles = [];
while ($row = $resultDisponibles->fetch_assoc()) {
    $disponibles[] = $row;
}

echo json_encode([
    'success' => true,
    'inscritos' => $inscritos,
    'disponibles' => $disponibles
]);

$stmtInscritos->close();
$stmtDisponibles->close();
$conn->close();
?>
