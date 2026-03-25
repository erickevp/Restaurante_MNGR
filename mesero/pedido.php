<?php
// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}
$mesa_id = $_GET['mesa_id'] ?? '';
$numero = $_GET['numero'] ?? '--';
if(empty($mesa_id)) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Comanda Mesa <?php echo $numero; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/mesero.css">
    <style>
        .mesero-header { background-color: #2c3e50; color: white; padding: 15px; position: sticky; top:0; z-index: 1020;}
        .menu-category { overflow-x: auto; white-space: nowrap; padding-bottom: 10px; margin-bottom: 15px; }
        .menu-category .btn { min-width: 100px; margin-right: 10px; border-radius: 20px; }
        .floating-ticket-btn { position: fixed; bottom: 20px; right: 20px; width: 65px; height: 65px; border-radius: 50%; box-shadow: 0 4px 15px rgba(0,0,0,0.3); z-index: 1040; display: flex; align-items: center; justify-content: center; }
        .ticket-badge { position: absolute; top: 0; right: 0; transform: translate(25%, -25%); border-radius: 50%; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; font-size: 12px;}
    </style>
</head>
<body class="bg-light pb-5 mb-5">

<!-- Topbar -->
<div class="mesero-header d-flex justify-content-between align-items-center mb-3">
    <button class="btn btn-outline-light border-0" onclick="window.location.href='dashboard.php'">
        <i class="fa-solid fa-chevron-left fa-lg"></i>
    </button>
    <h5 class="mb-0 fw-bold">Mesa <?php echo htmlspecialchars($numero); ?></h5>
    <div id="statusBadge"><span class="badge bg-secondary">Cargando...</span></div>
</div>

<div class="container-fluid px-3">
    <!-- Category Filter -->
    <div class="menu-category" id="categoryTabs">
        <button class="btn btn-primary fw-bold" onclick="filtrarMenu('todas')">Todas</button>
        <!-- Dynamic Categories -->
    </div>

    <!-- Search -->
    <div class="mb-3">
        <div class="input-group">
            <span class="input-group-text bg-white border-0 rounded-start-pill ps-3"><i class="fa-solid fa-search text-muted"></i></span>
            <input type="text" id="searchTouch" class="form-control form-control-lg border-0 rounded-end-pill shadow-sm" placeholder="Buscar platillo...">
        </div>
    </div>

    <!-- Menu Grid -->
    <div class="row g-3" id="menuTouchGrid">
        <div class="text-center py-5 text-muted"><i class="fa-solid fa-spinner fa-spin fa-2x"></i></div>
    </div>
</div>

<!-- Floating Action Button for Ticket -->
<button class="btn btn-success floating-ticket-btn" data-bs-toggle="offcanvas" data-bs-target="#ticketOffcanvas" id="btnTicket">
    <i class="fa-solid fa-receipt fa-2x"></i>
    <span class="badge bg-danger ticket-badge fw-bold shadow" id="ticketItemCount">0</span>
</button>

<!-- Ticket Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="ticketOffcanvas" style="width: 100%; max-width: 400px;">
    <div class="offcanvas-header bg-light border-bottom">
        <h5 class="offcanvas-title fw-bold text-dark"><i class="fa-solid fa-receipt me-2 text-primary"></i>Comanda Actual</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0 d-flex flex-column bg-white">
        <div class="flex-grow-1 overflow-auto p-3" id="ticketList">
            <!-- Ticket Items -->
            <div class="text-center py-5 text-muted">No hay artículos</div>
        </div>
        <div class="border-top p-3 bg-light mt-auto">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fs-5 fw-bold">Total:</span>
                <span class="fs-3 fw-bold text-success" id="ticketTotalAmount">$0.00</span>
            </div>
            <button class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow-sm mb-2" id="btnEnviarCocina" onclick="enviarACocina()">
                <i class="fa-solid fa-bell-concierge me-2"></i> Enviar Comanda a Cocina
            </button>
            <button class="btn btn-outline-danger w-100 py-2 rounded-4 fw-bold" onclick="cancelarComanda()">
                Cancelar Pedido
            </button>
        </div>
    </div>
</div>

<script>
    const MESA_ID = <?php echo json_encode($mesa_id); ?>;
</script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/main.js"></script>
<script src="../js/mesero_pedido.js"></script>
</body>
</html>
