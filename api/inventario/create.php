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
    $articulo = $_POST['articulo'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';
    $unidad = $_POST['unidad'] ?? '';
    $precio_unitario = $_POST['precio_unitario'] ?? '';

    if (empty($articulo) || strlen($cantidad) === 0 || empty($unidad) || empty($precio_unitario)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO inventario (articulo, cantidad, unidad, precio_unitario) VALUES (?, ?, ?, ?)");
        $stmt->execute([$articulo, $cantidad, $unidad, $precio_unitario]);
        echo json_encode(['success' => true, 'message' => 'Artículo guardado exitosamente']);
    } catch (\PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
