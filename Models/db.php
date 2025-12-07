<?php
$host = 'localhost';
$user = 'root';
$pass = ''; 
$dbname = 'cuarto2';
try{
$conn = new mysqli($host, $user, $pass, $dbname);
}catch(Exception $e){  
    echo json_encode(['errorMsg' => 'Error al actualizar el usuario: ' . $e->getMessage()]);
    exit;
}
if ($conn->connect_error) {
    die("Error de conexión: ". $conn->connect_error);
}

?>