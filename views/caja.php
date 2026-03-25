<?php
session_start();
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_rol'], ['admin', 'gerente', 'cajero'])) {
    header("Location: dashboard.php");
    exit();
}
$custom_css = ['../css/crud.css'];
include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark"><i class="fa-solid fa-cash-register me-2 text-primary"></i>Corte y Gestión de Caja</h2>
    <div id="cajaStatusBtn">
        <button class="btn btn-success shadow-sm" onclick="abrirCajaModal()" id="btnAbrirCaja">
            <i class="fa-solid fa-lock-open me-1"></i> Abrir Caja
        </button>
        <button class="btn btn-warning shadow-sm d-none fw-bold" onclick="cerrarCajaModal()" id="btnCerrarCaja">
            <i class="fa-solid fa-lock me-1"></i> Realizar Corte de Caja
        </button>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 rounded-4 bg-primary text-white text-center p-3">
            <h6 class="text-uppercase mb-2 opacity-75">Fondo Inicial</h6>
            <h3 class="fw-bold mb-0" id="cardFondo">$0.00</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 rounded-4 bg-success text-white text-center p-3">
            <h6 class="text-uppercase mb-2 opacity-75">Ingresos (Ventas)</h6>
            <h3 class="fw-bold mb-0" id="cardIngresos">$0.00</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 rounded-4 bg-danger text-white text-center p-3">
            <h6 class="text-uppercase mb-2 opacity-75">Egresos / Retiros</h6>
            <h3 class="fw-bold mb-0" id="cardEgresos">$0.00</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 rounded-4 bg-dark text-white text-center p-3">
            <h6 class="text-uppercase mb-2 opacity-75">Efectivo en Caja</h6>
            <h3 class="fw-bold mb-0 text-warning" id="cardTotal">$0.00</h3>
        </div>
    </div>
</div>

<div class="d-flex mb-3 gap-2">
    <button class="btn btn-danger shadow-sm d-none" id="btnRegistrarEgreso" onclick="egresoModal()">
        <i class="fa-solid fa-minus me-1"></i> Registrar Retiro/Gasto
    </button>
</div>

<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-header bg-white pt-4 pb-0 border-0">
        <h5 class="fw-bold mb-0 text-dark">Historial de Cortes de Caja</h5>
    </div>
    <div class="card-body p-0 mt-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Usuario</th>
                        <th>Apertura</th>
                        <th>Cierre</th>
                        <th>Ingresos</th>
                        <th>Egresos</th>
                        <th>Total Final</th>
                        <th class="text-end pe-4">Estado</th>
                    </tr>
                </thead>
                <tbody id="historialCajaBody">
                    <tr><td colspan="7" class="text-center py-4">Cargando historial...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Abrir Caja -->
<div class="modal fade" id="abrirModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold"><i class="fa-solid fa-lock-open me-2 text-success"></i>Abrir Caja</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="abrirCajaForm">
                <div class="modal-body">
                    <label class="form-label fw-medium">Fondo Inicial ($)</label>
                    <input type="number" class="form-control form-control-lg fw-bold text-end" id="fondo_inicial" value="0.00" step="0.01" min="0" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success fw-bold">Abrir Turno</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Retiro/Egreso -->
<div class="modal fade" id="egresoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold"><i class="fa-solid fa-minus-circle me-2 text-danger"></i>Registrar Egreso</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="egresoCajaForm">
                <div class="modal-body">
                    <label class="form-label fw-medium">Monto a retirar ($)</label>
                    <input type="number" class="form-control form-control-lg fw-bold text-end text-danger mb-3" id="monto_egreso" step="0.01" min="0.01" required>
                    <small class="text-muted">Este monto se restará del total en caja.</small>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-bold">Retirar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
$custom_js = ['../js/caja.js'];
include '../includes/footer.php'; 
?>
