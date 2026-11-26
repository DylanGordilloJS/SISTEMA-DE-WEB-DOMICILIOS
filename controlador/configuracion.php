<?php
session_start();

// 1. Validar sesión activa
if (!isset($_SESSION['id_usuario'])) {
    // Si no hay sesión, regresa dos niveles e ingresa a vista/html
    header("Location: ../vista/html/login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Conexión a la base de datos (sube un nivel a la raíz y entra a modelo)
    $id_actual = $_SESSION['id_usuario'];
    $nombre    = $_POST['nombre_1'];
    $apellido  = $_POST['apellido_1'];
    $correo    = $_POST['email'];
    $tipo_doc  = $_POST['tipo_de_documento'];
    $direccion = $_POST['dirrecion'];
    $telefono  = $_POST['telefono'];

    if (empty($nombre) || empty($apellido) || empty($correo) || empty($tipo_doc)) {
        echo "<script>alert('Por favor complete todos los campos obligatorios.'); window.history.back();</script>";
        exit();
    }

    try {
        require_once '../modelo/consulta_configuracion.php';
        
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':tipo_doc', $tipo_doc);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':id', $id_actual, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Regresa de forma segura usando la ruta exacta desde la carpeta controlador
            echo "<script>
                    alert('¡Tus datos se han guardado con éxito!'); 
                    window.location.href = '../vista/html/configuraciones.php';
                  </script>";
        } else {
            echo "<script>alert('Error: No se pudieron guardar los cambios.'); window.history.back();</script>";
        }

    } catch (PDOException $e) {
        echo "Error crítico al guardar en la base de datos: " . $e->getMessage();
    }
} else {
    header("Location: ../vista/html/configuraciones.php");
    exit();
}
?>
