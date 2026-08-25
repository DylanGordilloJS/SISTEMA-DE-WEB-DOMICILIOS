<?php
session_start();
require_once "../modelo/conexion.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../vista/html/login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pedido'])) {
    $id_pedido = (int) $_POST['id_pedido'];
    $id_usuario = $_SESSION['id_usuario'];

    // Solo se puede cancelar un pedido propio y que aún esté "Pendiente"
    $sql = "UPDATE Pedido 
            SET estado = 'Cancelado' 
            WHERE id_pedido = ? AND id_UsuarioFK = ? AND estado = 'Pendiente'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_pedido, $id_usuario]);

    header("Location: ../vista/html/detalles_pedido.php");
    exit();
}
?>