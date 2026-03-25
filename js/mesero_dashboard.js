// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

$(document).ready(function () {
    cargarMesas();

    // Polling every 15 seconds to keep tables updated
    setInterval(cargarMesas, 15000);
});

function cargarMesas() {
    App.api('../api/mesas/read.php', {}, 'GET').done(function (res) {
        if (res.success) {
            let html = '';
            res.data.forEach(m => {
                let statusClass = m.estado === 'disponible' ? 'mesa-disponible' : (m.estado === 'ocupada' ? 'mesa-ocupada' : 'bg-secondary text-white');
                let icon = m.estado === 'disponible' ? 'fa-chair' : 'fa-bell-concierge';
                let stateTxt = m.estado === 'disponible' ? 'Libre' : 'Ocupada';

                html += `
                <div class="mesa-item shadow-sm ${statusClass}" onclick="abrirMesa(${m.id}, '${m.numero}', '${m.estado}')">
                    <i class="fa-solid ${icon} fa-2x mb-2 opacity-75"></i>
                    <span class="fs-4">Mesa ${m.numero}</span>
                    <small class="fw-normal opacity-75">${stateTxt} - ${m.capacidad} pax</small>
                </div>
                `;
            });
            $('#mesasGrid').html(html);
        }
    });
}

function abrirMesa(id_mesa, numero, estado) {
    // Both available and occupied tables can be opened.
    // Available: creates order. Occupied: opens active order.
    window.location.href = `pedido.php?mesa_id=${id_mesa}&numero=${numero}`;
}

function logout() {
    App.confirm('¿Cerrar Sesión?', 'Saldrás de tu cuenta en este dispositivo.', function () {
        App.api('../api/auth/logout.php', {}, 'GET').done(function () {
            window.location.href = 'index.php';
        });
    });
}
