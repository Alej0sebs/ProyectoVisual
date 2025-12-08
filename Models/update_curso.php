<?php
header('Content-Type: application/json');
require 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';

if ($id <= 0) {
    echo json_encode(['success' => false, 'errorMsg' => 'ID de curso no válido.']);
    exit;
}

if (empty($nombre)) {
    echo json_encode(['success' => false, 'errorMsg' => 'El nombre del curso es obligatorio.']);
    exit;
}

$stmt = $conn->prepare("UPDATE cursos SET nombre = ? WHERE id = ?");
$stmt->bind_param("si", $nombre, $id);

try {
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Curso actualizado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'errorMsg' => 'Error al actualizar el curso.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'errorMsg' => 'Error: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>
