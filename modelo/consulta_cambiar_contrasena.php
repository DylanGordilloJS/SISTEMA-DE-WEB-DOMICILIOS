<?php 

require_once "../modelo/conexion.php";
require_once "../controlador/cambiar_contrasena.php";

$consulta = $conn->prepare("SELECT contrasena FROM Usuario WHERE id_usuario = :id");
$consulta->bindParam(':id', $id_actual, PDO::PARAM_INT);
$consulta->execute();
$contrasena_bd = $consulta->fetchColumn();

?>