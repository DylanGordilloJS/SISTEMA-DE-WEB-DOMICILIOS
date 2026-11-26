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

    $id_eliminar = (int) $_POST['id_usuario'];

    // Evitar que el administrador se elimine a sí mismo
    if ($id_eliminar === (int) $_SESSION['id_usuario']) {
        echo "<script>
                alert('No puedes eliminar tu propia cuenta desde este panel.');
                window.history.back();
              </script>";
        exit();
    }

    require_once __DIR__ . '/../modelo/conexion.php';

    try {
        // Consultar el rol del usuario que se quiere eliminar
        $consulta_rol = $conn->prepare("SELECT id_RolFK FROM usuario WHERE id_usuario = :id");
        $consulta_rol->bindParam(':id', $id_eliminar, PDO::PARAM_INT);
        $consulta_rol->execute();
        $rol_eliminar = $consulta_rol->fetchColumn();

        if ($rol_eliminar === false) {
            echo "<script>
                    alert('Atención: El usuario seleccionado no existe.');
                    window.history.back();
                  </script>";
            exit();
        }

        // Proteger las cuentas de administrador
        if ((int) $rol_eliminar === 1) {
            echo "<script>
                    alert('No se puede eliminar una cuenta de administrador.');
                    window.history.back();
                  </script>";
            exit();
        }

        // Todo el borrado se hace en una transacción:
        // si algo falla, no se borra nada a medias.
        $conn->beginTransaction();

        // 1. Borrar el detalle de los pedidos del usuario
        $stmt = $conn->prepare(
            "DELETE dp FROM detalle_pedido dp
             INNER JOIN pedido p ON dp.id_pedidoFK = p.id_pedido
             WHERE p.id_UsuarioFK = :id"
        );
        $stmt->bindParam(':id', $id_eliminar, PDO::PARAM_INT);
        $stmt->execute();

        // 2. Borrar los pedidos del usuario
        $stmt = $conn->prepare("DELETE FROM pedido WHERE id_UsuarioFK = :id");
        $stmt->bindParam(':id', $id_eliminar, PDO::PARAM_INT);
        $stmt->execute();

        // 3. Si es domiciliario: liberar los pedidos que tenga asignados
        //    y borrar su registro en la tabla domiciliario
        $stmt = $conn->prepare("SELECT id_domiciliario FROM domiciliario WHERE id_UsuarioFK = :id");
        $stmt->bindParam(':id', $id_eliminar, PDO::PARAM_INT);
        $stmt->execute();
        $domiciliarios = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($domiciliarios as $id_domiciliario) {
            $liberar = $conn->prepare("UPDATE pedido SET id_domiciliarioFK = NULL WHERE id_domiciliarioFK = :id");
            $liberar->bindParam(':id', $id_domiciliario, PDO::PARAM_INT);
            $liberar->execute();
        }

        $stmt = $conn->prepare("DELETE FROM domiciliario WHERE id_UsuarioFK = :id");
        $stmt->bindParam(':id', $id_eliminar, PDO::PARAM_INT);
        $stmt->execute();

        // 4. Borrar el usuario
        $stmt = $conn->prepare("DELETE FROM usuario WHERE id_usuario = :id");
        $stmt->bindParam(':id', $id_eliminar, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $conn->commit();
            echo "<script>
                    alert('¡Usuario eliminado con éxito junto con su historial de pedidos!');
                    window.location.href = '../vista/html/admin.php';
                  </script>";
            exit();
        }

        $conn->rollBack();
        echo "<script>
                alert('Atención: El usuario seleccionado no existe.');
                window.history.back();
              </script>";
        exit();

    } catch (PDOException $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        echo "<script>
                alert('No se pudo eliminar el usuario: tiene registros vinculados en el sistema.');
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
