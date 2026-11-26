<?php
// Asegúrate de que los nombres de las columnas coincidan exactamente con tu base de datos
$insetar = $conn->exec("INSERT INTO Usuario (nombre_1, apellido_1, tipo_de_documento, numero_documento, dirrecion, telefono, email, contrasena,id_RolFK) 
VALUES ('$nombre1', '$apellido', '$tipo_documento', '$numero_documento', '$dirrecion', '$telefono', '$gmail', '$hash_password','$rol')");

// NUEVO: si el rol es Domiciliario, crear también su registro en la tabla domiciliario
if ($rol == 3) {
    $id_usuario_nuevo = $conn->lastInsertId();
    $conn->exec("INSERT INTO domiciliario (id_UsuarioFK) VALUES ('$id_usuario_nuevo')");
}
?>
