<?php
session_start();
require_once __DIR__ . '/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?action=Servicios');
    exit;
}

$usuario  = $conn->real_escape_string(trim($_POST['usuario'] ?? ''));
$password = $_POST['contrasena'] ?? ($_POST['password'] ?? '');

// A dónde quiere ir después de loguearse (Servicios, Nosotros, etc.)
$redirect = $_POST['redirect'] ?? 'Servicios';

if ($usuario === '' || $password === '') {
    header('Location: ../index.php?action=' . urlencode($redirect) . '&error=1');
    exit;
}

$sql = "SELECT nombre, contrasena, rol FROM usuarios WHERE nombre = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row['contrasena']) || $password === $row['contrasena']) {
        $_SESSION['usuario'] = $row['nombre'];
        $_SESSION['rol']     = $row['rol'];

        // Redirige al front controller de tu proyecto en el hosting
        header('Location: ../index.php?action=' . urlencode($redirect));
        exit;
    }
}

// Si falla el login
header('Location: ../index.php?action=' . urlencode($redirect) . '&error=1');
exit;
