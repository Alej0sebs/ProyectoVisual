<?php
header('Content-Type: application/json');
require 'db.php';

$estudiante_id = isset($_POST['estudiante_id']) ? (int)$_POST['estudiante_id'] : 0;
$curso_id = isset($_POST['curso_id']) ? (int)$_POST['curso_id'] : 0;

if ($estudiante_id <= 0 || $curso_id <= 0) {
    echo json_encode(['success' => false, 'errorMsg' => 'Datos no válidos.']);
    exit;
}

// Verificar que no esté ya inscrito
$stmtCheck = $conn->prepare("SELECT id FROM estudiante_curso WHERE estudiante_id = ? AND curso_id = ?");
$stmtCheck->bind_param("ii", $estudiante_id, $curso_id);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    echo json_encode(['success' => false, 'errorMsg' => 'El estudiante ya está inscrito en este curso.']);
    $stmtCheck->close();
    $conn->close();
    exit;
}
$stmtCheck->close();

// Inscribir al estudiante
$stmt = $conn->prepare("INSERT INTO estudiante_curso (estudiante_id, curso_id) VALUES (?, ?)");
$stmt->bind_param("ii", $estudiante_id, $curso_id);

try {
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Estudiante inscrito correctamente.']);
    } else {
        echo json_encode(['success' => false, 'errorMsg' => 'Error al inscribir al estudiante.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'errorMsg' => 'Error: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>
