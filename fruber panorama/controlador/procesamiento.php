<?php
if ($_POST) {
    // Variables del register
    $nombre1 = $_POST['nombre1'];
    $apellido = $_POST['apellido'];
    $tipo_documento = $_POST['tipo_documento'];
    $numero_documento = $_POST['documento'];
    $gmail = $_POST['email'];
    $dirrecion = $_POST['direccion']; // esta es la direccion
    $telefono = $_POST['telefono'];
    $contrasena = $_POST['contrasena1'];
    $confir_contra = $_POST['contrasena_confirmar'];
    $rol = $_POST['rol'];

    // Validación: Solo si coinciden se procede con el registro y la base de datos
    if ($contrasena === $confir_contra) {
        echo "Las contraseñas y los correos son correctos.<br>";
        echo "Se registró correctamente " . $nombre1;
        echo "con el rol : ".$rol;
        // Se conectan y guardan los datos únicamente si la validación es exitosa
        require_once("../modelo/conexion.php");
        require_once("../controlador/insert.php");
        
    } else {
        echo "Error: Las contraseñas no coinciden o los datos de correo/dirección no son iguales.";
    }

}
?>