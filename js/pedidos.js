// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

let menuPlatillos = [];

$(document).ready(function () {
    cargarMenuData();
    cargarMesasDisponibles();
    cargarMesasActivas();

    $('#btnCrearPedido').on('click', function () {
        let mesa_id = $('#select_mesa').val();
        if (!mesa_id) {
            App.notify('warning', 'Seleccione una mesa primero');
            return;
        }

        App.api('../api/pedidos/create.php', { id_mesa: mesa_id })
            .done(function (res) {
                if (res.success) {
                    App.notify('success', res.message);
                    cargarMesasDisponibles();
                    cargarMesasActivas();
                    cargarDetallePedido(res.pedido_id, $('#select_mesa option:selected').text());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
    });

    $('#searchMenu').on('keyup', function () {
        let val = $(this).val().toLowerCase();
        $('.menu-item-card').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
        });
    });

    $('#pedidoEstado').on('change', function () {
        let pedido_id = $('#current_pedido_id').val();
        let estado = $(this).val();
        if (pedido_id) {
            App.api('../api/pedidos/update_status.php', { id: pedido_id, estado: estado })
                .done(function (res) {
                    if (res.success) {
                        cargarMesasActivas();
                        App.notify('success', 'Estado del pedido actualizado');
                    }
                });
        }
    });

    // Check query string
    if (INIT_MESA_ID) {
        // Necesitamos encontrar si está activa o disponible
        setTimeout(() => {
            // Check si ya hay pedido para esta mesa (esta lógica requeriría buscar en mesas activas pero por ahora validaremos visualmente)
            $('#select_mesa').val(INIT_MESA_ID);
        }, 500);
    }
});

function cargarMesasDisponibles() {
    App.api('../api/mesas/read.php', {}, 'GET').done(function (res) {
        if (res.success) {
            let options = '<option value="">Seleccione Mesa Disponible...</option>';
            res.data.forEach(m => {
                if (m.estado === 'disponible') {
                    options += `<option value="${m.id}">Mesa ${m.numero} (${m.capacidad} pax)</option>`;
                }
            });
            $('#select_mesa').html(options);
        }
    });
}

function cargarMesasActivas() {
    App.api('../api/pedidos/read_actives.php', {}, 'GET').done(function (res) {
        if (res.success) {
            let html = '';
            if (res.data.length === 0) {
                html = '<div class="text-center py-3 text-muted small">No hay mesas activas</div>';
            } else {
                res.data.forEach(p => {
                    let stColor = p.estado === 'pendiente' ? 'warning' : (p.estado === 'preparacion' ? 'primary' : 'success');
                    html += `
                    <div class="p-2 border-bottom cursor-pointer rounded mesa-activa-item" onclick="cargarDetallePedido(${p.id}, 'Mesa ${p.mesa_numero}')" style="cursor:pointer;" onmouseover="this.classList.add('bg-light')" onmouseout="this.classList.remove('bg-light')">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark"><i class="fa-solid fa-chair me-2 text-muted"></i>Mesa ${p.mesa_numero}</span>
                            <span class="badge bg-${stColor} small">${p.estado}</span>
                        </div>
                        <div class="small text-muted mt-1 d-flex justify-content-between">
                            <span>Total: <span class="text-success fw-medium">$${parseFloat(p.total).toFixed(2)}</span></span>
                            <span>#${p.id}</span>
                        </div>
                    </div>
                    `;
                });
            }
            $('#mesasActivasList').html(html);
        }
    });
}

function cargarMenuData() {
    App.api('../api/menu/read.php', {}, 'GET').done(function (res) {
        if (res.success) {
            menuPlatillos = res.data.filter(m => m.estado === 'activo');
            renderMenuGrid();
        }
    });
}

function renderMenuGrid() {
    let html = '<div class="list-group list-group-flush">';
    menuPlatillos.forEach(m => {
        html += `
        <div class="list-group-item list-group-item-action p-3 menu-item-card" style="cursor:pointer;" onclick="agregarItemPedido(${m.id})">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 fw-bold text-dark">${m.nombre}</h6>
                    <small class="text-muted"><span class="badge bg-light text-dark border me-1">${m.categoria}</span></small>
                </div>
                <div class="text-end">
                    <span class="fs-5 fw-bold text-success">$${parseFloat(m.precio).toFixed(2)}</span>
                    <button class="btn btn-sm btn-primary ms-3 rounded-circle" style="width:30px;height:30px;padding:0;"><i class="fa-solid fa-plus"></i></button>
                </div>
            </div>
        </div>
        `;
    });
    html += '</div>';
    $('#menuItemsGrid').html(html);
}

function cargarDetallePedido(pedido_id, tituloMsg) {
    $('#emptyState').addClass('d-none');
    $('#activeState').removeClass('d-none');
    $('#pedidoTitle').text(tituloMsg + ` (Comanda #${pedido_id})`);
    $('#current_pedido_id').val(pedido_id);

    // Fetch details
    App.api('../api/pedidos/read_details.php', { id: pedido_id }, 'GET').done(function (res) {
        if (res.success) {
            $('#pedidoEstado').val(res.pedido.estado);

            let html = '';
            let total = 0;
            if (res.detalles.length === 0) {
                html = '<div class="text-center py-4 text-muted small">Asigna productos del menú a la comanda</div>';
            } else {
                res.detalles.forEach(d => {
                    let sub = parseFloat(d.precio) * parseInt(d.cantidad);
                    total += sub;
                    html += `
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <div>
                            <div class="fw-bold text-dark">${d.nombre}</div>
                            <div class="small text-muted">$${parseFloat(d.precio).toFixed(2)} c/u</div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="fw-bold fs-6">${d.cantidad}</div>
                            <div class="fw-bold text-success" style="width:70px;text-align:right;">$${sub.toFixed(2)}</div>
                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarItem(${d.id})"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    </div>`;
                });
            }
            $('#ticketItems').html(html);
            $('#ticketTotal').text('$' + total.toFixed(2));
        }
    });
}

function agregarItemPedido(id_menu) {
    let pedido_id = $('#current_pedido_id').val();
    if (!pedido_id) {
        App.notify('warning', 'Abre o selecciona una comanda primero');
        return;
    }

    App.api('../api/pedidos/add_item.php', { id_pedido: pedido_id, id_menu: id_menu, cantidad: 1 })
        .done(function (res) {
            if (res.success) {
                cargarDetallePedido(pedido_id, $('#pedidoTitle').text().split(' (')[0]);
                cargarMesasActivas();
            }
        });
}

function eliminarItem(id_detalle) {
    let pedido_id = $('#current_pedido_id').val();
    App.api('../api/pedidos/delete_item.php', { id_detalle: id_detalle, id_pedido: pedido_id })
        .done(function (res) {
            if (res.success) {
                cargarDetallePedido(pedido_id, $('#pedidoTitle').text().split(' (')[0]);
                cargarMesasActivas();
            }
        });
}

function cancelarPedido() {
    let pedido_id = $('#current_pedido_id').val();
    App.confirm('¿Cancelar Pedido?', 'Se liberará la mesa y se anulará el ticket.', function () {
        App.api('../api/pedidos/update_status.php', { id: pedido_id, estado: 'cancelado' })
            .done(function (res) {
                if (res.success) {
                    App.notify('success', 'Pedido cancelado');
                    $('#activeState').addClass('d-none');
                    $('#emptyState').removeClass('d-none');
                    cargarMesasActivas();
                    cargarMesasDisponibles();
                }
            });
    });
}

function cobrarPedido() {
    let pedido_id = $('#current_pedido_id').val();
    App.confirm('¿Cobrar Pedido?', 'El pedido se marcará como pagado, la mesa se liberará y el monto irá a caja.', function () {
        App.api('../api/pedidos/cobrar.php', { id: pedido_id })
            .done(function (res) {
                if (res.success) {
                    Swal.fire('Cobrado', 'Ticket cerrado con éxito', 'success');
                    $('#activeState').addClass('d-none');
                    $('#emptyState').removeClass('d-none');
                    cargarMesasActivas();
                    cargarMesasDisponibles();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
    });
}
