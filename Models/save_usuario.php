<?php
header('Content-Type: application/json');
require 'db.php';

$nombre = $_POST['nombre'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';
$rol = $_POST['rol'] ?? '';

// Encriptar la contrasena antes de guardar
$contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO usuarios (nombre, contrasena, rol) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nombre, $contrasena_hash, $rol);

try {
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'usuario registrado correctamente']);
} catch (Exception $e) {
    echo json_encode(['errorMsg' => 'Error al registrar el usuario: ' . $e->getMessage()]);
    exit;
}

$stmt->close();
$conn->close();
?>