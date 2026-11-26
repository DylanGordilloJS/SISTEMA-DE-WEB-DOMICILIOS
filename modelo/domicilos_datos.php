<?php 
require_once('../../modelo/conexion.php'); 
require_once '../html/domicilios.php';

$Pedidos = $conn->prepare("SELECT  Pedido.id_pedido, Pedido.direccion_entrega,Pedido.estado,Pedido.total,Usuario.nombre_1,Usuario.apellido_1 FROM Pedido INNER JOIN Usuario ON Pedido.id_UsuarioFK = Usuario.id_usuario");
$Pedidos->execute();
?>