// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

$(document).ready(function () {
    cargarMesas();

    $('#mesaForm').on('submit', function (e) {
        e.preventDefault();

        let id = $('#mesa_id').val();
        let url = id ? '../api/mesas/update.php' : '../api/mesas/create.php';

        let data = {
            id: id,
            numero: $('#numero').val(),
            capacidad: $('#capacidad').val(),
            estado: $('#estado').val()
        };

        let btn = $('#btnGuardar');
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Guardando...');

        App.api(url, data)
            .done(function (res) {
                if (res.success) {
                    $('#mesaModal').modal('hide');
                    App.notify('success', res.message);
                    cargarMesas();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            })
            .fail(function () {
                Swal.fire('Error', 'Error de conexión con el servidor', 'error');
            })
            .always(function () {
                btn.prop('disabled', false).html('Guardar Mesa');
            });
    });
});

function cargarMesas() {
    App.api('../api/mesas/read.php', {}, 'GET')
        .done(function (res) {
            if (res.success) {
                let html = '';
                res.data.forEach(function (m) {
                    let badgeColor = 'bg-secondary';
                    if (m.estado === 'disponible') badgeColor = 'bg-success';
                    if (m.estado === 'ocupada') badgeColor = 'bg-danger';
                    if (m.estado === 'reservada') badgeColor = 'bg-warning text-dark';
                    if (m.estado === 'mantenimiento') badgeColor = 'bg-secondary';

                    html += `
                    <tr>
                        <td class="ps-4 fw-bold text-primary">Mesa ${m.numero}</td>
                        <td class="text-muted"><i class="fa-solid fa-users me-1 small"></i>${m.capacidad}</td>
                        <td><span class="badge ${badgeColor}">${m.estado.toUpperCase()}</span></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light text-primary me-1" onclick="editarMesa(${m.id}, ${m.numero}, ${m.capacidad}, '${m.estado}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-sm btn-light text-danger" onclick="eliminarMesa(${m.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
                $('#mesasTableBody').html(html);
            }
        });
}

function resetForm() {
    $('#mesaForm')[0].reset();
    $('#mesa_id').val('');
    $('#modalTitle').html('<i class="fa-solid fa-chair me-2 text-primary"></i>Nueva Mesa');
}

function editarMesa(id, numero, capacidad, estado) {
    resetForm();
    $('#mesa_id').val(id);
    $('#numero').val(numero);
    $('#capacidad').val(capacidad);
    $('#estado').val(estado);
    $('#modalTitle').html('<i class="fa-solid fa-pen me-2 text-primary"></i>Editar Mesa');
    $('#mesaModal').modal('show');
}

function eliminarMesa(id) {
    App.confirm('¿Eliminar Mesa?', 'Esta acción no se puede deshacer y puede fallar si la mesa tiene pedidos asociados.', function () {
        App.api('../api/mesas/delete.php', { id: id })
            .done(function (res) {
                if (res.success) {
                    App.notify('success', 'Mesa eliminada exitosamente');
                    cargarMesas();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
    });
}
