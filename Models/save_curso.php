<?php
header('Content-Type: application/json');
require 'db.php';

$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';

if (empty($nombre)) {
    echo json_encode(['success' => false, 'errorMsg' => 'El nombre del curso es obligatorio.']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO cursos (nombre) VALUES (?)");
$stmt->bind_param("s", $nombre);

try {
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Curso creado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'errorMsg' => 'Error al crear el curso.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'errorMsg' => 'Error: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>
