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
    <h2 class="fw-bold text-dark"><i class="fa-solid fa-tags me-2 text-primary"></i>Gestión de Promociones</h2>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#promocionModal" onclick="resetForm()">
        <i class="fa-solid fa-plus me-1"></i> Nueva Promoción
    </button>
</div>

<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Promoción</th>
                        <th>Descuento</th>
                        <th>Vigencia</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody id="promocionesTableBody">
                    <tr><td colspan="5" class="text-center py-4">Cargando promociones...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Promoción -->
<div class="modal fade" id="promocionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle"><i class="fa-solid fa-tag me-2 text-primary"></i>Nueva Promoción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="promocionForm">
                <div class="modal-body">
                    <input type="hidden" id="promocion_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre de la Promoción</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Descuento (%) o Monto Fijo</label>
                        <input type="number" class="form-control" id="descuento" name="descuento" step="0.01" min="0" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Fecha de Fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
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
$custom_js = ['../js/promociones.js'];
include '../includes/footer.php'; 
?>
