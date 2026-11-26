<?php 
require_once "../modelo/conexion.php";
$id = $_POST["id_pedido"];

$nuevo_estado = "En camino";
$actulizar= $conn->prepare("UPDATE Pedido SET estado = :esto_nuevo WHERE id_pedido = :id_pedido");
$actulizar->bindParam("esto_nuevo",$nuevo_estado);
$actulizar->bindParam("id_pedido",$id);
$actulizar->execute();
if ($actulizar->execute()){
    header("location: ../vista/html/domicilios.php");
    exit();
}
?>