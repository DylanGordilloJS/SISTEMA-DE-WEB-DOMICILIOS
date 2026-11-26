<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Obtener y limpiar datos del formulario
    $nombre1          = trim($_POST['nombre1'] ?? '');
    $apellido         = trim($_POST['apellido'] ?? '');
    $tipo_documento   = trim($_POST['tipo_documento'] ?? '');
    $numero_documento = trim($_POST['documento'] ?? '');
    $gmail            = trim($_POST['email'] ?? '');
    $dirrecion        = trim($_POST['direccion'] ?? '');
    $telefono         = trim($_POST['telefono'] ?? '');
    $contrasena       = $_POST['contrasena1'] ?? '';
    $confir_contra    = $_POST['contrasena_confirmar'] ?? '';
    $rol              = trim($_POST['rol'] ?? '');

    // Validar coincidencia de contraseñas
    if ($contrasena !== $confir_contra) {
        echo "<script>
                alert('Error: Las contraseñas no coinciden.');
                window.location.href = '../vista/html/register.html';
              </script>";
        exit;
    }

    require_once("../modelo/conexion.php");

    // Validar si el correo ya existe
    $stmt = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE email = :email");
    $stmt->bindParam(':email', $gmail, PDO::PARAM_STR);
    $stmt->execute();
    $existe_correo = $stmt->fetchColumn();

    if ($existe_correo > 0) {
        echo "<script>
                alert('Error: El correo electrónico ya se encuentra registrado.');
                window.location.href = '../vista/html/register.html';
              </script>";
        exit;
    }

    // 4. Encriptar contraseña (nombre de variable corregido)
    $hash_password = password_hash($contrasena, PASSWORD_DEFAULT);

    // 5. Incluir el script de inserción
    require_once("../controlador/insert.php");

    // 6. Redireccionar al login
    header("Location: ../vista/html/login.html");
    exit;
}
?>