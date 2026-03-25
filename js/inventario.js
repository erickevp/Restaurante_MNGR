// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

$(document).ready(function () {
    cargarInventario();

    $('#inventarioForm').on('submit', function (e) {
        e.preventDefault();

        let id = $('#inventario_id').val();
        let url = id ? '../api/inventario/update.php' : '../api/inventario/create.php';

        let data = {
            id: id,
            articulo: $('#articulo').val(),
            cantidad: $('#cantidad').val(),
            unidad: $('#unidad').val(),
            precio_unitario: $('#precio_unitario').val()
        };

        let btn = $('#btnGuardar');
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>...');

        App.api(url, data)
            .done(function (res) {
                if (res.success) {
                    $('#inventarioModal').modal('hide');
                    App.notify('success', res.message);
                    cargarInventario();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            })
            .always(function () {
                btn.prop('disabled', false).html('Guardar');
            });
    });
});

function cargarInventario() {
    App.api('../api/inventario/read.php', {}, 'GET')
        .done(function (res) {
            if (res.success) {
                let html = '';
                res.data.forEach(function (i) {
                    let badgeColor = i.cantidad <= 5 ? 'bg-danger' : (i.cantidad <= 15 ? 'bg-warning text-dark' : 'bg-success');
                    let valorTotal = (parseFloat(i.cantidad) * parseFloat(i.precio_unitario)).toFixed(2);

                    html += `
                    <tr>
                        <td class="ps-4 fw-medium text-dark">${i.articulo}</td>
                        <td>
                            <span class="badge ${badgeColor}">${parseFloat(i.cantidad).toFixed(2)} ${i.unidad}</span>
                        </td>
                        <td class="text-muted">$${parseFloat(i.precio_unitario).toFixed(2)}</td>
                        <td class="fw-bold">$${valorTotal}</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light text-primary me-1" onclick="editarInventario(${i.id}, '${i.articulo.replace(/'/g, "\\'")}', ${i.cantidad}, '${i.unidad}', ${i.precio_unitario})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            ${App.usuario_rol === 'admin' ? `
                            <button class="btn btn-sm btn-light text-danger" onclick="eliminarInventario(${i.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>` : ''}
                        </td>
                    </tr>`;
                });
                $('#inventarioTableBody').html(html);
            }
        });
}

function resetForm() {
    $('#inventarioForm')[0].reset();
    $('#inventario_id').val('');
    $('#modalTitle').html('<i class="fa-solid fa-box-open me-2 text-primary"></i>Nuevo Artículo');
}

function editarInventario(id, articulo, cantidad, unidad, precio) {
    resetForm();
    $('#inventario_id').val(id);
    $('#articulo').val(articulo);
    $('#cantidad').val(cantidad);
    $('#unidad').val(unidad);
    $('#precio_unitario').val(precio);
    $('#modalTitle').html('<i class="fa-solid fa-pen me-2 text-primary"></i>Editar Artículo');
    $('#inventarioModal').modal('show');
}

function eliminarInventario(id) {
    App.confirm('¿Eliminar Artículo?', 'Esta acción no se puede deshacer.', function () {
        App.api('../api/inventario/delete.php', { id: id })
            .done(function (res) {
                if (res.success) {
                    App.notify('success', 'Artículo eliminado de inventario');
                    cargarInventario();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
    });
}
