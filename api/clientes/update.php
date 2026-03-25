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
    $nombre = $_POST['nombre'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $email = $_POST['email'] ?? '';

    if (empty($id) || empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'ID y nombre son obligatorios']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("UPDATE clientes SET nombre = ?, telefono = ?, email = ? WHERE id = ?");
        $stmt->execute([$nombre, $telefono, $email, $id]);
        echo json_encode(['success' => true, 'message' => 'Cliente actualizado exitosamente']);
    } catch (\PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
