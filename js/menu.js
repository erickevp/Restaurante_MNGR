// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

$(document).ready(function () {
    cargarCategorias();
    cargarMenu();

    $('#menuForm').on('submit', function (e) {
        e.preventDefault();

        let id = $('#menu_id').val();
        let url = id ? '../api/menu/update.php' : '../api/menu/create.php';

        let data = {
            id: id,
            id_categoria: $('#id_categoria').val(),
            nombre: $('#nombre').val(),
            descripcion: $('#descripcion').val(),
            precio: $('#precio').val(),
            estado: $('#estado').val()
        };

        let btn = $('#btnGuardar');
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>...');

        App.api(url, data)
            .done(function (res) {
                if (res.success) {
                    $('#menuModal').modal('hide');
                    App.notify('success', res.message);
                    cargarMenu();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            })
            .fail(function () {
                Swal.fire('Error', 'Error de conexión', 'error');
            })
            .always(function () {
                btn.prop('disabled', false).html('Guardar');
            });
    });

    $('#categoriaForm').on('submit', function (e) {
        e.preventDefault();
        let nombre = $('#nombre_categoria').val();
        let btn = $('#btnGuardarCat');
        btn.prop('disabled', true);

        App.api('../api/categorias/create.php', { nombre: nombre })
            .done(function (res) {
                if (res.success) {
                    $('#categoriaModal').modal('hide');
                    $('#nombre_categoria').val('');
                    App.notify('success', 'Categoría creada');
                    cargarCategorias();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            })
            .always(function () {
                btn.prop('disabled', false);
            });
    });
});

function cargarCategorias() {
    App.api('../api/categorias/read.php', {}, 'GET')
        .done(function (res) {
            if (res.success) {
                let options = '<option value="">Seleccione una categoría...</option>';
                res.data.forEach(function (c) {
                    options += `<option value="${c.id}">${c.nombre}</option>`;
                });
                $('#id_categoria').html(options);
            }
        });
}

function cargarMenu() {
    App.api('../api/menu/read.php', {}, 'GET')
        .done(function (res) {
            if (res.success) {
                let html = '';
                res.data.forEach(function (m) {
                    let badgeColor = m.estado === 'activo' ? 'bg-success' : 'bg-danger';

                    html += `
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">${m.nombre}</div>
                            <small class="text-muted d-block text-truncate" style="max-width: 200px;">${m.descripcion}</small>
                        </td>
                        <td class="text-muted"><i class="fa-solid fa-tag me-1 small"></i>${m.categoria}</td>
                        <td class="fw-bold text-success">$${parseFloat(m.precio).toFixed(2)}</td>
                        <td><span class="badge ${badgeColor}">${m.estado.toUpperCase()}</span></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light text-primary me-1" onclick="editarMenu(${m.id}, ${m.id_categoria}, '${m.nombre.replace(/'/g, "\\'")}', '${m.descripcion.replace(/'/g, "\\'")}', ${m.precio}, '${m.estado}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-sm btn-light text-danger" onclick="eliminarMenu(${m.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
                $('#menuTableBody').html(html);
            }
        });
}

function resetForm() {
    $('#menuForm')[0].reset();
    $('#menu_id').val('');
    $('#modalTitle').html('<i class="fa-solid fa-burger me-2 text-primary"></i>Nuevo Elemento de Menú');
}

function editarMenu(id, id_categoria, nombre, desc, precio, estado) {
    resetForm();
    $('#menu_id').val(id);
    $('#id_categoria').val(id_categoria);
    $('#nombre').val(nombre);
    $('#descripcion').val(desc);
    $('#precio').val(precio);
    $('#estado').val(estado);
    $('#modalTitle').html('<i class="fa-solid fa-pen me-2 text-primary"></i>Editar Platillo');
    $('#menuModal').modal('show');
}

function eliminarMenu(id) {
    App.confirm('¿Eliminar Platillo?', 'Se ocultará o eliminará si no tiene pedidos históricos.', function () {
        App.api('../api/menu/delete.php', { id: id })
            .done(function (res) {
                if (res.success) {
                    App.notify('success', 'Eliminado correctamente');
                    cargarMenu();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
    });
}
