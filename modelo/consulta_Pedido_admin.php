
<?php 
require_once '../../modelo/conexion.php';
$consulta_Pedido = $conn->prepare("SELECT Pedido.id_pedido, Pedido.total,Pedido.estado,  Pedido.id_UsuarioFK, Usuario.nombre_1 AS nombre_usuario FROM Pedido INNER JOIN Usuario ON Pedido.id_UsuarioFK = Usuario.id_usuario");
$consulta_Pedido->execute();
?>