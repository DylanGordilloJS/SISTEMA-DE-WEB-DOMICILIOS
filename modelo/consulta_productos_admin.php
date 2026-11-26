<?php 
require_once '../../modelo/conexion.php';
$consulta_Producto = $conn->prepare("SELECT id_producto,nombre_producto, precio FROM Producto");
$consulta_Producto->execute();
?>