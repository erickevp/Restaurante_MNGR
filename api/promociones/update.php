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
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $descuento = $_POST['descuento'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';
    $estado = $_POST['estado'] ?? 'activo';

    if (empty($id) || empty($nombre) || strlen($descuento) === 0 || empty($fecha_inicio) || empty($fecha_fin)) {
        echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("UPDATE promociones SET nombre = ?, descripcion = ?, descuento = ?, fecha_inicio = ?, fecha_fin = ?, estado = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion, $descuento, $fecha_inicio, $fecha_fin, $estado, $id]);
        echo json_encode(['success' => true, 'message' => 'Promoción actualizada exitosamente']);
    } catch (\PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
