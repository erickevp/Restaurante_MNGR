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
    <h2 class="fw-bold text-dark"><i class="fa-solid fa-trash-can-arrow-up me-2 text-primary"></i>Registro de Mermas</h2>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#mermaModal" onclick="resetForm()">
        <i class="fa-solid fa-plus me-1"></i> Registrar Merma
    </button>
</div>

<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Fecha</th>
                        <th>Artículo (Inventario)</th>
                        <th>Cantidad Perdida</th>
                        <th>Motivo</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody id="mermasTableBody">
                    <tr><td colspan="5" class="text-center py-4">Cargando mermas...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Merma -->
<div class="modal fade" id="mermaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle"><i class="fa-solid fa-tags me-2 text-primary"></i>Nueva Merma</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="mermaForm">
                <div class="modal-body">
                    <input type="hidden" id="merma_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Artículo de Inventario</label>
                        <select class="form-select" id="id_inventario" name="id_inventario" required>
                            <option value="">Cargando inventario...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Cantidad Mermada</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" step="0.01" min="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Motivo o Justificación</label>
                        <textarea class="form-control" id="motivo" name="motivo" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Registrar Merma</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
$custom_js = ['../js/mermas.js'];
include '../includes/footer.php'; 
?>
