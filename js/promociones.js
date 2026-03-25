// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

$(document).ready(function () {
    cargarPromociones();

    $('#promocionForm').on('submit', function (e) {
        e.preventDefault();

        let id = $('#promocion_id').val();
        let url = id ? '../api/promociones/update.php' : '../api/promociones/create.php';

        let data = {
            id: id,
            nombre: $('#nombre').val(),
            descripcion: $('#descripcion').val(),
            descuento: $('#descuento').val(),
            fecha_inicio: $('#fecha_inicio').val(),
            fecha_fin: $('#fecha_fin').val(),
            estado: $('#estado').val()
        };

        let btn = $('#btnGuardar');
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>...');

        App.api(url, data)
            .done(function (res) {
                if (res.success) {
                    $('#promocionModal').modal('hide');
                    App.notify('success', res.message);
                    cargarPromociones();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            })
            .always(function () {
                btn.prop('disabled', false).html('Guardar');
            });
    });
});

function cargarPromociones() {
    App.api('../api/promociones/read.php', {}, 'GET')
        .done(function (res) {
            if (res.success) {
                let html = '';
                let today = new Date().toISOString().split('T')[0];

                res.data.forEach(function (p) {
                    // Verificar vigencia real
                    let vigente = (today >= p.fecha_inicio && today <= p.fecha_fin && p.estado === 'activo');
                    let badgeColor = vigente ? 'bg-success' : 'bg-secondary';
                    let statusText = vigente ? 'ACTIVO' : (p.estado === 'inactivo' ? 'INACTIVO' : 'VENCIDO');

                    // Formatear fechas
                    let f_ini = p.fecha_inicio.split('-').reverse().join('/');
                    let f_fin = p.fecha_fin.split('-').reverse().join('/');

                    html += `
                    <tr class="${!vigente ? 'opacity-75' : ''}">
                        <td class="ps-4">
                            <div class="fw-bold text-dark">${p.nombre}</div>
                            <small class="text-muted d-block text-truncate" style="max-width: 250px;">${p.descripcion || 'Sin descripción'}</small>
                        </td>
                        <td>
                            <span class="badge bg-primary fs-6">${p.descuento}</span>
                        </td>
                        <td class="small">
                            <div><i class="fa-regular fa-calendar me-1"></i>${f_ini}</div>
                            <div class="text-muted"><i class="fa-regular fa-calendar-check me-1"></i>${f_fin}</div>
                        </td>
                        <td><span class="badge ${badgeColor}">${statusText}</span></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light text-primary me-1" onclick="editarPromocion(${p.id}, '${p.nombre.replace(/'/g, "\\'")}', '${(p.descripcion || '').replace(/'/g, "\\'")}', ${p.descuento}, '${p.fecha_inicio}', '${p.fecha_fin}', '${p.estado}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            ${App.usuario_rol === 'admin' ? `
                            <button class="btn btn-sm btn-light text-danger" onclick="eliminarPromocion(${p.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>` : ''}
                        </td>
                    </tr>`;
                });
                $('#promocionesTableBody').html(html);
            }
        });
}

function resetForm() {
    $('#promocionForm')[0].reset();
    $('#promocion_id').val('');
    $('#modalTitle').html('<i class="fa-solid fa-tag me-2 text-primary"></i>Nueva Promoción');

    let today = new Date().toISOString().split('T')[0];
    $('#fecha_inicio').val(today);
    $('#fecha_fin').val(today);
}

function editarPromocion(id, nombre, desc, descuento, f_ini, f_fin, estado) {
    resetForm();
    $('#promocion_id').val(id);
    $('#nombre').val(nombre);
    $('#descripcion').val(desc);
    $('#descuento').val(descuento);
    $('#fecha_inicio').val(f_ini);
    $('#fecha_fin').val(f_fin);
    $('#estado').val(estado);
    $('#modalTitle').html('<i class="fa-solid fa-pen me-2 text-primary"></i>Editar Promoción');
    $('#promocionModal').modal('show');
}

function eliminarPromocion(id) {
    App.confirm('¿Eliminar Promoción?', 'Se eliminará permanentemente.', function () {
        App.api('../api/promociones/delete.php', { id: id })
            .done(function (res) {
                if (res.success) {
                    App.notify('success', 'Promoción eliminada');
                    cargarPromociones();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
    });
}
