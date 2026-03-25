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
    $articulo = $_POST['articulo'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';
    $unidad = $_POST['unidad'] ?? '';
    $precio_unitario = $_POST['precio_unitario'] ?? '';

    if (empty($id) || empty($articulo) || strlen($cantidad) === 0 || empty($unidad) || empty($precio_unitario)) {
        echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("UPDATE inventario SET articulo = ?, cantidad = ?, unidad = ?, precio_unitario = ? WHERE id = ?");
        $stmt->execute([$articulo, $cantidad, $unidad, $precio_unitario, $id]);
        echo json_encode(['success' => true, 'message' => 'Artículo actualizado exitosamente']);
    } catch (\PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
