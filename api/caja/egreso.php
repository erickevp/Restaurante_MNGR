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
    $monto = $_POST['monto'] ?? '';
    $id_usuario = $_SESSION['usuario_id'];

    if (empty($id) || empty($monto) || $monto <= 0) {
        echo json_encode(['success' => false, 'message' => 'Monto inválido']);
        exit();
    }

    try {
        $pdo->beginTransaction();

        $stmt_check = $pdo->prepare("SELECT egresos, total_calculado FROM caja WHERE id = ? AND id_usuario = ? AND abierto = 1");
        $stmt_check->execute([$id, $id_usuario]);
        $caja = $stmt_check->fetch();

        if(!$caja) {
            throw new \Exception("Caja no encontrada o ya se cerró.");
        }

        if ($monto > $caja['total_calculado']) {
            throw new \Exception("No hay suficiente efectivo en caja para este retiro.");
        }

        $stmt = $pdo->prepare("UPDATE caja SET egresos = egresos + ?, total_calculado = total_calculado - ? WHERE id = ?");
        $stmt->execute([$monto, $monto, $id]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Retiro registrado']);
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
