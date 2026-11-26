<?php 
require_once('../../modelo/conexion.php'); 
require_once '../html/configuraciones.php';


$consulta = $conn->prepare("SELECT * FROM Usuario WHERE id_usuario = :id");
$consulta->bindParam(':id', $id_actual, PDO::PARAM_INT);
$consulta->execute();
?>