<?php
session_start();
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_rol'], ['admin', 'gerente'])) {
    header("Location: dashboard.php");
    exit();
}
$custom_css = ['../css/crud.css'];
include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark"><i class="fa-solid fa-chair me-2 text-primary"></i>Gestión de Mesas</h2>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#mesaModal" onclick="resetForm()">
        <i class="fa-solid fa-plus me-1"></i> Nueva Mesa
    </button>
</div>

<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Número de Mesa</th>
                        <th>Capacidad (Personas)</th>
                        <th>Estado Actual</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody id="mesasTableBody">
                    <tr><td colspan="4" class="text-center py-4">Cargando mesas...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Mesa -->
<div class="modal fade" id="mesaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle"><i class="fa-solid fa-chair me-2 text-primary"></i>Nueva Mesa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="mesaForm">
                <div class="modal-body">
                    <input type="hidden" id="mesa_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Número de Mesa</label>
                        <input type="number" class="form-control" id="numero" name="numero" min="1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Capacidad</label>
                        <input type="number" class="form-control" id="capacidad" name="capacidad" min="1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="disponible">Disponible</option>
                            <option value="ocupada">Ocupada</option>
                            <option value="reservada">Reservada</option>
                            <option value="mantenimiento">Mantenimiento</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Mesa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
$custom_js = ['../js/mesas.js'];
include '../includes/footer.php'; 
?>
