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
    $fondo = $_POST['fondo'] ?? 0;
    $id_usuario = $_SESSION['usuario_id'];

    if ($fondo < 0) {
        echo json_encode(['success' => false, 'message' => 'El fondo no puede ser negativo']);
        exit();
    }

    try {
        // Verificar que no haya una caja abierta para este usuario
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM caja WHERE id_usuario = ? AND abierto = 1");
        $stmt_check->execute([$id_usuario]);
        if($stmt_check->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => 'Ya tienes una caja abierta. Realiza el corte antes de abrir otra.']);
            exit();
        }

        $stmt = $pdo->prepare("INSERT INTO caja (id_usuario, fondo_inicial, ingresos, egresos, total_calculado, abierto) VALUES (?, ?, 0, 0, ?, 1)");
        $stmt->execute([$id_usuario, $fondo, $fondo]);
        
        echo json_encode(['success' => true, 'message' => 'Turno de caja abierto']);
    } catch (\PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
