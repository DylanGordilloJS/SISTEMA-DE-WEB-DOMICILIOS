<?php
$consulta = $conn->prepare("SELECT * FROM Usuario WHERE email = :correo AND id_RolFK = :rol");
?>