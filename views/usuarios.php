<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
$custom_css = ['../css/crud.css'];
include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark"><i class="fa-solid fa-users-gear me-2 text-primary"></i>Gestión de Usuarios</h2>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#usuarioModal" onclick="resetForm()">
        <i class="fa-solid fa-plus me-1"></i> Nuevo Usuario
    </button>
</div>

<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Nombre Completo</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody id="usuariosTableBody">
                    <tr><td colspan="4" class="text-center py-4">Cargando usuarios...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Usuario -->
<div class="modal fade" id="usuarioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle"><i class="fa-solid fa-user-plus me-2 text-primary"></i>Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="usuarioForm">
                <div class="modal-body">
                    <input type="hidden" id="usuario_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre Completo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="usuario_text" name="usuario" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Contraseña <span class="text-muted small" id="pwdHelp">(Dejar en blanco para no cambiar)</span></label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Rol del Sistema</label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="">Seleccione un rol...</option>
                            <option value="admin">Administrador</option>
                            <option value="gerente">Gerente</option>
                            <option value="cajero">Cajero</option>
                            <option value="mesero">Mesero</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
$custom_js = ['../js/usuarios.js'];
include '../includes/footer.php'; 
?>
