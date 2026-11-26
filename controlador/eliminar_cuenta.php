<?php
session_start();

// 1. Validar que el usuario tenga la sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../vista/html/login.html");
    exit();
}

// 2. Verificar que la petición se envíe mediante el formulario (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Conectar a la base de datos
    require_once '../modelo/conexion.php';
    $id_actual = $_SESSION['id_usuario'];

    try {
        // 3. Verificar si el cliente tiene pedidos registrados
        $verificar = $conn->prepare("SELECT COUNT(*) FROM pedido WHERE id_UsuarioFK = :id");
        $verificar->bindParam(':id', $id_actual, PDO::PARAM_INT);
        $verificar->execute();

        if ((int) $verificar->fetchColumn() > 0) {
            // Tiene historial: la cuenta NO se puede eliminar
            echo "<script>
                    alert('No se puede borrar la cuenta: Tienes historial o pedidos registrados en el sistema.');
                    window.history.back();
                  </script>";
            exit();
        }

        // 4. Sin pedidos: eliminar la cuenta
        require_once "../modelo/consulta_eliminar.php";

        if ($stmt->execute()) {
            // Destruir la sesión por completo en el servidor porque el usuario ya no existe
            session_unset();
            session_destroy();

            // Mensaje de éxito y redirección automática hacia la pantalla de login
            echo "<script>
                    alert('¡Tu cuenta ha sido eliminada permanentemente de Fruver Panorama!');
                    window.location.href = '../vista/html/login.html';
                  </script>";
        } else {
            echo "<script>alert('Error: No se pudo eliminar la cuenta de la base de datos.'); window.history.back();</script>";
        }

    } catch (PDOException $e) {
        // Alerta en caso de que el usuario tenga compras o llaves foráneas amarradas en la BD
        echo "<script>
                alert('No se puede borrar la cuenta: Tienes historial o pedidos registrados en el sistema.');
                window.history.back();
              </script>";
    }
} else {
    // Si intentan acceder al archivo de forma directa por la URL
    header("Location: ../vista/html/configuraciones.php");
    exit();
}
?>
