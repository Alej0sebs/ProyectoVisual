<?php
header('Content-Type: application/json');
require 'db.php';

$cedulaVieja = isset($_GET['cedulaVieja']) ? trim($_GET['cedulaVieja']) : null;

// También recibimos la cédula que viene del formulario (por si la tocaron)
$cedulaNueva = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';

$nombre    = isset($_POST['nombre'])    ? trim($_POST['nombre'])    : '';
$apellido  = isset($_POST['apellido'])  ? trim($_POST['apellido'])  : '';
$telefono  = isset($_POST['telefono'])  ? trim($_POST['telefono'])  : '';
$direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';

if (!$cedulaVieja) {
    echo json_encode(['success' => false, 'errorMsg' => 'Cédula de usuario no proporcionada.']);
    exit;
}

if (!preg_match('/^\d{10}$/', $cedulaVieja)) {
    echo json_encode(['success' => false, 'errorMsg' => 'La cédula del usuario es inválida.']);
    exit;
}

if ($nombre === '' || $apellido === '' || $telefono === '' || $direccion === '') {
    echo json_encode(['success' => false, 'errorMsg' => 'Todos los campos son obligatorios.']);
    exit;
}

if (!preg_match('/^\d{10}$/', $telefono)) {
    echo json_encode(['success' => false, 'errorMsg' => 'El teléfono debe tener exactamente 10 dígitos numéricos.']);
    exit;
}

// Nombre: solo letras y espacios
if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u', $nombre)) {
    echo json_encode(['success' => false, 'errorMsg' => 'El nombre solo puede contener letras y espacios.']);
    exit;
}

// Apellido: solo letras y espacios
if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u', $apellido)) {
    echo json_encode(['success' => false, 'errorMsg' => 'El apellido solo puede contener letras y espacios.']);
    exit;
}

/* 🚫 No permitir cambiar la cédula */
if ($cedulaNueva !== '' && $cedulaNueva !== $cedulaVieja) {
    echo json_encode([
        'success'  => false,
        'errorMsg' => 'No se puede editar la cédula del estudiante.'
    ]);
    exit;
}

// Actualizar SOLO otros campos
$stmt = $conn->prepare("
    UPDATE estudiantes 
    SET nombre = ?, apellido = ?, telefono = ?, direccion = ?
    WHERE cedula = ?
");

$stmt->bind_param("sssss", $nombre, $apellido, $telefono, $direccion, $cedulaVieja);

try {
    $stmt->execute();

    if ($stmt->affected_rows >= 0) {
        echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'errorMsg' => 'No se pudo actualizar el usuario.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'errorMsg' => 'Error al actualizar el usuario.']);
}

$stmt->close();
$conn->close();
?>
