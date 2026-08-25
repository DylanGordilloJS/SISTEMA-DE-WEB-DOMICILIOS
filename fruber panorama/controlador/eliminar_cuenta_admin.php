<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Validar sesión del administrador
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../vista/html/login.html");
    exit();
}

// 2. Verificar que la petición sea POST y que id_usuario no esté vacío
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['id_usuario'])) {
    
    $id_eliminar = $_POST['id_usuario'];

    // Evitar que el administrador se elimine a sí mismo
    if ($id_eliminar == $_SESSION['id_usuario']) {
        echo "<script>
                alert('No puedes eliminar tu propia cuenta desde este panel.'); 
                window.history.back();
              </script>";
        exit();
    }

    require_once __DIR__ . '/../modelo/conexion.php';

    try {
        // AQUÍ SE INCLUYE EL ARCHIVO CON EL NOMBRE EXACTO
        require_once __DIR__ . '/../modelo/consulta_admin_eliminar.php';

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<script>
                    alert('¡Usuario borrado con éxito de la base de datos!'); 
                    window.location.href = '../vista/html/admin.php';
                  </script>";
            exit();
        } else {
            echo "<script>
                    alert('Atención: El ID " . htmlspecialchars($id_eliminar) . " no existe en la tabla Usuario.'); 
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