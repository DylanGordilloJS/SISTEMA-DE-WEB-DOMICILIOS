<?php
// Siempre iniciamos la sesión para poder acceder al carrito
session_start();

// Verificamos si nos están enviando el ID (índice) del producto por la URL
if (isset($_GET['id'])) {
    $indice = $_GET['id'];

    // Verificamos si ese producto realmente existe en el carrito
    if (isset($_SESSION['carrito'][$indice])) {
        
        // Eliminamos el producto de la sesión
        unset($_SESSION['carrito'][$indice]);
        
        // Reorganizamos los índices del carrito para que no queden "huecos"
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
}

// Redirigimos al usuario de vuelta a la página del carrito
// Ajusta esta ruta si tu carrito está en otra ubicación
header('Location: ../vista/html/carrito.php'); 
exit();
?>