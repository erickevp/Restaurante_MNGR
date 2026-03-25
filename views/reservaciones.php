<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit();
}
$custom_css = ['../css/crud.css'];
include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark"><i class="fa-solid fa-calendar-check me-2 text-primary"></i>Reservaciones</h2>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#reservacionModal" onclick="resetForm()">
        <i class="fa-solid fa-plus me-1"></i> Nueva Reservación
    </button>
</div>

<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Fecha y Hora</th>
                        <th>Cliente</th>
                        <th>Mesa / Personas</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody id="reservacionesTableBody">
                    <tr><td colspan="5" class="text-center py-4">Cargando reservaciones...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Reservación -->
<div class="modal fade" id="reservacionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle"><i class="fa-solid fa-calendar-plus me-2 text-primary"></i>Nueva Reservación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reservacionForm">
                <div class="modal-body">
                    <input type="hidden" id="reservacion_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Cliente</label>
                        <select class="form-select" id="id_cliente" name="id_cliente" required>
                            <option value="">Seleccione un cliente...</option>
                        </select>
                        <small class="text-muted">Si no existe, constrúyalo en <a href="clientes.php">Clientes</a></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Mesa (Capacidad)</label>
                        <select class="form-select" id="id_mesa" name="id_mesa" required>
                            <option value="">Seleccione una mesa...</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Hora</label>
                            <input type="time" class="form-control" id="hora" name="hora" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="completada">Completada</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
$custom_js = ['../js/reservaciones.js'];
include '../includes/footer.php'; 
?>
