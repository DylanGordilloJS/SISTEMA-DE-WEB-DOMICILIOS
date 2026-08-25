<?php 
    require_once '../modelo/conexion.php';
    require_once "../controlador/procesar_pago.php";
    $sql_pedido = "INSERT INTO Pedido (fecha_hora, metodo_pago, direccion_entrega, total, id_UsuarioFK) VALUES (?, ?, ?, ?, ?)";
    $stmt_pedido = $conn->prepare($sql_pedido);
    $stmt_pedido->execute([$fecha_hora, $metodo_pago, $direccion_entrega, $total_pagar, $id_usuario]);
?>