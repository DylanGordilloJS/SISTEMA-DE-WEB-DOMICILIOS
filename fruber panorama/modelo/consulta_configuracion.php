<?php 
require_once '../modelo/conexion.php';
require_once '../controlador/configuracion.php';
$sql = "UPDATE Usuario SET 
       nombre_1 = :nombre, 
       apellido_1 = :apellido, 
       email = :correo, 
        tipo_de_documento = :tipo_doc, 
        dirrecion = :direccion, 
        telefono = :telefono 
        WHERE id_usuario = :id";
?>