// JavaScript del panel de domiciliario — Fruver Panorama

// Confirmación antes de marcar un pedido como "Entregado"
function confirmarEntrega() {
    return confirm("¿Confirmas que este pedido fue entregado al cliente?");
}

// Confirmación antes de marcar un pedido como "En camino"
function confirmarEnCamino() {
    return confirm("¿Confirmas que vas a iniciar el trayecto de este pedido?");
}

// Funciones del modal de detalle del pedido
function cerrarModal(event) {
    if (event.target.id === 'modal-overlay') {
        document.getElementById('modal-overlay').classList.remove('activo');
    }
}

function cerrarModalDirecto() {
    document.getElementById('modal-overlay').classList.remove('activo');
}
