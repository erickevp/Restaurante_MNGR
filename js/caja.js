// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

let cajaActivaInfo = null;

$(document).ready(function () {
    cargarHistorialCaja();

    $('#abrirCajaForm').on('submit', function (e) {
        e.preventDefault();
        App.api('../api/caja/abrir.php', { fondo: $('#fondo_inicial').val() })
            .done(function (res) {
                if (res.success) {
                    $('#abrirModal').modal('hide');
                    App.notify('success', 'Turno de caja abierto');
                    cargarHistorialCaja();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
    });

    $('#egresoCajaForm').on('submit', function (e) {
        e.preventDefault();
        if (!cajaActivaInfo) return;

        App.api('../api/caja/egreso.php', { id: cajaActivaInfo.id, monto: $('#monto_egreso').val() })
            .done(function (res) {
                if (res.success) {
                    $('#egresoModal').modal('hide');
                    $('#monto_egreso').val('');
                    App.notify('success', 'Retiro registrado');
                    cargarHistorialCaja();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
    });
});

function cargarHistorialCaja() {
    App.api('../api/caja/read.php', {}, 'GET').done(function (res) {
        if (res.success) {
            let html = '';
            cajaActivaInfo = null;

            res.data.forEach(c => {
                if (c.abierto == 1 && c.id_usuario == App.usuario_id) {
                    cajaActivaInfo = c;
                }

                let badge = c.abierto == 1 ? '<span class="badge bg-success">ABIERTA</span>' : '<span class="badge bg-secondary">CERRADA</span>';
                let cierreStr = c.fecha_cierre ? c.fecha_cierre : '<span class="text-muted">--</span>';

                html += `
                <tr>
                    <td class="ps-4 fw-medium text-dark"><i class="fa-solid fa-user-circle me-2 text-muted"></i>${c.usuario_nombre}</td>
                    <td><small>${c.fecha_apertura}</small></td>
                    <td><small>${cierreStr}</small></td>
                    <td class="text-success fw-bold">$${parseFloat(c.ingresos).toFixed(2)}</td>
                    <td class="text-danger">$${parseFloat(c.egresos).toFixed(2)}</td>
                    <td class="fw-bold">$${parseFloat(c.total_calculado).toFixed(2)}</td>
                    <td class="text-end pe-4">${badge}</td>
                </tr>`;
            });
            $('#historialCajaBody').html(html || '<tr><td colspan="7" class="text-center py-4">No hay historial de caja</td></tr>');

            actualizarVistaCaja();
        }
    });
}

function actualizarVistaCaja() {
    if (cajaActivaInfo) {
        $('#btnAbrirCaja').addClass('d-none');
        $('#btnCerrarCaja').removeClass('d-none');
        $('#btnRegistrarEgreso').removeClass('d-none');

        $('#cardFondo').text('$' + parseFloat(cajaActivaInfo.fondo_inicial).toFixed(2));
        $('#cardIngresos').text('$' + parseFloat(cajaActivaInfo.ingresos).toFixed(2));
        $('#cardEgresos').text('$' + parseFloat(cajaActivaInfo.egresos).toFixed(2));
        $('#cardTotal').text('$' + parseFloat(cajaActivaInfo.total_calculado).toFixed(2));
    } else {
        $('#btnAbrirCaja').removeClass('d-none');
        $('#btnCerrarCaja').addClass('d-none');
        $('#btnRegistrarEgreso').addClass('d-none');

        $('#cardFondo').text('$0.00');
        $('#cardIngresos').text('$0.00');
        $('#cardEgresos').text('$0.00');
        $('#cardTotal').text('$0.00');
    }
}

function abrirCajaModal() {
    $('#fondo_inicial').val('0.00');
    $('#abrirModal').modal('show');
}

function egresoModal() {
    $('#monto_egreso').val('');
    $('#egresoModal').modal('show');
}

function cerrarCajaModal() {
    if (!cajaActivaInfo) return;

    Swal.fire({
        title: 'Corte de Caja',
        text: `El total esperado en caja es de $${parseFloat(cajaActivaInfo.total_calculado).toFixed(2)}. ¿Deseas cerrar el turno?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, cerrar caja'
    }).then((result) => {
        if (result.isConfirmed) {
            App.api('../api/caja/cerrar.php', { id: cajaActivaInfo.id })
                .done(function (res) {
                    if (res.success) {
                        Swal.fire('Corte Realizado', 'El turno de caja se ha cerrado.', 'success');
                        cargarHistorialCaja();
                        App.updateDashboardStats(); // update main counter if available
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
        }
    });
}
