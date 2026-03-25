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

try {
    $stmt = $pdo->query("
        SELECT r.*, c.nombre as cliente_nombre, m.numero as mesa_numero, m.capacidad 
        FROM reservaciones r 
        JOIN clientes c ON r.id_cliente = c.id 
        JOIN mesas m ON r.id_mesa = m.id 
        ORDER BY r.fecha DESC, r.hora DESC
    ");
    $reservaciones = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $reservaciones]);
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
