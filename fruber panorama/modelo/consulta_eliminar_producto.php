<?php
// modelo/consulta_eliminar.php

require_once __DIR__ . '/conexion.php';

$db =  $conn ;


// Sentencia preparada para evitar inyecciones SQL
$sql = "DELETE FROM Producto WHERE id_producto = :id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $eliminar_producto, PDO::PARAM_INT);
?>