// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

$(document).ready(function () {
    cargarUsuarios();

    $('#usuarioForm').on('submit', function (e) {
        e.preventDefault();

        let id = $('#usuario_id').val();
        let url = id ? '../api/usuarios/update.php' : '../api/usuarios/create.php';

        let data = {
            id: id,
            nombre: $('#nombre').val(),
            usuario: $('#usuario_text').val(),
            password: $('#password').val(),
            rol: $('#rol').val()
        };

        let btn = $('#btnGuardar');
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Guardando...');

        App.api(url, data)
            .done(function (res) {
                if (res.success) {
                    $('#usuarioModal').modal('hide');
                    App.notify('success', res.message);
                    cargarUsuarios();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            })
            .fail(function () {
                Swal.fire('Error', 'Error de conexión con el servidor', 'error');
            })
            .always(function () {
                btn.prop('disabled', false).html('Guardar Usuario');
            });
    });
});

function cargarUsuarios() {
    App.api('../api/usuarios/read.php', {}, 'GET')
        .done(function (res) {
            if (res.success) {
                let html = '';
                res.data.forEach(function (u) {
                    let badgeColor = 'bg-secondary';
                    if (u.rol === 'admin') badgeColor = 'bg-danger';
                    if (u.rol === 'gerente') badgeColor = 'bg-warning text-dark';
                    if (u.rol === 'cajero') badgeColor = 'bg-success';
                    if (u.rol === 'mesero') badgeColor = 'bg-info text-dark';

                    html += `
                    <tr>
                        <td class="ps-4 fw-medium">${u.nombre}</td>
                        <td class="text-muted"><i class="fa-regular fa-user me-1 small"></i>${u.usuario}</td>
                        <td><span class="badge ${badgeColor}">${u.rol.toUpperCase()}</span></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light text-primary me-1" onclick="editarUsuario(${u.id}, '${u.nombre}', '${u.usuario}', '${u.rol}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-sm btn-light text-danger" onclick="eliminarUsuario(${u.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
                $('#usuariosTableBody').html(html);
            }
        });
}

function resetForm() {
    $('#usuarioForm')[0].reset();
    $('#usuario_id').val('');
    $('#modalTitle').html('<i class="fa-solid fa-user-plus me-2 text-primary"></i>Nuevo Usuario');
    $('#password').prop('required', true);
    $('#pwdHelp').hide();
}

function editarUsuario(id, nombre, usuario, rol) {
    resetForm();
    $('#usuario_id').val(id);
    $('#nombre').val(nombre);
    $('#usuario_text').val(usuario);
    $('#rol').val(rol);
    $('#modalTitle').html('<i class="fa-solid fa-user-pen me-2 text-primary"></i>Editar Usuario');
    $('#password').prop('required', false);
    $('#pwdHelp').show();
    $('#usuarioModal').modal('show');
}

function eliminarUsuario(id) {
    App.confirm('¿Eliminar Usuario?', 'Esta acción no se puede deshacer', function () {
        App.api('../api/usuarios/delete.php', { id: id })
            .done(function (res) {
                if (res.success) {
                    App.notify('success', 'Usuario eliminado exitosamente');
                    cargarUsuarios();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
    });
}
