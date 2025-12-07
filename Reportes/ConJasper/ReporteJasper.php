<?php
require_once 'JasperPHP.php';

use JasperPHP\JasperPHP;

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

$input = 'plantillas/Reporte_Estudiante.jasper';
$output = 'reportes_salida/Reporte_Estudiante';

if ($jasper->process($input, $output)) {
    $pdfFile = $output . '.pdf';

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . basename($pdfFile) . '"');
    header('Content-Length: ' . filesize($pdfFile));

    readfile($pdfFile);

} else {
    echo '<h2>Error al generar el PDF</h2>';
    echo '<pre>';
    echo 'Comando ejecutado:' . PHP_EOL;
    echo $jasper->getLastCommand() . PHP_EOL . PHP_EOL;

    echo 'Salida:' . PHP_EOL;
    echo $jasper->getLastOutput();
    echo '</pre>';
}
