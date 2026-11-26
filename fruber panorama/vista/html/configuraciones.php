<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Validar sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../vista/html/login.html"); 
    exit();
}

// 2. Obtener ID del usuario actual
$id_actual = $_SESSION['id_usuario'];

try {
    // 3. Consultar datos del usuario
    require_once "../../modelo/consulta_confi_datos.php";

    if (isset($consulta) && $consulta->rowCount() > 0) {
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
    } else {
        die("Error: No se encontraron datos para este usuario.");
    }
} catch (PDOException $e) {
    die("Error al cargar la configuración: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/confi.css">
    <link rel="icon" type="image/png" href="../asset/logo_Fruver_Panorama.png">
    <title>Configuraciones - Fruver Panorama</title>
</head>
<body>

    <div class="maquetacion">
        <!-- ENCABEZADO -->
        <header class="encabezado">
            <a href="../html/fruber.php">
                <img src="../asset/logo_Fruver_Panorama.png" alt="Logo Fruver Panorama" class="logo_header">
            </a> 
            <h2>Configuración</h2>
            <nav id="botones_enlazados">
                <ul>
                    <li><a href="../html/fruber.php">Inicio</a></li>
                    <li><a href="../../controlador/cerrar_sesion.php">Cerrar Sesion</a></li>
                </ul>
            </nav>
        </header>

        <!-- CONTENEDOR CENTRAL -->
        <main class="contenedor_centro">
            <div class="contenedor_configuracion">
                
                <!-- BLOQUE 1: EDITAR PERFIL -->
                <div class="caja_formulario">
                    <h3>Editar Perfil</h3>
                    <form action="../../controlador/configuracion.php" method="post">
                        <div class="grupo_input">
                            <label for="usuario">Nombre de usuario</label>
                            <input type="text" id="usuario" name="nombre_1" placeholder="Ej. Juan"  maxlength="30" value="<?php echo htmlspecialchars($usuario['nombre_1'] ?? ''); ?>" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+" title="Solo se permiten letras y espacios" required>
                        </div>
                        
                        <div class="grupo_input">
                            <label for="apellido">Apellido</label>
                            <input type="text" id="apellido" name="apellido_1" placeholder="Ej. Pérez" maxlength="50 " value="<?php echo htmlspecialchars($usuario['apellido_1'] ?? ''); ?>"  pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+" title="Solo se permiten letras y espacios" required>
                        </div>

                        <div class="grupo_input">
                            <label for="correo">Correo electrónico</label>
                            <input type="email" id="correo" name="email" placeholder="correo@ejemplo.com"  maxlength="80" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" required>
                        </div>

                        <div class="grupo_input">
                            <label for="tipo_documento">Tipo de documento</label>
                            <input type="text" id="tipo_documento" name="tipo_de_documento"  placeholder="CC/TI"   maxlength="4" value="<?php echo htmlspecialchars($usuario['tipo_de_documento'] ?? ''); ?>" required>
                        </div>

                        <div class="grupo_input">
                            <label for="documento">Número de documento</label>
                            <input type="number" id="documento" name="numero_documento" placeholder="102622132" value="<?php echo htmlspecialchars($usuario['numero_documento'] ?? ''); ?>" readonly>
                        </div>

                        <div class="grupo_input">
                            <label for="direccion">Dirección</label>
                            <input type="text" id="direccion" name="dirrecion" placeholder="Calle 10 #20-30" maxlength="35" value="<?php echo htmlspecialchars($usuario['dirrecion'] ?? ''); ?>">
                        </div>

                        <div class="grupo_input">
                            <label for="telefono">Número de teléfono</label>
                            <input type="number" id="telefono" name="telefono" placeholder="300 123 4567"  oninput="if(this.value.length > 11) this.value = this.value.slice(0, 11);" this.value = this.value.slice(0, 11); value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>">
                        </div>

                        <button type="submit" class="btn_guardar">Guardar Cambios</button>
                    </form>
                </div>

                <!-- BLOQUE 2: SEGURIDAD (CAMBIAR CONTRASEÑA) -->
                <div class="caja_formulario">
                    <h3>Seguridad</h3>
                    <form action="../../controlador/cambiar_contrasena.php" method="post">
                        <div class="grupo_input">
                            <label for="pass_actual">Contraseña actual</label>
                            <input type="password" id="pass_actual" name="Contra_actual" oninput="if(this.value.length > 32) this.value = this.value.slice(0, 32);" placeholder="••••••••">
                        </div>
                        
                        <div class="grupo_input">
                            <label for="pass_nueva">Nueva contraseña</label>
                            <input type="password" id="pass_nueva" name="nueva_contra"  oninput="if(this.value.length > 32) this.value = this.value.slice(0, 32);" placeholder="Mínimo 8 caracteres">
                        </div>

                        <div class="grupo_input">
                            <label for="pass_confirmar">Confirmar nueva contraseña</label>
                            <input type="password" id="pass_confirmar" name="confirmar_contra" oninput="if(this.value.length > 32) this.value = this.value.slice(0, 32);" placeholder="Repite la nueva contraseña">
                        </div>

                        <button type="submit" class="btn_guardar">Actualizar Contraseña</button>
                    </form>
                </div>

                <!-- BLOQUE 3: ZONA DE PELIGRO (ELIMINAR CUENTA) -->
                <div class="caja_formulario zona_peligro">
                    <h3>Eliminar Cuenta</h3>
                    <p class="advertencia_texto">
                        Si eliminas tu cuenta, se borrarán de forma permanente todos tus datos, el historial de tus pedidos y configuraciones dentro de Fruver Panorama. Esta acción es irreversible.
                    </p>
                    <form action="../../controlador/eliminar_cuenta.php" method="post" onsubmit="return confirm('¿Estás completamente seguro de que deseas eliminar tu cuenta permanentemente?');">
                        <button type="submit" class="btn_eliminar">Eliminar mi cuenta permanentemente</button>
                    </form>
                </div>

            </div>
        </main>
    </div>
</body>
</html>