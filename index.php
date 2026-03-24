<?php
// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: views/dashboard.php");
    exit();
}
$custom_css = ['css/login.css'];
$custom_js = ['js/auth.js'];
include 'includes/header.php';
?>

<div class="login-wrapper d-flex align-items-center justify-content-center vh-100">
    <div class="login-card card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div class="logo-circle bg-primary text-white mx-auto mb-3 shadow">
                    <i class="fa-solid fa-utensils fa-2x"></i>
                </div>
                <h4 class="fw-bold text-dark">Restaurante</h4>
                <p class="text-muted small">Ingresa tus credenciales para continuar</p>
            </div>
            
            <form id="loginForm">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="usuario" name="usuario" placeholder="Usuario" required>
                    <label for="usuario"><i class="fa-regular fa-user text-muted me-2"></i>Usuario</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="password" class="form-control rounded-3" id="password" name="password" placeholder="Contraseña" required>
                    <label for="password"><i class="fa-solid fa-lock text-muted me-2"></i>Contraseña</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-bold shadow-sm" id="btnLogin">
                    <span>Ingresar al Sistema</span>
                </button>
            </form>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
