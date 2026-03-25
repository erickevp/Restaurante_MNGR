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
    <h2 class="fw-bold text-dark"><i class="fa-solid fa-burger me-2 text-primary"></i>Gestión de Menú</h2>
    <div>
        <button class="btn btn-outline-secondary shadow-sm me-2" data-bs-toggle="modal" data-bs-target="#categoriaModal">
            <i class="fa-solid fa-tags me-1"></i> Categorías
        </button>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#menuModal" onclick="resetForm()">
            <i class="fa-solid fa-plus me-1"></i> Nuevo Platillo
        </button>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Platillo / Bebida</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody id="menuTableBody">
                    <tr><td colspan="5" class="text-center py-4">Cargando menú...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Menú -->
<div class="modal fade" id="menuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle"><i class="fa-solid fa-burger me-2 text-primary"></i>Nuevo Elemento del Menú</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="menuForm">
                <div class="modal-body">
                    <input type="hidden" id="menu_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Categoría</label>
                        <select class="form-select" id="id_categoria" name="id_categoria" required>
                            <option value="">Cargando categorías...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="2"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Precio ($)</label>
                            <input type="number" class="form-control" id="precio" name="precio" min="0.01" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="activo">Activo (Disponible)</option>
                                <option value="inactivo">Inactivo (Agotado)</option>
                            </select>
                        </div>
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

<!-- Modal Categorías -->
<div class="modal fade" id="categoriaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-tags me-2 text-primary"></i>Nueva Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="categoriaForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre de la Categoría</label>
                        <input type="text" class="form-control" id="nombre_categoria" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="submit" class="btn btn-outline-primary w-100" id="btnGuardarCat">Agregar Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
$custom_js = ['../js/menu.js'];
include '../includes/footer.php'; 
?>
