<?php
// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Meseros - Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/mesero.css">
    <style>
        .login-touch-wrapper { height: 100vh; background-color: #2c3e50; display: flex; align-items: center; justify-content: center; }
        .login-touch-card { max-width: 450px; width: 90%; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); }
    </style>
</head>
<body>

<div class="login-touch-wrapper">
    <div class="card bg-white login-touch-card border-0 p-4">
        <div class="card-body text-center p-3">
            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                <i class="fa-solid fa-tablet-screen-button fa-3x"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">Punto de Venta</h3>
            <p class="text-muted mb-4 fs-5">Módulo para Meseros</p>

            <form id="loginMeseroForm">
                <div class="mb-4">
                    <input type="text" class="form-control form-control-touch text-center" id="usuario" name="usuario" placeholder="Usuario" required autocomplete="off">
                </div>
                <div class="mb-4">
                    <input type="password" class="form-control form-control-touch text-center" id="password" name="password" placeholder="Contraseña (PIN)" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 btn-touch shadow-sm mb-3" id="btnLogin">
                    <i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Ingresar
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/main.js"></script>
<script src="../js/mesero_auth.js"></script>
</body>
</html>
