<?php
// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario_id']) && basename($_SERVER['PHP_SELF']) !== 'index.php') {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante App</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS Base -->
    <link rel="stylesheet" href="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'css/style.css' : '../css/style.css'; ?>">
    
    <?php if(isset($custom_css)): ?>
        <?php foreach($custom_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="bg-light">

<?php if (isset($_SESSION['usuario_id'])): ?>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark brand-navbar sticky-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="fa-solid fa-utensils me-2"></i>Restaurante
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php"><i class="fa-solid fa-home me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pedidos.php"><i class="fa-solid fa-bell-concierge me-1"></i> Pedidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="caja.php"><i class="fa-solid fa-cash-register me-1"></i> Caja</a>
                    </li>
                    <?php if($_SESSION['usuario_rol'] === 'admin' || $_SESSION['usuario_rol'] === 'gerente'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarAdmin" role="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-gear me-1"></i> Administración
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="mesas.php"><i class="fa-solid fa-chair me-2"></i>Mesas</a></li>
                            <li><a class="dropdown-item" href="menu.php"><i class="fa-solid fa-burger me-2"></i>Menú</a></li>
                            <li><a class="dropdown-item" href="inventario.php"><i class="fa-solid fa-box me-2"></i>Inventario</a></li>
                            <li><a class="dropdown-item" href="mermas.php"><i class="fa-solid fa-trash me-2"></i>Mermas</a></li>
                            <li><a class="dropdown-item" href="reservaciones.php"><i class="fa-solid fa-calendar-check me-2"></i>Reservaciones</a></li>
                            <li><a class="dropdown-item" href="clientes.php"><i class="fa-solid fa-users me-2"></i>Clientes</a></li>
                            <li><a class="dropdown-item" href="promociones.php"><i class="fa-solid fa-tag me-2"></i>Promociones</a></li>
                            <?php if($_SESSION['usuario_rol'] === 'admin'): ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="usuarios.php"><i class="fa-solid fa-user-tie me-2"></i>Usuarios</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex align-items-center text-white">
                    <span class="me-3 fw-medium"><i class="fa-regular fa-user me-1"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?> (<?php echo ucfirst($_SESSION['usuario_rol']); ?>)</span>
                    <a href="../api/auth/logout.php" class="btn btn-outline-light btn-sm"><i class="fa-solid fa-right-from-bracket"></i> Salir</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container-fluid mt-4 fade-in">
<?php endif; ?>
