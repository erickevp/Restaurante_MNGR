// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

$(document).ready(function () {
    cargarSelects();
    cargarReservaciones();

    $('#reservacionForm').on('submit', function (e) {
        e.preventDefault();

        let id = $('#reservacion_id').val();
        let url = id ? '../api/reservaciones/update.php' : '../api/reservaciones/create.php';

        let data = {
            id: id,
            id_cliente: $('#id_cliente').val(),
            id_mesa: $('#id_mesa').val(),
            fecha: $('#fecha').val(),
            hora: $('#hora').val(),
            estado: $('#estado').val()
        };

        let btn = $('#btnGuardar');
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>...');

        App.api(url, data)
            .done(function (res) {
                if (res.success) {
                    $('#reservacionModal').modal('hide');
                    App.notify('success', res.message);
                    cargarReservaciones();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            })
            .always(function () {
                btn.prop('disabled', false).html('Guardar');
            });
    });
});

function cargarSelects() {
    App.api('../api/clientes/read.php', {}, 'GET').done(function (res) {
        if (res.success) {
            let options = '<option value="">Seleccione un cliente...</option>';
            res.data.forEach(c => options += `<option value="${c.id}">${c.nombre} (${c.telefono || 'Sin tel'})</option>`);
            $('#id_cliente').html(options);
        }
    });

    App.api('../api/mesas/read.php', {}, 'GET').done(function (res) {
        if (res.success) {
            let options = '<option value="">Seleccione una mesa...</option>';
            res.data.forEach(m => options += `<option value="${m.id}">Mesa ${m.numero} (Cap: ${m.capacidad} pax)</option>`);
            $('#id_mesa').html(options);
        }
    });
}

function cargarReservaciones() {
    App.api('../api/reservaciones/read.php', {}, 'GET')
        .done(function (res) {
            if (res.success) {
                let html = '';
                res.data.forEach(function (r) {
                    let badgeColor = 'bg-secondary';
                    if (r.estado == 'pendiente') badgeColor = 'bg-warning text-dark';
                    if (r.estado == 'confirmada') badgeColor = 'bg-primary';
                    if (r.estado == 'completada') badgeColor = 'bg-success';
                    if (r.estado == 'cancelada') badgeColor = 'bg-danger';

                    // Formatear fecha y hora
                    let dateParts = r.fecha.split('-');
                    let formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
                    let timeParts = r.hora.split(':');
                    let formattedTime = `${timeParts[0]}:${timeParts[1]}`;

                    html += `
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark"><i class="fa-regular fa-calendar me-1"></i>${formattedDate}</div>
                            <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>${formattedTime}</small>
                        </td>
                        <td class="fw-medium">${r.cliente_nombre}</td>
                        <td class="text-muted"><i class="fa-solid fa-chair me-1"></i>Mesa ${r.mesa_numero} <span class="ms-1 small">(${r.capacidad} pax)</span></td>
                        <td><span class="badge ${badgeColor}">${r.estado.toUpperCase()}</span></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light text-primary me-1" onclick="editarReservacion(${r.id}, ${r.id_cliente}, ${r.id_mesa}, '${r.fecha}', '${r.hora}', '${r.estado}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-sm btn-light text-danger" onclick="eliminarReservacion(${r.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
                $('#reservacionesTableBody').html(html);
            }
        });
}

function resetForm() {
    $('#reservacionForm')[0].reset();
    $('#reservacion_id').val('');
    $('#modalTitle').html('<i class="fa-solid fa-calendar-plus me-2 text-primary"></i>Nueva Reservación');

    // Set fecha de hoy por default
    let today = new Date().toISOString().split('T')[0];
    $('#fecha').val(today);
}

function editarReservacion(id, id_cliente, id_mesa, fecha, hora, estado) {
    resetForm();
    $('#reservacion_id').val(id);
    $('#id_cliente').val(id_cliente);
    $('#id_mesa').val(id_mesa);
    $('#fecha').val(fecha);
    $('#hora').val(hora);
    $('#estado').val(estado);
    $('#modalTitle').html('<i class="fa-solid fa-pen me-2 text-primary"></i>Editar Reservación');
    $('#reservacionModal').modal('show');
}

function eliminarReservacion(id) {
    App.confirm('¿Cancelar/Eliminar Reservación?', 'El registro será eliminado.', function () {
        App.api('../api/reservaciones/delete.php', { id: id })
            .done(function (res) {
                if (res.success) {
                    App.notify('success', 'Reservación eliminada');
                    cargarReservaciones();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
    });
}
