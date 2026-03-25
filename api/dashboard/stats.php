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

$action = $_GET['action'] ?? 'stats';

try {
    if ($action === 'stats') {
        $today = date('Y-m-d');
        
        // Pedidos activos (que no están pagados o cancelados)
        $stmt = $pdo->query("SELECT COUNT(*) FROM pedidos WHERE estado NOT IN ('pagado', 'cancelado')");
        $pedidos_activos = $stmt->fetchColumn();
        
        // Mesas disponibles
        $stmt = $pdo->query("SELECT COUNT(*) FROM mesas WHERE estado = 'disponible'");
        $mesas_disponibles = $stmt->fetchColumn();
        
        // Reservaciones de hoy
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservaciones WHERE fecha = ? AND estado != 'cancelada'");
        $stmt->execute([$today]);
        $reservaciones_hoy = $stmt->fetchColumn();
        
        // Ingresos de hoy en caja
        $stmt = $pdo->prepare("SELECT SUM(ingresos) FROM caja WHERE DATE(fecha) = ?");
        $stmt->execute([$today]);
        $ingresos_hoy = $stmt->fetchColumn() ?: 0;
        
        echo json_encode(['success' => true, 'data' => [
            'pedidos_activos' => $pedidos_activos,
            'mesas_disponibles' => $mesas_disponibles,
            'reservaciones_hoy' => $reservaciones_hoy,
            'ingresos_hoy' => $ingresos_hoy
        ]]);
    } 
    elseif ($action === 'mesas') {
        $stmt = $pdo->query("SELECT id, numero, capacidad, estado FROM mesas ORDER BY numero ASC");
        $mesas = $stmt->fetchAll();
        echo json_encode(['success' => true, 'data' => $mesas]);
    }
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener datos: ' . $e->getMessage()]);
}
?>
