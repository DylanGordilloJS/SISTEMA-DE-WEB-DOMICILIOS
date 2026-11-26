<?php
session_start();

if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
    header('Location: ../vista/html/carrito.php');
    exit();
}

// 1. Validar sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../vista/html/login.html"); 
    exit();
}

// 1. Recibir los datos del formulario de pago
$direccion_entrega = isset($_POST['direccion']) ? $_POST['direccion'] : 'No especificada';
$metodo_pago = isset($_POST['metodo_pago']) ? $_POST['metodo_pago'] : 'Contra Entrega';


try {
    // Calculamos los totales
    $subtotal = 0;
    $costo_envio = 4000;
    foreach ($_SESSION['carrito'] as $producto) {
        $subtotal += ($producto['precio'] * $producto['cantidad']);
    }
    $total_pagar = $subtotal + $costo_envio;

    $fecha_hora = date('Y-m-d H:i:s');
    $id_usuario = $_SESSION['id_usuario'];

    //  hacer la conxecion la consulta
    require_once "../modelo/consulta_pago.php";
    // Obtenemos el ID generado usando $conn
    $id_pedido_generado = $conn->lastInsertId();

    $sql_detalle = "INSERT INTO Detalle_Pedido (id_pedidoFK, id_productoFK, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
    $stmt_detalle = $conn->prepare($sql_detalle);

    $sql_buscar_producto = "SELECT id_producto FROM Producto WHERE nombre_producto = ?";
    $stmt_buscar = $conn->prepare($sql_buscar_producto);

    foreach ($_SESSION['carrito'] as $prod) {
        $stmt_buscar->execute([$prod['nombre']]);
        $resultado = $stmt_buscar->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            $id_producto_real = $resultado['id_producto'];
            $stmt_detalle->execute([
                $id_pedido_generado, 
                $id_producto_real, 
                $prod['cantidad'], 
                $prod['precio']
            ]);
        }
    }
// 4. Vaciamos el carrito tras el éxito
    unset($_SESSION['carrito']);

    echo "<script>
            alert('¡Tu pedido ha sido registrado con éxito! Estará llegando pronto a: " . $direccion_entrega . "');
            window.location.href = '../vista/html/fruber.html';
          </script>";

} catch (Exception $e) {
    echo "Error al registrar el pedido: " . $e->getMessage();
}
?>
