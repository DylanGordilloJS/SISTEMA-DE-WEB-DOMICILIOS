<?php
session_start();

// 1. Validar sesión activa
if (!isset($_SESSION['id_usuario'])) {
    // Si no hay sesión, regresa dos niveles e ingresa a vista/html
    header("Location: ../vista/html/login.html");
    exit();
}

// 2. Validar que la petición sea por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_producto = trim($_POST['nombre_producto'] ?? '');
    $precio = trim($_POST['precio'] ?? '');

    // Validación básica de campos vacíos
    if (empty($nombre_producto) || empty($precio)) {
        echo "<script>
                alert('Error: Todos los campos son obligatorios.');
                window.history.back();
              </script>";
        exit();
    }
    if (strlen($nombre_producto) < 2 || strlen($nombre_producto) > 50) {
        echo "<script>alert('Error: el nombre del producto debe tener entre 2 y 50 caracteres.'); window.history.back();</script>";
        exit();
    }
    if (!is_numeric($precio) || (float)$precio < 0 || (float)$precio > 99999) {
        echo "<script>alert('Error: el precio debe ser un numero valido entre 0 y 99999.'); window.history.back();</script>";
        exit();
    }

    try {
        // Incluir el archivo que maneja la consulta PDO
        require_once '../modelo/consulta_insert_Producto.php'; 

        // Verificamos si la inserción fue exitosa
        if ($stmt->rowCount() > 0) {
            echo "<script>
                    alert('¡Producto registrado con éxito en la base de datos!'); 
                    window.location.href = '../vista/html/admin.php';
                  </script>";
            exit();
        } else {
            echo "<script>
                    alert('Atención: No se pudo registrar el producto.'); 
                    window.history.back();
                  </script>";
            exit();
        }

    } catch (PDOException $e) {
        // Manejo de errores en la base de datos
        echo "<script>
                alert('Error al registrar el producto: " . addslashes($e->getMessage()) . "'); 
                window.history.back();
              </script>";
        exit();
    }

} else {
    // Si intentan acceder al script directamente por la URL sin enviar POST
    echo "<script>
            alert('Error: No se recibió la solicitud correctamente.'); 
            window.history.back();
          </script>";
    exit();
}
?>