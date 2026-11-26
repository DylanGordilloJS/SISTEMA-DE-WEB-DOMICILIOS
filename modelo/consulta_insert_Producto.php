<?php
// conexion 
require_once "../modelo/conexion.php";
require_once "../controlador/agregar_producto_admin.php";
// Supongamos que ya tienes la conexión a la base de datos en $conexion (objeto PDO)
// Asumiendo que $conexion es tu objeto PDO
$sql = "INSERT INTO Producto (nombre_producto, precio) VALUES (:nombre, :precio)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':nombre', $nombre_producto);
$stmt->bindParam(':precio', $precio);
$stmt->execute();

?>