<?php
header('Content-Type: application/json');

require 'db.php';

$cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';

$page  = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$rowsPerPage = isset($_POST['rows']) ? (int)$_POST['rows'] : 10;

if ($page < 1) $page = 1;
if ($rowsPerPage < 1) $rowsPerPage = 10;

$offset = ($page - 1) * $rowsPerPage;

$where = "";
if ($cedula !== '') {
    $cedulaEsc = $conn->real_escape_string($cedula);
    $where = " WHERE cedula LIKE '%$cedulaEsc%'";
}

$sqlTotal = "SELECT COUNT(*) AS total FROM estudiantes" . $where;
$resTotal = $conn->query($sqlTotal);
$total = 0;
if ($resTotal && $rowTotal = $resTotal->fetch_assoc()) {
    $total = (int)$rowTotal['total'];
}

$sql = "SELECT id, cedula, nombre, apellido, telefono, direccion 
        FROM estudiantes
        $where
        LIMIT $offset, $rowsPerPage";

$result = $conn->query($sql);

$rows = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}

echo json_encode([
    'total' => $total,
    'rows'  => $rows
]);

$conn->close();
?>
