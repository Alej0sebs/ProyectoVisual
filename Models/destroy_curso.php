<?php
header('Content-Type: application/json');
require 'db.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'errorMsg' => 'ID de curso no válido.']);
    exit;
}

// Las inscripciones se eliminarán automáticamente por CASCADE
$stmt = $conn->prepare("DELETE FROM cursos WHERE id = ?");
$stmt->bind_param("i", $id);

try {
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Curso eliminado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'errorMsg' => 'Error al eliminar el curso.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'errorMsg' => 'Error: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>
