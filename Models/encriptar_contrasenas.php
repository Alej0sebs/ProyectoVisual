<?php
require 'db.php';
$result = $conn->query("SELECT id, contrasena FROM usuarios");
while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $hash = password_hash($row['contrasena'], PASSWORD_DEFAULT);
    $conn->query("UPDATE usuarios SET contrasena='$hash' WHERE id=$id");
}
echo "Contraseñas encriptadas";
?>