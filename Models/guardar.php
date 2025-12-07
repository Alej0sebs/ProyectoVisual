<?php
    include 'bd.php';
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $sql = "INSERT INTO estudiantes  VALUES ('$cedula', '$nombre', '$apellido', '$telefono', '$direccion')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Usuario guardado correctamente']);
    } else {
        echo json_encode(['errorMsg' => 'Error al guardar el usuario: ' . $conn->error]);
    }
    $conn->close();


?>