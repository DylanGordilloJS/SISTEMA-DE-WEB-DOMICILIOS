<?php
session_start();
require_once "../../modelo/conexion.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../vista/html/login.html");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Traemos los pedidos del usuario
$sql_pedidos = "SELECT id_pedido, fecha_hora, metodo_pago, direccion_entrega, total, estado 
                 FROM Pedido 
                 WHERE id_UsuarioFK = ? 
                 ORDER BY fecha_hora DESC";
$stmt_pedidos = $conn->prepare($sql_pedidos);
$stmt_pedidos->execute([$id_usuario]);
$pedidos = $stmt_pedidos->fetchAll(PDO::FETCH_ASSOC);

// Preparamos la consulta de detalle (productos) por pedido
$sql_detalle = "SELECT p.nombre_producto, d.cantidad, d.precio_unitario 
                 FROM Detalle_Pedido d
                 JOIN Producto p ON d.id_productoFK = p.id_producto
                 WHERE d.id_pedidoFK = ?";
$stmt_detalle = $conn->prepare($sql_detalle);

// Colores y clases según estado (igual que tu diseño original)
$estilos_estado = [
    'Pendiente' => ['color' => '#f39c12', 'clase' => 'estado-pendiente'],
    'En camino' => ['color' => '#3498db', 'clase' => 'estado-camino'],
    'Entregado' => ['color' => '#2ecc71', 'clase' => 'estado-entregado'],
    'Cancelado' => ['color' => '#e74c3c', 'clase' => 'estado-cancelado'],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos - Detalles</title>
    <link rel="icon" type="image/png" href="../asset/carrito .jpg">
    <link rel="stylesheet" href="../css/datalles_pedido.css"> 
</head>
<body>

    <div class="contenedor">
          <a href="../../vista/html/fruber.php" class="enlace-volver">&larr; Volver al inicio</a>
        <h2>Detalles de mis Pedidos</h2>

        <?php if (count($pedidos) === 0): ?>
            <p>Todavía no tienes pedidos registrados.</p>
        <?php endif; ?>

        <?php foreach ($pedidos as $pedido): 
            $estilo = $estilos_estado[$pedido['estado']] ?? ['color' => '#7f8c8d', 'clase' => ''];

            // Traemos los productos de este pedido específico
            $stmt_detalle->execute([$pedido['id_pedido']]);
            $productos = $stmt_detalle->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="tarjeta-pedido" style="border-left-color: <?= $estilo['color'] ?>;">
            <div class="cabecera-pedido">
                <div>
                    <h3>Pedido #<?= str_pad($pedido['id_pedido'], 3, '0', STR_PAD_LEFT) ?></h3>
                    <small>Fecha: <?= $pedido['fecha_hora'] ?> | Dirección: <?= htmlspecialchars($pedido['direccion_entrega']) ?></small>
                </div>
                <span class="etiqueta <?= $estilo['clase'] ?>"><?= $pedido['estado'] ?></span>
            </div>

            <table class="tabla-productos">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unit.</th>
                    <th>Subtotal</th>
                </tr>
                <?php foreach ($productos as $prod): 
                    $subtotal = $prod['cantidad'] * $prod['precio_unitario'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($prod['nombre_producto']) ?></td>
                    <td><?= $prod['cantidad'] ?></td>
                    <td>$<?= number_format($prod['precio_unitario'], 0, ',', '.') ?></td>
                    <td>$<?= number_format($subtotal, 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </table>

            <div class="total-pedido">
                Total a pagar: $<?= number_format($pedido['total'], 0, ',', '.') ?>
            </div>

            <div class="acciones">
                <?php if ($pedido['estado'] === 'Pendiente'): ?>
                    <form action="../../modelo/consulta_cancelar_pedido.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar este pedido?');">
                        <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido'] ?>">
                        <button type="submit" class="btn-cancelar">Cancelar Pedido</button>
                    </form>
                <?php else: ?>
                    <button type="button" class="btn-cancelar btn-desactivado" disabled>Cancelar Pedido</button>
                    <small style="display: block; margin-top: 5px; color: #7f8c8d;">
                        <?php
                            echo match($pedido['estado']) {
                                'En camino' => 'El pedido ya está en camino, no se puede cancelar.',
                                'Entregado' => 'Este pedido ya fue entregado.',
                                'Cancelado' => 'Este pedido fue cancelado.',
                                default => ''
                            };
                        ?>
                    </small>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

    </div>

</body>
</html>