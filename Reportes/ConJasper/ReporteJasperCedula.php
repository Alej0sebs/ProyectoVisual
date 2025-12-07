<?php
require_once 'JasperPHP.php';

use JasperPHP\JasperPHP;

// Validar parámetro
if (!isset($_GET['cedula'])) {
    die("Cédula no proporcionada.");
}

$cedula = $_GET['cedula'];

// Configura Jasper
$jasper = new JasperPHP();
$jasper->setDbConfig(
    user: 'root',
    pass: '',         // aquí pon tu contraseña si tienes, si no, deja ''
    host: 'localhost',
    name: 'cuarto2',
    port: 3306,
    driver: 'com.mysql.cj.jdbc.Driver',
    jdbcDir: 'lib'
);

// Rutas

$input = 'plantillas/Reporte_Estudiante_cedula.jrxml';
$output = 'reportes_salida/Reporte_Estudiante_cedula';

// Ejecuta el reporte
$exito = $jasper->process(
    $input,
    $output,
    ['pdf'],
    ['cedula' => $cedula] // Pasamos el parámetro al .jasper
);

if ($exito && file_exists($output . '.pdf')) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="Estudiante_' . $cedula . '.pdf"');
    readfile($output . '.pdf');
    exit;
} else {
    echo "<pre>";
    echo "No se pudo generar el reporte.\n\n";
    echo "Comando ejecutado:\n" . $jasper->getLastCommand() . "\n\n";
    echo "Salida:\n" . $jasper->getLastOutput();
    echo "</pre>";
}
