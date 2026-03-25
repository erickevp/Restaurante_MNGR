// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

$(document).ready(function () {
    cargarClientes();

    $('#clienteForm').on('submit', function (e) {
        e.preventDefault();

        let id = $('#cliente_id').val();
        let url = id ? '../api/clientes/update.php' : '../api/clientes/create.php';

        let data = {
            id: id,
            nombre: $('#nombre').val(),
            telefono: $('#telefono').val(),
            email: $('#email').val()
        };

        let btn = $('#btnGuardar');
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Guardando...');

        App.api(url, data)
            .done(function (res) {
                if (res.success) {
                    $('#clienteModal').modal('hide');
                    App.notify('success', res.message);
                    cargarClientes();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            })
            .always(function () {
                btn.prop('disabled', false).html('Guardar Cliente');
            });
    });
});

function cargarClientes() {
    App.api('../api/clientes/read.php', {}, 'GET')
        .done(function (res) {
            if (res.success) {
                let html = '';
                res.data.forEach(function (c) {
                    html += `
                    <tr>
                        <td class="ps-4 fw-medium text-dark">${c.nombre}</td>
                        <td class="text-muted"><i class="fa-solid fa-phone me-1 small"></i>${c.telefono || 'N/A'}</td>
                        <td class="text-muted"><i class="fa-regular fa-envelope me-1 small"></i>${c.email || 'N/A'}</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light text-primary me-1" onclick="editarCliente(${c.id}, '${c.nombre.replace(/'/g, "\\'")}', '${c.telefono}', '${c.email}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            ${App.usuario_rol === 'admin' ? `
                            <button class="btn btn-sm btn-light text-danger" onclick="eliminarCliente(${c.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>` : ''}
                        </td>
                    </tr>`;
                });
                $('#clientesTableBody').html(html);
            }
        });
}

function resetForm() {
    $('#clienteForm')[0].reset();
    $('#cliente_id').val('');
    $('#modalTitle').html('<i class="fa-solid fa-user-plus me-2 text-primary"></i>Nuevo Cliente');
}

function editarCliente(id, nombre, tel, email) {
    resetForm();
    $('#cliente_id').val(id);
    $('#nombre').val(nombre);
    $('#telefono').val(tel);
    $('#email').val(email);
    $('#modalTitle').html('<i class="fa-solid fa-user-pen me-2 text-primary"></i>Editar Cliente');
    $('#clienteModal').modal('show');
}

function eliminarCliente(id) {
    App.confirm('¿Eliminar Cliente?', 'Esta acción no se puede deshacer.', function () {
        App.api('../api/clientes/delete.php', { id: id })
            .done(function (res) {
                if (res.success) {
                    App.notify('success', 'Cliente eliminado exitosamente');
                    cargarClientes();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
    });
}
