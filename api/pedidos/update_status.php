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
    $estado = $_POST['estado'] ?? '';

    if (empty($id) || empty($estado)) {
        echo json_encode(['success' => false, 'message' => 'Faltan parámetros']);
        exit();
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
        $stmt->execute([$estado, $id]);

        // Si se cancela, liberar la mesa
        if ($estado === 'cancelado') {
            $stmt_mesa = $pdo->prepare("UPDATE mesas m JOIN pedidos p ON m.id = p.id_mesa SET m.estado = 'disponible' WHERE p.id = ?");
            $stmt_mesa->execute([$id]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Estado actualizado']);
    } catch (\PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
