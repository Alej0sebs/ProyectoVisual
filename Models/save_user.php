<?php
header('Content-Type: application/json');
require 'db.php';

$cedula    = isset($_POST['cedula'])    ? trim($_POST['cedula'])    : '';
$nombre    = isset($_POST['nombre'])    ? trim($_POST['nombre'])    : '';
$apellido  = isset($_POST['apellido'])  ? trim($_POST['apellido'])  : '';
$telefono  = isset($_POST['telefono'])  ? trim($_POST['telefono'])  : '';
$direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';

if ($cedula === '' || $nombre === '' || $apellido === '' || $telefono === '' || $direccion === '') {
    echo json_encode(['success' => false, 'errorMsg' => 'Todos los campos son obligatorios.']);
    exit;
}

if (!preg_match('/^\d{10}$/', $cedula)) {
    echo json_encode(['success' => false, 'errorMsg' => 'La cédula debe tener exactamente 10 dígitos numéricos.']);
    exit;
}

if (!preg_match('/^\d{10}$/', $telefono)) {
    echo json_encode(['success' => false, 'errorMsg' => 'El teléfono debe tener exactamente 10 dígitos numéricos.']);
    exit;
}

if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u', $nombre)) {
    echo json_encode(['success' => false, 'errorMsg' => 'El nombre solo puede contener letras y espacios.']);
    exit;
}

if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u', $apellido)) {
    echo json_encode(['success' => false, 'errorMsg' => 'El apellido solo puede contener letras y espacios.']);
    exit;
}

$check = $conn->prepare("SELECT 1 FROM estudiantes WHERE cedula = ? LIMIT 1");
$check->bind_param("s", $cedula);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'errorMsg' => 'Ya existe un estudiante con esa cédula.']);
    $check->close();
    exit;
}
$check->close();

$stmt = $conn->prepare(
    "INSERT INTO estudiantes (cedula, nombre, apellido, telefono, direccion) VALUES (?, ?, ?, ?, ?)"
);
$stmt->bind_param("sssss", $cedula, $nombre, $apellido, $telefono, $direccion);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Estudiante registrado correctamente']);
} else {
    echo json_encode(['success' => false, 'errorMsg' => 'No se pudo registrar el estudiante.']);
}

$stmt->close();
$conn->close();
?>
