// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

$(document).ready(function () {
    cargarInventarioItems();
    cargarMermas();

    $('#mermaForm').on('submit', function (e) {
        e.preventDefault();

        let id = $('#merma_id').val();
        let url = id ? '../api/mermas/update.php' : '../api/mermas/create.php';

        let data = {
            id: id,
            id_inventario: $('#id_inventario').val(),
            cantidad: $('#cantidad').val(),
            motivo: $('#motivo').val()
        };

        let btn = $('#btnGuardar');
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>...');

        App.api(url, data)
            .done(function (res) {
                if (res.success) {
                    $('#mermaModal').modal('hide');
                    App.notify('success', res.message);
                    cargarMermas();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            })
            .always(function () {
                btn.prop('disabled', false).html('Registrar Merma');
            });
    });
});

function cargarInventarioItems() {
    App.api('../api/inventario/read.php', {}, 'GET').done(function (res) {
        if (res.success) {
            let options = '<option value="">Seleccione un artículo...</option>';
            res.data.forEach(i => options += `<option value="${i.id}">${i.articulo} (${i.unidad})</option>`);
            $('#id_inventario').html(options);
        }
    });
}

function cargarMermas() {
    App.api('../api/mermas/read.php', {}, 'GET')
        .done(function (res) {
            if (res.success) {
                let html = '';
                res.data.forEach(function (m) {
                    let dateParts = m.fecha.split(' ')[0].split('-');
                    let formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;

                    html += `
                    <tr>
                        <td class="ps-4 fw-medium text-dark"><i class="fa-regular fa-calendar-xmark me-2 text-danger"></i>${formattedDate}</td>
                        <td class="fw-bold text-primary">${m.articulo}</td>
                        <td><span class="badge bg-danger">${m.cantidad} ${m.unidad}</span></td>
                        <td class="text-muted small">${m.motivo}</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light text-primary me-1" onclick="editarMerma(${m.id}, ${m.id_inventario}, ${m.cantidad}, '${m.motivo.replace(/'/g, "\\'")}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-sm btn-light text-danger" onclick="eliminarMerma(${m.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
                $('#mermasTableBody').html(html);
            }
        });
}

function resetForm() {
    $('#mermaForm')[0].reset();
    $('#merma_id').val('');
    $('#modalTitle').html('<i class="fa-solid fa-trash-can-arrow-up me-2 text-primary"></i>Nueva Merma');
}

function editarMerma(id, id_inv, cantidad, motivo) {
    resetForm();
    $('#merma_id').val(id);
    $('#id_inventario').val(id_inv);
    $('#cantidad').val(cantidad);
    $('#motivo').val(motivo);
    $('#modalTitle').html('<i class="fa-solid fa-pen me-2 text-primary"></i>Editar Merma');
    $('#mermaModal').modal('show');
}

function eliminarMerma(id) {
    App.confirm('¿Eliminar registro de Merma?', 'Esta acción restaurará el inventario (lógicamente) de esta merma.', function () {
        App.api('../api/mermas/delete.php', { id: id })
            .done(function (res) {
                if (res.success) {
                    App.notify('success', 'Registro eliminado');
                    cargarMermas();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
    });
}
