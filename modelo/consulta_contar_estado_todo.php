<?php 
require_once "../../modelo/conexion.php";

// 1. Pendientes
$nuevo_estado = "Pendiente";
$contar = $conn->prepare("SELECT COUNT(*) AS total FROM Pedido WHERE estado = :estado");
$contar->bindParam("estado", $nuevo_estado);
$contar->execute();
$resultado = $contar->fetch(PDO::FETCH_ASSOC);
$totalPendientes = $resultado['total'];

// 2. En camino
$nuevo_estado2 = "En camino";
$contar2 = $conn->prepare("SELECT COUNT(*) AS camino FROM Pedido WHERE estado = :estado");
$contar2->bindParam("estado", $nuevo_estado2);
$contar2->execute();
$resultado2 = $contar2->fetch(PDO::FETCH_ASSOC);
$totalEnCamino = $resultado2['camino'];

// 3. Entregados (Corregida la variable en bindParam)
$nuevo_estado3 = "Entregado";
$contar3 = $conn->prepare("SELECT COUNT(*) AS entregado FROM Pedido WHERE estado = :estado");
$contar3->bindParam("estado", $nuevo_estado3); // <-- Se corrigió $nuevo_estado2 por $nuevo_estado3
$contar3->execute();
$resultado3 = $contar3->fetch(PDO::FETCH_ASSOC);
$totalEntregados = $resultado3['entregado'];
?>