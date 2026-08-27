<?php
require_once "../../modelo/conexion.php";
$nuevo_estado = "Entregado"; 

$suma_valores = $conn->prepare("SELECT SUM(total) AS suma_total FROM Pedido WHERE estado = :estado");
$suma_valores->bindParam(":estado", $nuevo_estado, PDO::PARAM_STR);
$suma_valores->execute();
$resultado = $suma_valores->fetch(PDO::FETCH_ASSOC);

// Obtenemos la suma total (si no hay resultados, devolvemos 0 por seguridad)
$total_dinero = $resultado['suma_total'] ?? 0;
?>