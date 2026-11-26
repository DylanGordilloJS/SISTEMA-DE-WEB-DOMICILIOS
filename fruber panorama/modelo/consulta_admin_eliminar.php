<?php
// modelo/consulta_eliminar.php

require_once __DIR__ . '/conexion.php';

// Detectar automáticamente la variable de conexión ($conexion o $conn)
$db = $conexion ?? $conn ?? null;

if (!$db) {
    die("Error crítico: No se pudo establecer la conexión a la base de datos.");
}

// Sentencia preparada para evitar inyecciones SQL
$sql = "DELETE FROM Usuario WHERE id_usuario = :id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $id_eliminar, PDO::PARAM_INT);
?>