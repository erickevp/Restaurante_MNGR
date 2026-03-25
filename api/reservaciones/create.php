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
    $id_cliente = $_POST['id_cliente'] ?? '';
    $id_mesa = $_POST['id_mesa'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';
    $estado = $_POST['estado'] ?? 'pendiente';

    if (empty($id_cliente) || empty($id_mesa) || empty($fecha) || empty($hora)) {
        echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
        exit();
    }

    try {
        // Verificar disponibilidad simple
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM reservaciones WHERE id_mesa = ? AND fecha = ? AND hora = ? AND estado != 'cancelada'");
        $stmt_check->execute([$id_mesa, $fecha, $hora]);
        if($stmt_check->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => 'La mesa ya está reservada en esa fecha y hora']);
            exit();
        }

        $stmt = $pdo->prepare("INSERT INTO reservaciones (id_cliente, id_mesa, fecha, hora, estado) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id_cliente, $id_mesa, $fecha, $hora, $estado]);
        echo json_encode(['success' => true, 'message' => 'Reservación guardada exitosamente']);
    } catch (\PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
