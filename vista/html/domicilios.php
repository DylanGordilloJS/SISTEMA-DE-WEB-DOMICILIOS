
<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Validar sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html"); 
    exit();
}

// 2. Obtener ID del usuario actual
$id_actual = $_SESSION['id_usuario'];   


try {
    //para que pueda hacer for en base de datos buscado el dato
    require_once "../../modelo/domicilos_datos.php";
    require_once "../../modelo/consulta_nombre_domciliario.php";
    //el total de todos de todos los pedido igual (estado
    require_once "../../modelo/consulta_contar_estado_todo.php";
    // el valor total de entragaos de precio
    require_once "../../modelo/consulta_calcular_total_precio_entregado.php";
    
    $Usuarios = [];
    $pedidos = [];
    // comprobar si exite la varible  pedidos y si es verderdad que recorra toda la fila la base de datos 
    if (isset($Pedidos) && $Pedidos->rowCount() > 0) {
        $Pedidos = $Pedidos->fetchAll(PDO::FETCH_ASSOC);
    }
    // recorra  en la base de datos buscado nombre
    if (isset($Nombre) && $Nombre->rowCount() > 0) {    
        $Usuarios = $Nombre->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Panel Domiciliario - Fruver Panorama</title>
    <link rel="stylesheet" href="../css/domicilios.css">
    <link rel="icon" type="image/png" href="../asset/logo_Fruver_Panorama.png">
</head>
<body>

    <!-- HEADER -->
    <header class="encabezado">
        <div class="encabezado-izq">
            <img src="../asset/logo_Fruver_Panorama.png" alt="Logo" onerror="this.style.display='none'">
            <h1>Fruver Panorama</h1>
        </div>
        <div class="encabezado-der">
            <span class="chip-rol">🚴 Domiciliario</span>
            <a href="../../controlador/cerrar_sesion.php" class="btn-salir">Cerrar sesión</a>
            <a href="../html/configuraciones.php" class="btn-salir">configuración </a>

            
        </div>
    </header>


    <!-- BIENVENIDA + ESTADO DE TURNO -->
    <div class="bienvenida">
        <div class="bienvenida-texto">
            <?php foreach ($Usuarios as $u): ?>
            <h2>👋 Bienvenido, <?php echo htmlspecialchars($u['nombre_1'].' '.$u['apellido_1']);?></h2>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ESTADÍSTICAS -->
    <div class="stats-bar">
        <div class="stat-card">
            <div class="stat-num naranja" id="stat-pendientes"><?= $totalPendientes ?></div>
            <div class="stat-label">Pedidos pendientes</div>
        </div>
        <div class="stat-card">
            <div class="stat-num azul" id="stat-camino"><?= $totalEnCamino ?></div>
            <div class="stat-label">En camino ahora</div>
        </div>
        <div class="stat-card">
            <div class="stat-num verde" id="stat-entregados"><?= $totalEntregados ?></div>
            <div class="stat-label">Entregados hoy</div>
        </div>
        <div class="stat-card">
            <div class="stat-num"><?= number_format($total_dinero , 0, '', '.'); ?></div>
            <div class="stat-label">Recaudado hoy</div>
        </div>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="contenido-principal">

        <!-- TABLA DE PEDIDOS ASIGNADOS -->
        <div>
            <div class="seccion-titulo">
                📋 Pedidos asignados
                
            </div>

            <div class="tabla-pedidos">
                <table>
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Cliente</th>
                            <th>Dirección de entrega</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
<?php foreach ($Pedidos as $Pedido): ?>

    <?php 
    // Obtenemos el estado actual del pedido en minúsculas
    $estadoActual = strtolower(trim($Pedido['estado'] ?? ''));

    // Si ya está "en camino", "entregado" o "cancelado", ocultamos el botón "En camino"
    $ocultarEnCamino = ($estadoActual === 'en camino' || $estadoActual === 'en_camino' || $estadoActual === 'entregado' || $estadoActual === 'cancelado');

    // Si ya está "entregado" o "cancelado", ocultamos el botón "Entregar"
    $ocultarEntregar = ($estadoActual === 'entregado' || $estadoActual === 'cancelado');
    ?>

    <tr id="fila-<?= $Pedido['id_pedido'] ?>">
        <td><strong><?php echo htmlspecialchars($Pedido['id_pedido']); ?></strong></td>
        <td><?php echo htmlspecialchars($Pedido['nombre_1'] . ' ' . $Pedido['apellido_1']); ?></td>
        <td>
            <div class="dir-principal"><?php echo $Pedido['direccion_entrega']; ?></div>  
        </td>
        <td><strong><?php echo number_format($Pedido['total'], 0, '', '.'); ?></strong></td>
        <td><span class="estado-badge en-camino"><?php echo $Pedido['estado'] ?? ""; ?></span></td>
        <td>
            <div class="btns-accion">
                <form method="post">
                    <input type="hidden" name="id_pedido" value="<?= $Pedido["id_pedido"] ?>">

                    <button type="submit" 
                            onclick="if(!confirmarEntrega()){return false;} this.style.display='none';" 
                            class="btn-accion btn-entregar <?= $ocultarEntregar ? 'ocultar-boton' : '' ?>" 
                            formaction="../../modelo/consulta_entraga_domicilios.php">
                        ✔ Entregar
                    </button>

                    <button type="submit" 
                            onclick="if(!confirmarEnCamino()){return false;} this.style.display='none';" 
                            class="btn-accion btn-entregar <?= $ocultarEnCamino ? 'ocultar-boton' : '' ?>" 
                            formaction="../../modelo/consulta_en_camino_domiciliario.php">
                        🚶En camino
                    </button>
                </form>
            </div>
        </td>
    </tr>
        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <!-- MODAL DETALLE -->
    <div class="modal-overlay" id="modal-overlay" onclick="cerrarModal(event)">
        <div class="modal-contenido">
            <div class="modal-header">
                <h3 id="modal-titulo">Detalle del pedido</h3>
                <button class="btn-cerrar-modal" onclick="cerrarModalDirecto()">✕</button>
            </div>
            <div id="modal-cuerpo"></div>
        </div>
    </div>

    <script src="../js/domicilios.js"></script>
</body>
</html>