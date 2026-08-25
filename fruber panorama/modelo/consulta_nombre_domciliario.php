    <?php 
    require_once('../../modelo/conexion.php'); 
    $Nombre = $conn->prepare("SELECT nombre_1 ,apellido_1 FROM  Usuario WHERE id_usuario  = :id ");
    $Nombre->bindParam(':id', $id_actual);
    $Nombre->execute();
    ?>