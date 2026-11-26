<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Validar sesión del administrador
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../vista/html/login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['id_producto'])) {
    
    $eliminar_producto = $_POST['id_producto'];



    try {
        require_once '../modelo/consulta_eliminar_producto.php';

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<script>
                    alert('¡Producto borrado con éxito de la base de datos!'); 
                    window.location.href = '../vista/html/admin.php';
                  </script>";
            exit();
        } else {
            echo "<script>
                    alert('Atención: El ID " . htmlspecialchars($eliminar_producto) . " no existe en la tabla Producto.'); 
                    window.history.back();
                  </script>";
        }

    } catch (PDOException $e) {
        echo "<script>
                alert('Error al borrar: El usuario tiene registros vinculados en otras tablas (pedidos, domicilios, etc.).'); 
                window.history.back();
              </script>";
    }
} else {
    // Si entra aquí, significa que $_POST['id_usuario'] no llegó
    echo "<script>
            alert('Error: No se recibió la solicitud correctamente. (El ID llegó vacío o no se envió por POST)'); 
            window.history.back();
          </script>";
}
?>