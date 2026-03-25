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
    $id_mesa = $_POST['id_mesa'] ?? '';
    $id_usuario = $_SESSION['usuario_id'];

    if (empty($id_mesa)) {
        echo json_encode(['success' => false, 'message' => 'Mesa obligatoria']);
        exit();
    }

    try {
        $pdo->beginTransaction();

        // Crear pedido
        $stmt = $pdo->prepare("INSERT INTO pedidos (id_mesa, id_usuario, total, estado) VALUES (?, ?, 0, 'pendiente')");
        $stmt->execute([$id_mesa, $id_usuario]);
        $pedido_id = $pdo->lastInsertId();

        // Cambiar estado de mesa
        $stmt_mesa = $pdo->prepare("UPDATE mesas SET estado = 'ocupada' WHERE id = ?");
        $stmt_mesa->execute([$id_mesa]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Comanda abierta', 'pedido_id' => $pedido_id]);
    } catch (\PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
