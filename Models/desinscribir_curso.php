<?php
header('Content-Type: application/json');
require 'db.php';

$estudiante_id = isset($_POST['estudiante_id']) ? (int)$_POST['estudiante_id'] : 0;
$curso_id = isset($_POST['curso_id']) ? (int)$_POST['curso_id'] : 0;

if ($estudiante_id <= 0 || $curso_id <= 0) {
    echo json_encode(['success' => false, 'errorMsg' => 'Datos no válidos.']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM estudiante_curso WHERE estudiante_id = ? AND curso_id = ?");
$stmt->bind_param("ii", $estudiante_id, $curso_id);

try {
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Estudiante desinscrito correctamente.']);
    } else {
        echo json_encode(['success' => false, 'errorMsg' => 'Error al desinscribir al estudiante.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'errorMsg' => 'Error: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>
