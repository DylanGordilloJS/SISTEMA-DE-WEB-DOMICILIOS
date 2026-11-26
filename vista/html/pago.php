<?php 
session_start(); 

// Si intentan entrar al pago pero el carrito está vacío, los devolvemos
if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
    header('Location: fruber.php');
    exit();
}

// Calculamos el total para mostrárselo al cliente
$subtotal = 0;
$costo_envio = 4000;
foreach ($_SESSION['carrito'] as $producto) {
    $subtotal += ($producto['precio'] * $producto['cantidad']);
}
$total_pagar = $subtotal + $costo_envio;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - Fruver</title>
    <link rel="stylesheet" href="../css/fruber.css">
    <link rel="stylesheet" href="../css/pago.css">
</head>
<body>

    <header class="encabezado-fruver">
        <h1 class="logo-fruver">Fruver Panorama 🥕</h1>
        <nav class="navegacion-fruver">
            <ul>
                <li><a href="carrito.php">⬅️ Volver al Carrito</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="contenedor-pago">
            <h2 style="text-align: center;">Detalles de Envío</h2>
            
            <div class="total-destacado">
                Total a Pagar: $<?php echo number_format($total_pagar, 0, ',', '.'); ?>
            </div>

            <!-- Este formulario envía los datos a procesar_pago.php -->
            <form action="../../controlador/procesar_pago.php" method="POST">
                
                <div class="grupo-form">
                    <label for="direccion">Dirección de Entrega:</label>
                    <input type="text" id="direccion" name="direccion" maxlength="35" required placeholder="Ej: Calle 45 # 12-34, Barrio Centro">
                </div>

                <div class="grupo-form">
                    <label for="telefono">Teléfono de Contacto:</label>
                    <input type="number" id="telefono" name="telefono" oninput="if(this.value.length > 11) this.value = this.value.slice(0, 11);"required placeholder="Ej: 317 038 2341">
                </div>

                <div class="grupo-form">
                    <label for="metodo">Método de Pago:</label>
                    <input type="text" id="metodo" name="metodo_pago" value="Contra Entrega" readonly style="background-color: #f0f0f0;">
                </div>

                <button type="submit" class="boton-principal" style="width: 100%;">CONFIRMAR PEDIDO</button>
            </form>
        </div>
    </main>

</body>
</html>