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
    $id = $_POST['id'] ?? '';
    $id_usuario = $_SESSION['usuario_id'];

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'Faltan parámetros']);
        exit();
    }

    try {
        $pdo->beginTransaction();

        // 1. Obtener total del pedido
        $stmt_ped = $pdo->prepare("SELECT total FROM pedidos WHERE id = ? AND estado != 'pagado'");
        $stmt_ped->execute([$id]);
        $total = $stmt_ped->fetchColumn();

        if ($total === false) {
            throw new \Exception("El pedido no existe o ya fue pagado.");
        }

        // 2. Marcar pedido como pagado
        $stmt_upd = $pdo->prepare("UPDATE pedidos SET estado = 'pagado' WHERE id = ?");
        $stmt_upd->execute([$id]);

        // 3. Liberar la mesa
        $stmt_mesa = $pdo->prepare("UPDATE mesas m JOIN pedidos p ON m.id = p.id_mesa SET m.estado = 'disponible' WHERE p.id = ?");
        $stmt_mesa->execute([$id]);

        // 4. Registrar ingreso en caja activa del usuario (si hay caja abierta)
        $stmt_caja = $pdo->prepare("SELECT id FROM caja WHERE id_usuario = ? AND abierto = 1 ORDER BY id DESC LIMIT 1");
        $stmt_caja->execute([$id_usuario]);
        $id_caja = $stmt_caja->fetchColumn();

        if ($id_caja) {
            $stmt_ing = $pdo->prepare("UPDATE caja SET ingresos = ingresos + ? WHERE id = ?");
            $stmt_ing->execute([$total, $id_caja]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Pedido cobrado exitosamente. Total: $' . number_format($total, 2)]);
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
