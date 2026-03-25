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
    $id_categoria = $_POST['id_categoria'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $estado = $_POST['estado'] ?? 'activo';

    if (empty($id_categoria) || empty($nombre) || empty($precio)) {
        echo json_encode(['success' => false, 'message' => 'Categoría, nombre y precio son obligatorios']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO menu (id_categoria, nombre, descripcion, precio, estado) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id_categoria, $nombre, $descripcion, $precio, $estado]);
        echo json_encode(['success' => true, 'message' => 'Platillo agregado al menú']);
    } catch (\PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
