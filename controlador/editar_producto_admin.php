<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Validar sesión del administrador
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../vista/html/login.html");
    exit();
}

// 2. Validar que la petición sea POST y que venga el ID del producto
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['id_producto'])) {
    $id_producto = $_POST['id_producto'];
    $nombre_producto = $_POST['nombre_producto'] ?? '';
    $precio = $_POST['precio'] ?? '';

    // Validación básica de campos vacíos
    if (empty($nombre_producto) || empty($precio)) {
        echo "<script>
                alert('Error: Todos los campos son obligatorios.');
                window.history.back();
              </script>";
        exit();
    }

    try {
        // Incluir el archivo que realiza la consulta UPDATE en la base de datos
        require_once '../modelo/consulta_editar_producto_admin.php';

        // Ejecutar la sentencia preparada (definida en el modelo)
        $stmt->execute();

        // Verificar si se actualizó algún registro
        if ($stmt->rowCount() > 0) {
            echo "<script>
                    alert('¡Producto actualizado con éxito en la base de datos!'); 
                    window.location.href = '../vista/html/admin.php';
                  </script>";
            exit();
        } else {
            echo "<script>
                    alert('Atención: No se realizaron cambios o el ID " . htmlspecialchars($id_producto) . " no existe.'); 
                    window.history.back();
                  </script>";
            exit();
        }

    } catch (PDOException $e) {
        echo "<script>
                alert('Error al actualizar: " . addslashes($e->getMessage()) . "'); 
                window.history.back();
              </script>";
        exit();
    }

} else {
    echo "<script>
            alert('Error: No se recibió la solicitud correctamente. (El ID del producto llegó vacío)'); 
            window.history.back();
          </script>";
    exit();
}
?>