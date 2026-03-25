<?php
// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

session_start();
require_once '../../includes/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

$condicion_usuario = "";
if ($_SESSION['usuario_rol'] !== 'admin') {
    // Si no es admin, solo ve sus cortes
    $condicion_usuario = " WHERE c.id_usuario = " . intval($_SESSION['usuario_id']);
}

try {
    $stmt = $pdo->query("
        SELECT c.*, u.nombre as usuario_nombre 
        FROM caja c 
        JOIN usuarios u ON c.id_usuario = u.id 
        $condicion_usuario
        ORDER BY c.fecha_apertura DESC 
        LIMIT 50
    ");
    $cajas = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $cajas]);
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
