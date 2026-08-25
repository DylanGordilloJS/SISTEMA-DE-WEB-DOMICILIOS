<?php
session_start();
echo "<pre>";
require_once('../modelo/conexion.php'); 
// 1. Recibir y validar que los datos no estén vacíos
$correo = $_POST['correo'];
$contrasena = $_POST['calve']; 
$rol = $_POST['rol'];

// si la cadaena esta vacia
if (empty($correo) || empty($contrasena) || empty($rol)) {
    die('Por favor, complete todos los campos.');
}

try {
    // vincular archivo
    require_once "../modelo/consulta_login.php";
    //vincular los parametros 
    // 3. Vincular los parámetros de forma segura
    $consulta->bindParam(':correo', $correo); 
    $consulta->bindParam(':contrasena', $contrasena);
    $consulta->bindParam(':rol', $rol);
    
    $consulta->execute();

    // 4. Verificar si se encontró el usuario
    if ($consulta->rowCount() > 0) {
      // Extraemos la fila del usuario de la base de datos
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
    $_SESSION['id_usuario'] = $usuario['id_usuario']; 
    // para mostar que id de usuario es 
    echo "¡ID de usuario capturado con éxito!: " . $_SESSION['id_usuario'] . "<br>";
 //verificando con por numero
            switch ((int)$usuario['id_RolFK']) {
                case 1:
                    echo '¡Bienvenido Administrador!';
                    echo '<br><a href="../vista/html/admin.php" class="register">Entrar al Panel de Control</a>';
                    break;
                case 2:
                    echo '¡Ingreso exitoso!';
                    echo '<br><a href="../vista/html/fruber.html" class="register">Entrar a la Tienda</a>';
                    break;
                case 3:
                    echo '¡Bienvenido al sistema de entregas!';
                    echo '<br><a href="../vista/html/domicilios.php" class="register">Ver Pedidos</a>';
                    break;
                default:
                    echo '❌ ERROR: El rol no está configurado en el sistema.';
                    break;
            }

    }else {
        echo '❌ ERROR: Credenciales incorrectas o el rol no coincide. Vuelva a intentarlo.';
    }

} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>
