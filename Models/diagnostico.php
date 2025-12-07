<?php
// Script de diagnóstico para verificar la base de datos
header('Content-Type: text/html; charset=utf-8');

require 'db.php';

echo "<h1>Diagnóstico de Base de Datos</h1>";
echo "<hr>";

// Verificar conexión
if ($conn->connect_error) {
    echo "<p style='color:red;'>❌ Error de conexión: " . $conn->connect_error . "</p>";
    exit;
} else {
    echo "<p style='color:green;'>✅ Conexión exitosa a la base de datos</p>";
}

// Verificar tabla estudiantes
$result = $conn->query("SHOW TABLES LIKE 'estudiantes'");
if ($result->num_rows > 0) {
    echo "<p style='color:green;'>✅ Tabla 'estudiantes' existe</p>";
    
    $count = $conn->query("SELECT COUNT(*) as total FROM estudiantes")->fetch_assoc();
    echo "<p>   → Total de estudiantes: " . $count['total'] . "</p>";
} else {
    echo "<p style='color:red;'>❌ Tabla 'estudiantes' NO existe</p>";
}

// Verificar tabla cursos
$result = $conn->query("SHOW TABLES LIKE 'cursos'");
if ($result->num_rows > 0) {
    echo "<p style='color:green;'>✅ Tabla 'cursos' existe</p>";
    
    $count = $conn->query("SELECT COUNT(*) as total FROM cursos")->fetch_assoc();
    echo "<p>   → Total de cursos: " . $count['total'] . "</p>";
    
    // Mostrar cursos
    $cursos = $conn->query("SELECT * FROM cursos");
    if ($cursos->num_rows > 0) {
        echo "<ul>";
        while($curso = $cursos->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($curso['nombre']) . " (ID: " . $curso['id'] . ")</li>";
        }
        echo "</ul>";
    }
} else {
    echo "<p style='color:red;'>❌ Tabla 'cursos' NO existe</p>";
    echo "<p style='color:orange;'>⚠️ Necesitas ejecutar el archivo 'migration_cursos.sql'</p>";
}

// Verificar tabla estudiante_curso
$result = $conn->query("SHOW TABLES LIKE 'estudiante_curso'");
if ($result->num_rows > 0) {
    echo "<p style='color:green;'>✅ Tabla 'estudiante_curso' existe</p>";
    
    $count = $conn->query("SELECT COUNT(*) as total FROM estudiante_curso")->fetch_assoc();
    echo "<p>   → Total de inscripciones: " . $count['total'] . "</p>";
} else {
    echo "<p style='color:red;'>❌ Tabla 'estudiante_curso' NO existe</p>";
    echo "<p style='color:orange;'>⚠️ Necesitas ejecutar el archivo 'migration_cursos.sql'</p>";
}

echo "<hr>";
echo "<h2>Acciones necesarias:</h2>";
echo "<ol>";
echo "<li>Abre <strong>phpMyAdmin</strong></li>";
echo "<li>Selecciona la base de datos <strong>cuarto2</strong></li>";
echo "<li>Ve a la pestaña <strong>SQL</strong></li>";
echo "<li>Copia y pega el contenido del archivo <strong>Models/migration_cursos.sql</strong></li>";
echo "<li>Haz clic en <strong>Ejecutar</strong></li>";
echo "</ol>";

$conn->close();
?>
