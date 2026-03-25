// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

$(document).ready(function () {
    cargarStats();
    cargarMesas();

    // Actualizar datos cada 30 segundos
    setInterval(function () {
        cargarStats();
        cargarMesas();
    }, 30000);

    function cargarStats() {
        App.api('../api/dashboard/stats.php', {}, 'GET')
            .done(function (res) {
                if (res.success) {
                    $('#stat-pedidos').text(res.data.pedidos_activos);
                    $('#stat-mesas').text(res.data.mesas_disponibles);
                    $('#stat-reservaciones').text(res.data.reservaciones_hoy);
                    $('#stat-ingresos').text('$' + parseFloat(res.data.ingresos_hoy).toFixed(2));
                }
            });
    }

    function cargarMesas() {
        App.api('../api/dashboard/stats.php?action=mesas', {}, 'GET')
            .done(function (res) {
                if (res.success) {
                    let html = '';
                    res.data.forEach(function (mesa) {
                        let clase = 'mesa-disponible';
                        let strEstado = 'Disponible';

                        if (mesa.estado === 'ocupada') { clase = 'mesa-ocupada'; strEstado = 'Ocupada'; }
                        else if (mesa.estado === 'reservada') { clase = 'mesa-reservada'; strEstado = 'Reservada'; }
                        else if (mesa.estado === 'mantenimiento') { clase = 'mesa-mantenimiento'; strEstado = 'Inactiva'; }

                        html += `
                        <div class="col-4 col-md-3">
                            <div class="mesa-btn ${clase} card shadow-sm text-center" onclick="window.location.href='pedidos.php?mesa=${mesa.id}'">
                                <h3 class="fw-bold mb-1">${mesa.numero}</h3>
                                <small class="fw-medium">${strEstado}</small>
                                <small class="opacity-75" style="font-size: 0.7em"><i class="fa-solid fa-users"></i> ${mesa.capacidad}</small>
                            </div>
                        </div>`;
                    });
                    $('#mesas-grid').html(html);
                }
            });
    }
});
