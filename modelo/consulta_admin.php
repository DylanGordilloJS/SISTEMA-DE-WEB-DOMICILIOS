<?php
require_once '../../modelo/conexion.php';
$consulta = $conn->prepare("SELECT id_usuario, nombre_1, apellido_1, email, id_RolFK FROM Usuario");
// 2. Ejecutamos la consulta
$consulta->execute();
?>