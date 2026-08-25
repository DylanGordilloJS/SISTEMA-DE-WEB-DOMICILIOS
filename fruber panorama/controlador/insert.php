<?php
// Asegúrate de que los nombres de las columnas coincidan exactamente con tu base de datos
$insetar = $conn->exec("INSERT INTO Usuario (nombre_1, apellido_1, tipo_de_documento, numero_documento, dirrecion, telefono, email, contrasena,id_RolFK) 
VALUES ('$nombre1', '$apellido', '$tipo_documento', '$numero_documento', '$dirrecion', '$telefono', '$gmail', '$contrasena','$rol')");

print "<br>¡Usuario registrado correctamente!";
print '<br><a href="../vista/html/login.html" class="register">Volver</a>';
?>