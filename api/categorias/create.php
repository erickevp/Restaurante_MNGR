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
    $nombre = $_POST['nombre'] ?? '';

    if (empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO categorias (nombre) VALUES (?)");
        $stmt->execute([$nombre]);
        echo json_encode(['success' => true, 'message' => 'Categoría creada exitosamente']);
    } catch (\PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['success' => false, 'message' => 'La categoría ya existe']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
?>
