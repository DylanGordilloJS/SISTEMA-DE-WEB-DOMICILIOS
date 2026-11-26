<?php 
require_once "../modelo/conexion.php";
require_once "../controlador/eliminar_cuenta.php";
$sql = "DELETE FROM Usuario WHERE id_usuario = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_actual, PDO::PARAM_INT);

?>