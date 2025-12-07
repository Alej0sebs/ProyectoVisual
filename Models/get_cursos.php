<?php
header('Content-Type: application/json');

require 'db.php';

$page  = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$rowsPerPage = isset($_POST['rows']) ? (int)$_POST['rows'] : 10;
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';

if ($page < 1) $page = 1;
if ($rowsPerPage < 1) $rowsPerPage = 10;

$offset = ($page - 1) * $rowsPerPage;

// Construir condición WHERE si hay búsqueda
$whereClause = '';
if ($nombre !== '') {
    $nombreLike = $conn->real_escape_string($nombre);
    $whereClause = "WHERE c.nombre LIKE '%$nombreLike%'";
}

// Contar total de cursos
$sqlTotal = "SELECT COUNT(*) AS total FROM cursos c $whereClause";
$resTotal = $conn->query($sqlTotal);
$total = 0;
if ($resTotal && $rowTotal = $resTotal->fetch_assoc()) {
    $total = (int)$rowTotal['total'];
}

// Obtener cursos con conteo de estudiantes inscritos
$sql = "SELECT 
    c.id, 
    c.nombre, 
    c.created_at,
    COUNT(ec.id) AS total_estudiantes
FROM cursos c
LEFT JOIN estudiante_curso ec ON c.id = ec.curso_id
$whereClause
GROUP BY c.id
ORDER BY c.id DESC
LIMIT $offset, $rowsPerPage";

$result = $conn->query($sql);

$rows = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Formatear fecha
        $row['created_at'] = date('d/m/Y', strtotime($row['created_at']));
        $rows[] = $row;
    }
}

echo json_encode([
    'total' => $total,
    'rows'  => $rows
]);

$conn->close();
?>
