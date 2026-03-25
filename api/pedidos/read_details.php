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

$id = $_GET['id'] ?? '';
if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID de pedido requerido']);
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT p.id, p.id_mesa, p.total, p.estado, m.numero as mesa_numero FROM pedidos p JOIN mesas m ON p.id_mesa = m.id WHERE p.id = ?");
    $stmt->execute([$id]);
    $pedido = $stmt->fetch();

    if(!$pedido) {
        echo json_encode(['success' => false, 'message' => 'Pedido no encontrado']);
        exit();
    }

    $stmt_det = $pdo->prepare("
        SELECT dp.id, dp.id_menu, dp.cantidad, dp.precio, m.nombre 
        FROM detalle_pedido dp 
        JOIN menu m ON dp.id_menu = m.id 
        WHERE dp.id_pedido = ?
    ");
    $stmt_det->execute([$id]);
    $detalles = $stmt_det->fetchAll();

    echo json_encode(['success' => true, 'pedido' => $pedido, 'detalles' => $detalles]);
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
