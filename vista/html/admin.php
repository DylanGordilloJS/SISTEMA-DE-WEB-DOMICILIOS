<?php

// Traer todo lo que necesito de la base de datos
session_start();

// 1. Validar sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../vista/html/login.html");
    exit();
}

// 2. Conectar y ejecutar la consulta
$id_actual = $_SESSION['id_usuario'];

try {
    require_once "../../modelo/consulta_admin.php";
    require_once "../../modelo/consulta_productos_admin.php";
    require_once "../../modelo/consulta_Pedido_admin.php";

    $usuarios = [];
    $Productos = [];
    $Pedido = [];

    if (isset($consulta) && $consulta->rowCount() > 0) {
        $usuarios = $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
    if (isset($consulta_Producto) && $consulta_Producto->rowCount() > 0) {
        $Productos = $consulta_Producto->fetchAll(PDO::FETCH_ASSOC);
    }
    if (isset($consulta_Pedido) && $consulta_Pedido->rowCount() > 0) {
        $Pedido = $consulta_Pedido->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="icon" type="image/png" href="../asset/configuracion.png">
    <title>Fruber Admin</title>
    <style>
        /* Ocultar los checkboxes de control de modales */
        #modal-agregar-toggle,
        #modal-usuario-toggle,
        .modal-editar-toggle {
            display: none;
        }

        /* Estilos base del Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.75);
            justify-content: center;
            align-items: center;
        }

        #modal-agregar-toggle:checked ~ #modalAgregar {
            display: flex;
        }

        #modal-usuario-toggle:checked ~ #modalUsuario {
            display: flex;
        }

        .modal-editar-toggle:checked ~ .modal-editar-contenedor {
            display: flex;
        }

        .fondo-cerrar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .modal-contenido {
            background-color: #1c1c1c;
            border: 1px solid #2f2f2f;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 420px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.8);
            position: relative;
            z-index: 2001;
            display: flex;
            flex-direction: column;
        }

        .modal-contenido h2 {
            color: var(--secundario, #fff);
            font-size: 20px;
            margin-bottom: 20px;
            border-bottom: 2px solid #2f2f2f;
            padding-bottom: 8px;
            text-align: left;
        }

        .cerrar-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 26px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
            transition: color 0.2s;
        }

        .cerrar-modal:hover {
            color: #ff4d4d;
        }

        .grupo-form {
            margin-bottom: 18px;
            text-align: left;
            display: flex;
            flex-direction: column;
        }

        .grupo-form label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: var(--primario, #28a745);
            font-size: 14px;
        }

        .grupo-form input {
            width: 100%;
            padding: 11px;
            background: #2f2f2f;
            border: 1px solid #444;
            border-radius: 6px;
            color: #fff;
            font-size: 15px;
            outline: none;
            box-sizing: border-box;
        }

        .grupo-form select {
            width: 100%;
            padding: 11px;
            background: #2f2f2f;
            border: 1px solid #444;
            border-radius: 6px;
            color: #fff;
            font-size: 15px;
            outline: none;
            box-sizing: border-box;
        }

        .acciones-form {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 25px;
            gap: 10px;
        }

        .btn-guardar {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 18px;
            cursor: pointer;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
        }

        .btn-cancelar {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 18px;
            cursor: pointer;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .acciones-tabla {
            display: flex;
            gap: 6px;
            align-items: center;
        }
    </style>
</head>
<body>

    <?php $vistaInicial = (isset($_GET['vista']) && $_GET['vista'] === 'usuarios') ? 'usuarios' : 'productos'; ?>
    <input type="radio" name="cambiar-vista" id="vista-productos" <?php echo $vistaInicial === 'productos' ? 'checked' : ''; ?>>
    <input type="radio" name="cambiar-vista" id="vista-pedidos">
    <input type="radio" name="cambiar-vista" id="vista-usuarios" <?php echo $vistaInicial === 'usuarios' ? 'checked' : ''; ?>>
   
    <input type="checkbox" id="btn-menu">
    <input type="checkbox" id="modal-agregar-toggle">
    <input type="checkbox" id="modal-usuario-toggle">

    <div class="maquetacion">
        <header class="encabezado-header">
            <label for="btn-menu" class="ico_menu">☰</label>
            <div class="botones_header">
                <h2>👤 Bienvenido, Admin</h2>
            </div>
        </header>
    </div>

    <main class="centro">
        
        <!-- SECCIÓN PRODUCTOS -->
        <div class="seccion-admin" id="sec-productos">
            <div class="listado_fruber">
                <h1>📋 Productos</h1>
               
                <div class="acciones-inventario">
                    <div class="caja-buscar">
                        <span>🔍</span>
                        <input type="text" placeholder="Buscar producto...">
                    </div>
                    <label for="modal-agregar-toggle" class="btn-nuevo-producto" style="cursor: pointer; display: inline-flex; align-items: center;">+ Añadir Producto</label>
                </div>
               
                <table class="tabla-admin">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($Productos as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                            <td><?php echo htmlspecialchars($producto['precio']); ?></td>
                            <td>
                                <div class="acciones-tabla">
                                    <input type="checkbox" id="modal-editar-<?php echo $producto['id_producto']; ?>" class="modal-editar-toggle">
                                    <label for="modal-editar-<?php echo $producto['id_producto']; ?>" class="btn-accion btn-editar" title="Editar" style="cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">✏️</label>
                                    
                                    <div class="modal modal-editar-contenedor" id="modalEditar-<?php echo $producto['id_producto']; ?>">
                                        <label for="modal-editar-<?php echo $producto['id_producto']; ?>" class="fondo-cerrar"></label>
                                        <div class="modal-contenido">
                                            <label for="modal-editar-<?php echo $producto['id_producto']; ?>" class="cerrar-modal">&times;</label>
                                            <h2>Editar Producto</h2>
                                            <form action="../../controlador/editar_producto_admin.php" method="POST">
                                                <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                                                
                                                <div class="grupo-form">
                                                    <label>Nombre del Producto:</label>
                                                    <input type="text" name="nombre_producto" maxlength="50"  value="<?php echo htmlspecialchars($producto['nombre_producto'], ENT_QUOTES); ?>"  required>
                                                </div>
                                                
                                                <div class="grupo-form">
                                                    <label>Precio:</label>
                                                    <!-- Se limita la longitud de caracteres ingresados mediante JavaScript inline -->
                                                    <input type="number" step="0.01" min="0" max="99999" name="precio" value="<?php echo $producto['precio']; ?>" oninput="if(this.value.length > 8) this.value = this.value.slice(0, 8);" required>
                                                </div>
                                                
                                                <div class="acciones-form">
                                                    <button type="submit" class="btn-guardar">Actualizar Producto</button>
                                                    <label for="modal-editar-<?php echo $producto['id_producto']; ?>" class="btn-cancelar" style="cursor: pointer;">Cancelar</label>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <form action="../../controlador/eliminar_producto_admin.php" method="post" onsubmit="return confirm('¿Deseas eliminar este Producto?');" style="display:inline;">                    
                                        <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto['id_producto']); ?>">
                                        <button type="submit" class="btn-accion btn-eliminar" title="Eliminar">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECCIÓN PEDIDOS -->
        <div class="seccion-admin" id="sec-pedidos">
            <div class="listado_fruber">
                <h1>🛒 CONTROL DE PEDIDOS</h1>
                <table class="tabla-admin">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($Pedido)): ?>
                            <?php foreach ($Pedido as $pedido_item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pedido_item['id_pedido']); ?></td>
                                <td><?php echo htmlspecialchars($pedido_item['nombre_usuario'] ?? 'N/D'); ?></td>
                                <td><?php echo htmlspecialchars($pedido_item['total']); ?></td>
                                <td><?php echo htmlspecialchars($pedido_item['estado']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No se encontraron pedidos registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECCIÓN USUARIOS -->
        <div class="seccion-admin" id="sec-usuarios">
            <div class="listado_fruber">
                <h1>👥 GESTIÓN DE USUARIOS</h1>
                <div class="acciones-inventario">
                    <label for="modal-usuario-toggle" class="btn-nuevo-producto" style="cursor: pointer; display: inline-flex; align-items: center;">+ Añadir Usuario</label>
                </div>
                <table class="tabla-admin">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo Electrónico</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['nombre_1'] . " " . $usuario['apellido_1']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['id_RolFK']); ?></td>
                                    <td>
                                        <form action="../../controlador/eliminar_cuenta_admin.php" method="post" onsubmit="return confirm('¿Deseas eliminar este usuario?');">                    
                                            <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($usuario['id_usuario']); ?>">
                                            <button type="submit" class="btn-accion btn-eliminar" title="Eliminar">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No se encontraron usuarios registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- MODAL AÑADIR PRODUCTO -->
    <div class="modal" id="modalAgregar">
        <label for="modal-agregar-toggle" class="fondo-cerrar"></label>
        <div class="modal-contenido">
            <label for="modal-agregar-toggle" class="cerrar-modal">&times;</label>
            <h2>Añadir Nuevo Producto</h2>
            <form action="../../controlador/agregar_producto_admin.php" method="POST">
                
                <div class="grupo-form">
                    <label for="nombre_producto">Nombre del Producto:</label>
                    <input type="text" id="nombre_producto" name="nombre_producto" maxlength="50" required placeholder="Ej: Manzana">
                </div>
                
                <div class="grupo-form">
                    <label for="precio">Precio:</label>
                    <!-- Se limita la longitud de caracteres ingresados mediante JavaScript inline -->
                    <input type="number" step="0.01" min="0" max="99999" id="precio" name="precio" oninput="if(this.value.length > 8) this.value = this.value.slice(0, 8);" required placeholder="Ej: 2500.50">
                </div>
                
                <div class="acciones-form">
                    <button type="submit" class="btn-guardar">Guardar Producto</button>
                    <label for="modal-agregar-toggle" class="btn-cancelar" style="cursor: pointer;">Cancelar</label>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL AÑADIR USUARIO -->
    <div class="modal" id="modalUsuario">
        <label for="modal-usuario-toggle" class="fondo-cerrar"></label>
        <div class="modal-contenido" style="max-height: 90vh; overflow-y: auto;">
            <label for="modal-usuario-toggle" class="cerrar-modal">&times;</label>
            <h2>Añadir Nuevo Usuario</h2>
            <form action="../../controlador/agregar_usuario_admin.php" method="POST">

                <div class="grupo-form">
                    <label for="nu_nombre">Nombres:</label>
                    <input type="text" id="nu_nombre" name="nombre1" minlength="3" maxlength="30" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+" title="Solo se permiten letras y espacios" required>
                </div>
                <div class="grupo-form">
                    <label for="nu_apellido">Apellidos:</label>
                    <input type="text" id="nu_apellido" name="apellido" minlength="3" maxlength="39" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+" title="Solo se permiten letras y espacios" required>
                </div>
                <div class="grupo-form">
                    <label for="nu_tipo_doc">Tipo de documento:</label>
                    <select id="nu_tipo_doc" name="tipo_documento" required>
                        <option value="" disabled selected>Selecciona una opción</option>
                        <option value="CC">Cédula de Ciudadanía (CC)</option>
                        <option value="TI">Tarjeta de Identidad (TI)</option>
                        <option value="CE">Cédula de Extranjería (CE)</option>
                        <option value="PAS">Pasaporte</option>
                        <option value="PEP">Permiso Especial de Permanencia (PEP)</option>
                    </select>
                </div>
                <div class="grupo-form">
                    <label for="nu_documento">Número de documento:</label>
                    <input type="text" id="nu_documento" name="documento" minlength="8" maxlength="18" pattern="[0-9]+" title="Solo se permiten números" required>
                </div>
                <div class="grupo-form">
                    <label for="nu_email">Correo electrónico:</label>
                    <input type="email" id="nu_email" name="email" maxlength="80" required>
                </div>
                <div class="grupo-form">
                    <label for="nu_direccion">Dirección:</label>
                    <input type="text" id="nu_direccion" name="direccion" minlength="5" maxlength="100" required>
                </div>
                <div class="grupo-form">
                    <label for="nu_telefono">Teléfono:</label>
                    <input type="tel" id="nu_telefono" name="telefono" minlength="7" maxlength="15" pattern="[0-9]+" title="Solo se permiten números" required>
                </div>
                <div class="grupo-form">
                    <label for="nu_clave">Contraseña:</label>
                    <input type="password" id="nu_clave" name="contrasena1" minlength="8" maxlength="30" required>
                </div>
                <div class="grupo-form">
                    <label for="nu_clave2">Confirmar contraseña:</label>
                    <input type="password" id="nu_clave2" name="contrasena_confirmar" minlength="8" maxlength="30" required>
                </div>
                <div class="grupo-form">
                    <label for="nu_rol">Rol:</label>
                    <select id="nu_rol" name="rol" required>
                        <option value="2" selected>Cliente</option>
                        <option value="3">Domiciliario</option>
                        <option value="1">Administrador</option>
                    </select>
                </div>

                <div class="acciones-form">
                    <button type="submit" class="btn-guardar">Guardar Usuario</button>
                    <label for="modal-usuario-toggle" class="btn-cancelar" style="cursor: pointer;">Cancelar</label>
                </div>
            </form>
        </div>
    </div>

    <div class="contener-menu">
        <div class="cont-menu">
            <nav>
                <a href="../html/fruber.php">🏠 Inicio</a>
                <label for="vista-productos" class="opcion-menu">📦 Productos</label>
                <label for="vista-pedidos" class="opcion-menu">🛒 Pedidos</label>
                <label for="vista-usuarios" class="opcion-menu">👥 Usuarios</label>
                <a href="../html/configuraciones.php">⚙️ Configuración</a>
            </nav>
            <label for="btn-menu" class="ico_equis">x</label>
        </div>
    </div>

</body>
</html>