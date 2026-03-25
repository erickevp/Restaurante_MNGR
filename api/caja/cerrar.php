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
        echo json_encode(['success' => false, 'message' => 'Caja no identificada']);
        exit();
    }

    try {
        $stmt_check = $pdo->prepare("SELECT fondo_inicial, ingresos, egresos FROM caja WHERE id = ? AND id_usuario = ? AND abierto = 1");
        $stmt_check->execute([$id, $id_usuario]);
        $caja = $stmt_check->fetch();

        if(!$caja) {
            echo json_encode(['success' => false, 'message' => 'Caja no encontrada o ya cerrada']);
            exit();
        }

        $total = $caja['fondo_inicial'] + $caja['ingresos'] - $caja['egresos'];

        $stmt = $pdo->prepare("UPDATE caja SET fecha_cierre = CURRENT_TIMESTAMP, total_calculado = ?, abierto = 0 WHERE id = ?");
        $stmt->execute([$total, $id]);

        echo json_encode(['success' => true, 'message' => 'Corte de caja realizado']);
    } catch (\PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
