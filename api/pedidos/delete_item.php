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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_detalle = $_POST['id_detalle'] ?? '';
    $id_pedido = $_POST['id_pedido'] ?? '';

    if (empty($id_detalle) || empty($id_pedido)) {
        echo json_encode(['success' => false, 'message' => 'Faltan parámetros']);
        exit();
    }

    try {
        $pdo->beginTransaction();

        // Obtener info del detalle a eliminar
        $stmt_get = $pdo->prepare("SELECT cantidad, precio FROM detalle_pedido WHERE id = ? AND id_pedido = ?");
        $stmt_get->execute([$id_detalle, $id_pedido]);
        $detalle = $stmt_get->fetch();

        if ($detalle) {
            $subtotal = $detalle['cantidad'] * $detalle['precio'];

            // Eliminar detalle
            $stmt = $pdo->prepare("DELETE FROM detalle_pedido WHERE id = ?");
            $stmt->execute([$id_detalle]);

            // Restar del total del pedido
            $stmt_total = $pdo->prepare("UPDATE pedidos SET total = total - ? WHERE id = ?");
            $stmt_total->execute([$subtotal, $id_pedido]);

            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Artículo removido']);
        } else {
            throw new \Exception("Detalle no encontrado");
        }
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
