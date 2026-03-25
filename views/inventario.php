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
    <h2 class="fw-bold text-dark"><i class="fa-solid fa-box me-2 text-primary"></i>Gestión de Inventario</h2>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#inventarioModal" onclick="resetForm()">
        <i class="fa-solid fa-plus me-1"></i> Nuevo Artículo
    </button>
</div>

<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Artículo</th>
                        <th>Cantidad Disponible</th>
                        <th>Costo Unitario ($)</th>
                        <th>Valor Total ($)</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody id="inventarioTableBody">
                    <tr><td colspan="5" class="text-center py-4">Cargando inventario...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Inventario -->
<div class="modal fade" id="inventarioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle"><i class="fa-solid fa-box-open me-2 text-primary"></i>Nuevo Artículo de Inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="inventarioForm">
                <div class="modal-body">
                    <input type="hidden" id="inventario_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre del Artículo</label>
                        <input type="text" class="form-control" id="articulo" name="articulo" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Cantidad Mínima/Actual</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Unidad de Medida</label>
                            <select class="form-select" id="unidad" name="unidad" required>
                                <option value="kg">Kilogramos (kg)</option>
                                <option value="g">Gramos (g)</option>
                                <option value="l">Litros (L)</option>
                                <option value="ml">Mililitros (ml)</option>
                                <option value="pz">Piezas (pz)</option>
                                <option value="caja">Cajas</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Costo Unitario ($)</label>
                        <input type="number" class="form-control" id="precio_unitario" name="precio_unitario" step="0.01" min="0.01" required>
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
$custom_js = ['../js/inventario.js'];
include '../includes/footer.php'; 
?>
