<?php
session_start();
require_once('../modelo/conexion.php'); 

// 1. Recibir y validar que los datos no estén vacíos
$correo = $_POST['correo'] ?? '';
$contrasena = $_POST['clave'] ?? ''; 
$rol = $_POST['rol'] ?? '';

try {
    // 2. Vincular el archivo de consulta
    require_once "../modelo/consulta_login.php";
    
    // 3. Vincular los parámetros de forma segura
    $consulta->bindParam(':correo', $correo); 
    $consulta->bindParam(':rol', $rol);
    
    $consulta->execute();

    // 4. Verificar si se encontró el usuario
    if ($consulta->rowCount() > 0) {
        
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
        $hashAlmacenado = $usuario['contrasena']; 

        // 5. Verificar la contraseña
        if (password_verify($contrasena, $hashAlmacenado)) {
            
            $_SESSION['id_usuario'] = $usuario['id_usuario']; 

            // Redireccionamiento según el rol
            switch ((int)$usuario['id_RolFK']) {
                case 1:
                    header('Location: ../vista/html/admin.php');   
                    exit();
                case 2:
                    header('Location: ../vista/html/fruber.php');    
                    exit();
                case 3:
                    header('Location: ../vista/html/domicilios.php');
                    exit();
                default:
                    echo "<script>
                        alert('❌ ERROR: El rol no está configurado en el sistema.');
                        window.location.href = '../vista/html/login.html';
                    </script>";
                    exit();
            }

        } else {
            // Contraseña incorrecta
            echo "<script>
                alert('❌ ERROR: Contraseña incorrecta. Vuelva a intentarlo.');
                window.location.href = '../vista/html/login.html';
            </script>";
            exit();
        }

    } else {
        // No se encontró el usuario o el rol no coincide
        echo "<script>
            alert('❌ ERROR: Credenciales incorrectas o el rol no coincide. Vuelva a intentarlo.');
            window.location.href = '../vista/html/login.html';
        </script>";
        exit();
    }

} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>