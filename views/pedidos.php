<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit();
}
$custom_css = ['../css/crud.css'];
include '../includes/header.php';

// Obtener mesa si viene por query string
$mesa_id_query = isset($_GET['mesa']) ? intval($_GET['mesa']) : '';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark"><i class="fa-solid fa-bell-concierge me-2 text-primary"></i>Comandas / Pedidos</h2>
    <span class="text-muted"><i class="fa-solid fa-circle-info me-1"></i>Seleccione una mesa para iniciar o editar pedido</span>
</div>

<div class="row g-4">
    <!-- Panel Izquierdo: Selección de Mesas y Mesas Activas -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-white rounded-4 mb-3">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <h6 class="fw-bold text-primary mb-0">Iniciar Comanda</h6>
            </div>
            <div class="card-body">
                <select id="select_mesa" class="form-select mb-3">
                    <option value="">Seleccione Mesa Disponible...</option>
                </select>
                <button class="btn btn-primary w-100 fw-bold shadow-sm" id="btnCrearPedido">
                    <i class="fa-solid fa-plus me-1"></i> Abrir Mesa
                </button>
            </div>
        </div>
        
        <div class="card shadow-sm border-0 bg-white rounded-4">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <h6 class="fw-bold text-dark mb-0">Mesas Activas</h6>
            </div>
            <div class="card-body p-2" id="mesasActivasList">
                <div class="text-center py-3 text-muted small"><i class="fa-solid fa-spinner fa-spin"></i> Cargando...</div>
            </div>
        </div>
    </div>

    <!-- Panel Derecho: Detalle de Pedido -->
    <div class="col-md-9" id="panelPedido">
        <div class="card shadow-sm border-0 rounded-4 h-100 bg-light opacity-50 text-center d-flex align-items-center justify-content-center" id="emptyState" style="min-height: 400px;">
            <div>
                <i class="fa-solid fa-utensils fa-4x text-muted mb-3 opacity-25"></i>
                <h4 class="text-muted fw-normal">Seleccione una Mesa Activa o inicie una comanda</h4>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 h-100 d-none" id="activeState">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-3 d-flex justify-content-between align-items-center">
                <h4 class="fw-bold mb-0 text-primary" id="pedidoTitle">Mesa # --</h4>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm fw-bold border-0 bg-light" id="pedidoEstado" style="width: auto;">
                        <option value="pendiente">Pendiente</option>
                        <option value="preparacion">En Preparación</option>
                        <option value="servido">Servido</option>
                    </select>
                </div>
            </div>
            <div class="card-body p-0 border-top bg-light">
                <div class="row g-0">
                    <!-- Productos / Menú -->
                    <div class="col-md-7 border-end bg-white">
                        <div class="p-3 border-bottom">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" id="searchMenu" placeholder="Buscar platillo..." autocomplete="off">
                            </div>
                        </div>
                        <div class="p-0 overflow-auto" style="height: 400px;" id="menuItemsGrid">
                            <!-- Items menú -->
                        </div>
                    </div>
                    <!-- Ticket de Comanda -->
                    <div class="col-md-5 d-flex flex-column" style="height: auto;">
                        <input type="hidden" id="current_pedido_id">
                        <div class="p-3 bg-white flex-grow-1 overflow-auto" id="ticketItems" style="max-height: 380px;">
                            <div class="text-center py-4 text-muted small">Sin artículos</div>
                        </div>
                        <div class="p-3 bg-white border-top mt-auto">
                            <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                                <span>TOTAL</span>
                                <span class="text-success" id="ticketTotal">$0.00</span>
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-success fw-bold py-2 shadow-sm" id="btnCobrar" onclick="cobrarPedido()">
                                    <i class="fa-solid fa-cash-register me-1"></i> Cobrar y Cerrar
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="cancelarPedido()">
                                    <i class="fa-solid fa-ban me-1"></i> Cancelar Pedido
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const INIT_MESA_ID = '<?php echo $mesa_id_query; ?>';
</script>
<?php 
$custom_js = ['../js/pedidos.js'];
include '../includes/footer.php'; 
?>
