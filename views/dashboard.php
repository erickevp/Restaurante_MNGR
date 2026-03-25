<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit();
}
$custom_css = ['../css/dashboard.css'];
include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark"><i class="fa-solid fa-chart-line me-2 text-primary"></i>Dashboard</h2>
    <span class="text-muted">Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></strong></span>
</div>

<div class="row g-4 mb-4">
    <!-- Stats Cards -->
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-0 bg-primary text-white rounded-4 h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                <i class="fa-solid fa-bell-concierge fa-3x mb-3 opacity-75"></i>
                <h5 class="card-title fw-normal">Pedidos Activos</h5>
                <h2 class="fw-bold mb-0" id="stat-pedidos">0</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-0 bg-success text-white rounded-4 h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                <i class="fa-solid fa-chair fa-3x mb-3 opacity-75"></i>
                <h5 class="card-title fw-normal">Mesas Disponibles</h5>
                <h2 class="fw-bold mb-0" id="stat-mesas">0</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-0 bg-warning text-dark rounded-4 h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                <i class="fa-solid fa-calendar-check fa-3x mb-3 opacity-75"></i>
                <h5 class="card-title fw-normal">Reservaciones Hoy</h5>
                <h2 class="fw-bold mb-0" id="stat-reservaciones">0</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-0 bg-danger text-white rounded-4 h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                <i class="fa-solid fa-cash-register fa-3x mb-3 opacity-75"></i>
                <h5 class="card-title fw-normal">Ingresos del Día</h5>
                <h2 class="fw-bold mb-0" id="stat-ingresos">$0.00</h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h5 class="fw-bold"><i class="fa-solid fa-utensils me-2 text-primary"></i>Mesas Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="row g-3" id="mesas-grid">
                    <!-- Las mesas se cargarán aquí por Ajax -->
                    <div class="text-center py-5 text-muted">Cargando mesas...</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h5 class="fw-bold"><i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i>Actividad Reciente</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush mb-0" id="actividad-reciente">
                    <li class="list-group-item text-center py-4 text-muted border-bottom-0">Cargando actividad...</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php 
$custom_js = ['../js/dashboard.js'];
include '../includes/footer.php'; 
?>
