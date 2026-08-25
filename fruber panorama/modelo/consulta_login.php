<?php
require_once "../modelo/conexion.php";
require_once "../controlador/procesamiento_login.php";
$consulta = $conn->prepare("SELECT * FROM Usuario WHERE email = :correo AND contrasena = :contrasena AND id_RolFK = :rol");
?>