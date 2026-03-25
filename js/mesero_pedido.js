// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

let menuOriginal = [];
let pedidoActivoId = null;

$(document).ready(function () {
    verificarEstadoMesa();
    cargarMenu();

    $('#searchTouch').on('keyup', function () {
        let val = $(this).val().toLowerCase();
        $('.menu-item-touch').filter(function () {
            $(this).toggle($(this).find('.item-title').text().toLowerCase().indexOf(val) > -1);
        });
    });
});

function verificarEstadoMesa() {
    App.api('../api/pedidos/read_actives.php', {}, 'GET').done(function (res) {
        if (res.success) {
            let pActivo = res.data.find(p => p.id_mesa == MESA_ID);
            if (pActivo) {
                pedidoActivoId = pActivo.id;
                actualizarBadgeEstado(pActivo.estado);
                cargarDetalleComanda();
            } else {
                // Not active, available explicitly
                $('#statusBadge').html('<span class="badge bg-success">LIBRE</span>');
                $('#ticketItemCount').text('0');
                $('#ticketList').html('<div class="text-center py-5 text-muted">Añade productos para iniciar comanda</div>');
                $('#ticketTotalAmount').text('$0.00');
            }
        }
    });
}

function actualizarBadgeEstado(estado) {
    if (estado === 'pendiente') $('#statusBadge').html('<span class="badge bg-warning text-dark">PENDIENTE</span>');
    else if (estado === 'preparacion') $('#statusBadge').html('<span class="badge bg-primary">PREPARANDO</span>');
    else if (estado === 'servido') $('#statusBadge').html('<span class="badge bg-success">SERVIDO</span>');
}

function cargarMenu() {
    App.api('../api/menu/read.php', {}, 'GET').done(function (res) {
        if (res.success) {
            menuOriginal = res.data.filter(m => m.estado === 'activo');

            // Build categories
            let cats = [...new Set(menuOriginal.map(item => item.categoria))];
            let catHtml = '<button class="btn btn-primary fw-bold" onclick="filtrarMenu(\'todas\', this)">Todas</button>';
            cats.forEach(c => {
                catHtml += `<button class="btn btn-outline-primary fw-bold" onclick="filtrarMenu('${c}', this)">${c}</button>`;
            });
            $('#categoryTabs').html(catHtml);

            renderMenu(menuOriginal);
        }
    });
}

function filtrarMenu(categoria, btnElement) {
    // Style active button
    $('#categoryTabs .btn').removeClass('btn-primary').addClass('btn-outline-primary');
    $(btnElement).removeClass('btn-outline-primary').addClass('btn-primary');

    if (categoria === 'todas') {
        renderMenu(menuOriginal);
    } else {
        renderMenu(menuOriginal.filter(m => m.categoria === categoria));
    }
}

function renderMenu(items) {
    let html = '';
    items.forEach(m => {
        let precioFormat = parseFloat(m.precio).toFixed(2);
        html += `
        <div class="col-12 col-md-6 menu-item-touch bg-white p-3 d-flex justify-content-between align-items-center" onclick="agregarItem(${m.id})" style="cursor:pointer;">
            <div>
                <h6 class="fw-bold text-dark mb-1 item-title" style="font-size: 1.15rem;">${m.nombre}</h6>
                <div class="text-success fw-bold">$${precioFormat}</div>
            </div>
            <button class="btn btn-light text-primary rounded-circle shadow-sm" style="width: 45px; height: 45px;">
                <i class="fa-solid fa-plus mt-1"></i>
            </button>
        </div>
        `;
    });
    $('#menuTouchGrid').html(html);
}

function agregarItem(id_menu) {
    // Si no hay pedido activo, lo creamos primero
    if (!pedidoActivoId) {
        App.api('../api/pedidos/create.php', { id_mesa: MESA_ID })
            .done(function (res) {
                if (res.success) {
                    pedidoActivoId = res.pedido_id;
                    actualizarBadgeEstado('pendiente');
                    ejecutarAgregar(id_menu);
                }
            });
    } else {
        ejecutarAgregar(id_menu);
    }
}

function ejecutarAgregar(id_menu) {
    App.api('../api/pedidos/add_item.php', { id_pedido: pedidoActivoId, id_menu: id_menu, cantidad: 1 })
        .done(function (res) {
            if (res.success) {
                App.notify('success', 'Añadido', 'top-end');
                cargarDetalleComanda();
            }
        });
}

function cargarDetalleComanda() {
    if (!pedidoActivoId) return;

    App.api('../api/pedidos/read_details.php', { id: pedidoActivoId }, 'GET').done(function (res) {
        if (res.success) {
            let html = '';
            let total = 0;
            let count = 0;

            if (res.detalles.length === 0) {
                html = '<div class="text-center py-5 text-muted">Añade productos para iniciar comanda</div>';
            } else {
                res.detalles.forEach(d => {
                    let sub = parseFloat(d.precio) * parseInt(d.cantidad);
                    total += sub;
                    count += parseInt(d.cantidad);
                    html += `
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <div class="w-50">
                            <div class="fw-bold text-dark text-truncate">${d.nombre}</div>
                            <div class="text-muted small">$${parseFloat(d.precio).toFixed(2)} c/u</div>
                        </div>
                        <div class="d-flex align-items-center bg-light rounded-pill px-2">
                            <span class="fw-bold text-primary mx-2">${d.cantidad}x</span>
                        </div>
                        <div class="fw-bold text-dark text-end" style="width: 60px;">$${sub.toFixed(2)}</div>
                        <button class="btn btn-sm btn-outline-danger border-0 ms-1 rounded-circle" onclick="eliminarLinea(${d.id})">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>`;
                });
            }
            $('#ticketList').html(html);
            $('#ticketTotalAmount').text('$' + total.toFixed(2));
            $('#ticketItemCount').text(count);

            // Pop the button a bit
            $('#btnTicket').addClass('scale-up');
            setTimeout(() => $('#btnTicket').removeClass('scale-up'), 200);
        }
    });
}

function eliminarLinea(id_detalle) {
    App.api('../api/pedidos/delete_item.php', { id_detalle: id_detalle, id_pedido: pedidoActivoId })
        .done(function (res) {
            if (res.success) {
                cargarDetalleComanda();
            }
        });
}

function enviarACocina() {
    if (!pedidoActivoId || $('#ticketItemCount').text() === '0') {
        App.notify('warning', 'Comanda vacía');
        return;
    }

    // For waiters, sending to kitchen means setting status to 'preparacion'
    App.api('../api/pedidos/update_status.php', { id: pedidoActivoId, estado: 'preparacion' })
        .done(function (res) {
            if (res.success) {
                $('#ticketOffcanvas').offcanvas('hide');
                Swal.fire({
                    icon: 'success',
                    title: '¡Comanda Enviada!',
                    text: 'La orden ya está en la cocina',
                    timer: 2000,
                    showConfirmButton: false
                });
                actualizarBadgeEstado('preparacion');
            }
        });
}

function cancelarComanda() {
    if (!pedidoActivoId) return;
    App.confirm('¿Cancelar Pedido?', 'Se anulará por completo la orden', function () {
        App.api('../api/pedidos/update_status.php', { id: pedidoActivoId, estado: 'cancelado' })
            .done(function (res) {
                if (res.success) {
                    Swal.fire('Cancelado', 'La mesa ha sido liberada', 'success');
                    setTimeout(() => { window.location.href = 'dashboard.php'; }, 1500);
                }
            });
    });
}
