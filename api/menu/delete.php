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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
        exit();
    }

    try {
        // Verificar si está en detalles de pedidos (lógica futura de integridad)
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM detalle_pedido WHERE id_menu = ?");
        $stmt_check->execute([$id]);
        
        if($stmt_check->fetchColumn() > 0) {
            // Soft delete: cambiar estado a inactivo
            $stmt = $pdo->prepare("UPDATE menu SET estado = 'inactivo' WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'El platillo tiene pedidos. Su estado cambió a "inactivo".']);
        } else {
            // Hard delete
            $stmt = $pdo->prepare("DELETE FROM menu WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Platillo eliminado por completo']);
        }
    } catch (\PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
