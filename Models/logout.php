<?php
session_start();

// Vaciar variables de sesión
$_SESSION = [];
session_unset();
session_destroy();

// Redirigir al inicio del sistema
header('Location: ../index.php?action=Inicio');
exit;
