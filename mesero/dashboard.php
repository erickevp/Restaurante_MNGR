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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Mesas - Punto de Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/mesero.css">
    <style>
        .mesero-header { background-color: #2c3e50; color: white; padding: 15px 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .grid-mesas { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 15px; padding: 20px; }
        .mesa-item { height: 130px; display: flex; flex-direction: column; justify-content: center; align-items: center; border-radius: 15px; font-weight: bold; cursor: pointer; transition: transform 0.1s; }
        .mesa-item:active { transform: scale(0.95); }
        .mesa-disponible { background-color: #e8f5e9; color: #2e7d32; border: 2px solid #a5d6a7; }
        .mesa-ocupada { background-color: #fff3cd; color: #b07d00; border: 2px solid #ffeeba; }
    </style>
</head>
<body class="bg-light">

<div class="mesero-header d-flex justify-content-between align-items-center sticky-top">
    <div class="d-flex align-items-center">
        <div class="bg-white rounded-circle text-primary d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
            <i class="fa-solid fa-user"></i>
        </div>
        <div>
            <h5 class="mb-0 fw-bold"><?php echo $_SESSION['usuario_nombre'] ?? 'Mesero'; ?></h5>
            <small class="opacity-75">Seleccione una mesa</small>
        </div>
    </div>
    <button class="btn btn-outline-light btn-touch py-2 px-3" onclick="logout()">
        <i class="fa-solid fa-power-off"></i>
    </button>
</div>

<div class="container-fluid pt-3 pb-5 mb-5">
    <div class="d-flex justify-content-between px-3 mb-2 align-items-center">
        <h4 class="fw-bold mb-0 text-dark">Mapa de Mesas</h4>
        <button class="btn btn-primary rounded-circle shadow" style="width: 50px; height: 50px;" onclick="cargarMesas()">
            <i class="fa-solid fa-rotate-right"></i>
        </button>
    </div>
    <div class="grid-mesas" id="mesasGrid">
        <div class="text-center w-100 py-5 text-muted"><i class="fa-solid fa-spinner fa-spin fa-2x mb-3"></i><br>Cargando...</div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/main.js"></script>
<script src="../js/mesero_dashboard.js"></script>
</body>
</html>
