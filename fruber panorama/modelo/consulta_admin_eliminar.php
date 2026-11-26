<?php
// modelo/consulta_eliminar.php
require_once __DIR__ . '/conexion.php';
// Sentencia preparada para evitar inyecciones SQL
$sql = "DELETE FROM Usuario WHERE id_usuario = :id";
$stmt =  $conn ->prepare($sql);
$stmt->bindParam(':id', $id_eliminar, PDO::PARAM_INT);
?>