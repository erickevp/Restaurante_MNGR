<?php
// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

session_start();
require_once '../../includes/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_rol'], ['admin', 'gerente'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

try {
    $stmt = $pdo->query("
        SELECT m.*, i.articulo, i.unidad 
        FROM mermas m 
        JOIN inventario i ON m.id_inventario = i.id 
        ORDER BY m.fecha DESC
    ");
    $mermas = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $mermas]);
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
