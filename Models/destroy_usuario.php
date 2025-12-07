<?php
header('Content-Type: application/json');
require 'db.php';

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['errorMsg' => 'ID no proporcionada']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM usuarios WHERE id=?");
$stmt->bind_param("s", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['errorMsg' => 'Error al eliminar']);
}

$stmt->close();
$conn->close();
?>
