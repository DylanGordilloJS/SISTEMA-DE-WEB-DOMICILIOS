<?php
// conexion 
require_once "../modelo/conexion.php";
require_once "../controlador/editar_producto_admin.php";
$sql = "UPDATE Producto SET nombre_producto = :nombre, precio = :precio WHERE id_producto = :id";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':nombre', $nombre_producto);
$stmt->bindParam(':precio', $precio);
$stmt->bindParam(':id', $id_producto);

?>