<?php
session_start();

// 1. Debe haber sesión iniciada
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../vista/html/login.html");
    exit();
}

require_once '../modelo/conexion.php';

// 2. Solo el administrador puede crear usuarios desde el panel
$rolActual = $conn->prepare("SELECT id_RolFK FROM Usuario WHERE id_usuario = ?");
$rolActual->execute([$_SESSION['id_usuario']]);
if ((int)$rolActual->fetchColumn() !== 1) {
    echo "<script>alert('Error: solo el administrador puede añadir usuarios.'); window.location.href = '../vista/html/login.html';</script>";
    exit();
}

function volverConError($mensaje) {
    echo "<script>alert('" . addslashes($mensaje) . "'); window.history.back();</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    volverConError('Error: no se recibió la solicitud correctamente.');
}

$nombre1          = trim($_POST['nombre1'] ?? '');
$apellido         = trim($_POST['apellido'] ?? '');
$tipo_documento   = $_POST['tipo_documento'] ?? '';
$numero_documento = trim($_POST['documento'] ?? '');
$email            = trim($_POST['email'] ?? '');
$direccion        = trim($_POST['direccion'] ?? '');
$telefono         = trim($_POST['telefono'] ?? '');
$contrasena       = $_POST['contrasena1'] ?? '';
$confirmar        = $_POST['contrasena_confirmar'] ?? '';
$rol              = (int)($_POST['rol'] ?? 0);

// 3. Validaciones del lado del servidor
if ($nombre1 === '' || $apellido === '' || $tipo_documento === '' || $numero_documento === '' ||
    $email === '' || $direccion === '' || $telefono === '' || $contrasena === '' || $confirmar === '') {
    volverConError('Error: todos los campos son obligatorios.');
}
if (!in_array($tipo_documento, ['CC', 'TI', 'CE', 'PAS', 'PEP'], true)) {
    volverConError('Error: el tipo de documento no es válido.');
}
if (!in_array($rol, [1, 2, 3], true)) {
    volverConError('Error: el rol seleccionado no es válido.');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    volverConError('Error: el correo electrónico no tiene un formato válido.');
}
if (!preg_match('/^[0-9]{8,18}$/', $numero_documento)) {
    volverConError('Error: el número de documento debe tener entre 8 y 18 números.');
}
if (!preg_match('/^[0-9]{7,15}$/', $telefono)) {
    volverConError('Error: el teléfono debe tener entre 7 y 15 números.');
}
if (strlen($contrasena) < 8 || strlen($contrasena) > 30) {
    volverConError('Error: la contraseña debe tener entre 8 y 30 caracteres.');
}
if ($contrasena !== $confirmar) {
    volverConError('Error: las contraseñas no coinciden.');
}

try {
    // 4. No repetir el mismo correo con el mismo rol (es la llave que usa el login)
    $existe = $conn->prepare("SELECT COUNT(*) FROM Usuario WHERE email = ? AND id_RolFK = ?");
    $existe->execute([$email, $rol]);
    if ((int)$existe->fetchColumn() > 0) {
        volverConError('Error: ya existe un usuario con ese correo y ese rol.');
    }

    // 5. Guardar el usuario con la contraseña cifrada
    $conn->beginTransaction();
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    $insertar = $conn->prepare("INSERT INTO Usuario (nombre_1, apellido_1, tipo_de_documento, numero_documento, dirrecion, telefono, email, contrasena, id_RolFK)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insertar->execute([$nombre1, $apellido, $tipo_documento, $numero_documento, $direccion, $telefono, $email, $hash, $rol]);
    $id_nuevo = $conn->lastInsertId();

    // Si es domiciliario, también se crea su registro en la tabla domiciliario
    if ($rol === 3) {
        $dom = $conn->prepare("INSERT INTO domiciliario (id_UsuarioFK) VALUES (?)");
        $dom->execute([$id_nuevo]);
    }

    $conn->commit();

    echo "<script>
            alert('¡Usuario registrado con éxito!');
            window.location.href = '../vista/html/admin.php?vista=usuarios';
          </script>";
    exit();

} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    volverConError('Error al registrar el usuario: ' . $e->getMessage());
}
?>
