<?php
header('Content-Type: application/json');
require 'db.php';

$cedula = $_POST['cedula'] ?? null;

if (!$cedula) {
    echo json_encode(['errorMsg' => 'Cédula no proporcionada']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM estudiantes WHERE cedula=?");
$stmt->bind_param("s", $cedula);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['errorMsg' => 'Error al eliminar']);
}

$stmt->close();
$conn->close();
?>
