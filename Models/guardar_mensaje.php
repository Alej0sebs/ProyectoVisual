<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $mensaje = $conn->real_escape_string($_POST['mensaje']);

    $sql = "INSERT INTO mensaje (nombre, correo, mensaje) VALUES ('$nombre', '$correo', '$mensaje')";

    if ($conn->query($sql) === TRUE) {
        header(header: 'Location: ../index.php?action=Contactanos');
    } else {
        echo "Error al enviar el mensaje: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Acceso no permitido.";
}
?>