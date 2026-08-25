<?php
// para saber si esta activo
session_start();

// 1. Validar sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../vista/html/login.html");
    exit();
}

// 2. Verificar que los datos lleguen por método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Conectar a la base de datos
    require_once('../modelo/conexion.php');
    // varible que su definicion es  el id Usuario
    $id_actual = $_SESSION['id_usuario'];

    // Capturar datos enviados desde el formulario
    $contra_actual   = $_POST["Contra_actual"];
    $contra_nueva    = $_POST["nueva_contra"];
    $confirmar_nueva = $_POST["confirmar_contra"];

    // Validar que los campos no estén vacíos
    if (empty($contra_actual) || empty($contra_nueva) || empty($confirmar_nueva)) {
        echo "<script>alert('Por favor, complete todos los campos.'); window.history.back();</script>";
        exit();
    }

    // Validar que la nueva contraseña coincida con su confirmación
    if ($contra_nueva !== $confirmar_nueva) {
        echo "<script>alert('La nueva contraseña y la confirmación no coinciden.'); window.history.back();</script>";
        exit();
    }

    try {
        require_once "../modelo/consulta_cambiar_contrasena.php";

        // 3. Consultar la contraseña actual guardada en la base de datos para este usuario
        $consulta = $conn->prepare("SELECT contrasena FROM Usuario WHERE id_usuario = :id");
        $consulta->bindParam(':id', $id_actual, PDO::PARAM_INT);
        $consulta->execute();
        $contrasena_bd = $consulta->fetchColumn();
        
           // esta en string (varchar) para que pueda veruficar
        if ($contra_actual !== $contrasena_bd) {
            echo "<script>alert('La contraseña actual es incorrecta.'); window.history.back();</script>";
            exit();
        }


        // 5. Preparar y ejecutar la actualización de la contraseña
        $sql = "UPDATE Usuario SET contrasena = :nueva_contra WHERE id_usuario = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nueva_contra', $contra_nueva);
        $stmt->bindParam(':id', $id_actual, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>
                    alert('¡La contraseña se ha actualizado con éxito!'); 
                    window.location.href = '../vista/html/configuraciones.php';
                  </script>";
        } else {
            echo "<script>alert('Error: No se pudo actualizar la contraseña.'); window.history.back();</script>";
        }

    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
} else {
    // Si intentan entrar directo por URL, los expulsa a la vista
    header("Location: ../vista/html/configuraciones.php");
    exit();
}
?>
