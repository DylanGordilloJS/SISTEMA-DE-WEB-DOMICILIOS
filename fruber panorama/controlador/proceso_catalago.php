<?php
session_start();
// validando  POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Capturamos los datos validando que realmente existan
    // condicioles (? :)
    $nombre = isset($_POST['nombre_producto']) ? $_POST['nombre_producto'] : 'Desconocido';
    $precio = isset($_POST['precio_producto']) ? $_POST['precio_producto'] : 0;
    /// la cantidasd de input que son sale en el metodo post con su valor en html
    $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;

    // Creamos el carrito si no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }

    // Armamos el arreglo del producto
    $producto_nuevo = array(
        'nombre' => $nombre,
        'precio' => $precio,
        'cantidad' => $cantidad
    );

    // Lo añadimos a la sesión
    array_push($_SESSION['carrito'], $producto_nuevo);
    // Redirección directa 
    header('Location: ../vista/html/fruber.html'); 
    exit();
}
?>