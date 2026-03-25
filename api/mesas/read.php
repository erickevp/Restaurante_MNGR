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
    $stmt = $pdo->query("SELECT id, numero, capacidad, estado FROM mesas ORDER BY numero ASC");
    $mesas = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $mesas]);
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
