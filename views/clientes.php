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
    <h2 class="fw-bold text-dark"><i class="fa-solid fa-users me-2 text-primary"></i>Gestión de Clientes</h2>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#clienteModal" onclick="resetForm()">
        <i class="fa-solid fa-plus me-1"></i> Nuevo Cliente
    </button>
</div>

<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Nombre de Cliente</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody id="clientesTableBody">
                    <tr><td colspan="4" class="text-center py-4">Cargando clientes...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Cliente -->
<div class="modal fade" id="clienteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle"><i class="fa-solid fa-user-plus me-2 text-primary"></i>Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="clienteForm">
                <div class="modal-body">
                    <input type="hidden" id="cliente_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre Completo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
$custom_js = ['../js/clientes.js'];
include '../includes/footer.php'; 
?>
