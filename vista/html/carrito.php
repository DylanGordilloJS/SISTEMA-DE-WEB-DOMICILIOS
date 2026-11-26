<?php
session_start();
$hayProductos = isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <link rel="icon" type="image/png" href="../asset/carrito .jpg">
    <link rel="stylesheet" href="../css/carrito.css"> 
</head>
<body>

    <header class="encabezado-fruver">
        <h1 class="logo-fruver">Fruber Panorama 🥕</h1>
        <nav class="navegacion-fruver">
            <ul>
                <!-- Ajusta este enlace hacia tu catálogo principal -->
                <li><a href="../../vista/html/detalles_pedido.php" >🏻 detalles del pedido</a></li>
                <li><a href="../../vista/html/fruber.php">🛍️ Volver a la Tienda</a></li>
                <?php if ($hayProductos): ?>
                <li class="carrito-fruver"><a href="../../vista/html/pago.php">Pagar</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    
    <main class="menu-productos-fruver">
        <h2>📦 Mi Cesta de Compra</h2>

        <section class="resumen-carrito">
            
            <?php
            // 1. Verificamos si la sesión 'carrito' existe y tiene productos adentro
            if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
                
                $subtotal_general = 0;
                $costo_envio = 4000; // Valor fijo de envío

                // 2. Recorremos cada producto ($indice es la posición, $producto son los datos)
                foreach ($_SESSION['carrito'] as $indice => $producto) {
                    
                    // Multiplicamos el precio por la cantidad de este producto específico
                    $subtotal_item = $producto['precio'] * $producto['cantidad'];
                    
                    // Lo vamos sumando al subtotal general de toda la compra
                    $subtotal_general += $subtotal_item; 
                    ?>
                    
                    <!-- 3. Imprimimos el HTML de cada producto con sus datos reales -->
                    <div class="item-carrito">
                        <span class="item-nombre"><?php echo htmlspecialchars($producto['nombre']); ?></span>
                        <span class="item-cantidad">x <?php echo htmlspecialchars($producto['cantidad']); ?></span>
                        <span class="item-subtotal">$<?php echo number_format($subtotal_item, 0, ',', '.'); ?></span>
                        
                        <!-- 4. Botón de eliminar apuntando al archivo eliminar_carrito.php enviando el $indice -->
                        <a href="../../controlador/eliminar_carrito.php?id=<?php echo $indice; ?>" class="boton-eliminar" title="Eliminar producto" style="color: red; text-decoration: none; font-size: 1.2em; margin-left: 15px;">🗑️</a>
                    </div>

                    <?php
                } // Aquí termina el ciclo foreach
                
                // 5. Calculamos el total final sumando el subtotal y el envío
                $total_pagar = $subtotal_general + $costo_envio;
                ?>
                
                <!-- 6. Imprimimos la sección de totales -->
                <hr> 
                <div class="totales-carrito">
                    <div class="total-fila">
                        <span class="total-etiqueta">Subtotal:</span>
                        <span class="total-valor">$<?php echo number_format($subtotal_general, 0, ',', '.'); ?></span>
                    </div>
                    <div class="total-fila">
                        <span class="total-etiqueta">Costo de Envío:</span>
                        <span class="total-valor">$<?php echo number_format($costo_envio, 0, ',', '.'); ?></span>
                    </div>
                    <div class="total-fila total-final">
                        <span class="total-etiqueta">TOTAL A PAGAR:</span>
                        <span class="total-valor precio-fruver">$<?php echo number_format($total_pagar, 0, ',', '.'); ?></span>
                    </div>
                </div>

                <button class="boton-principal">FINALIZAR PEDIDO (PAGO CONTRA ENTREGA)</button>

            <?php
            } else {
                // 7. Si el carrito está vacío, mostramos este mensaje en lugar de los totales
                echo '<p style="text-align: center; padding: 20px; font-size: 1.2em; color: #555;">Tu cesta está vacía. ¡Anímate a agregar algunas frutas y verduras!</p>';
            }
            ?>

        </section>
    </main>

</body>
</html>