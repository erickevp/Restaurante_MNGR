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
    $numero = $_POST['numero'] ?? '';
    $capacidad = $_POST['capacidad'] ?? '';
    $estado = $_POST['estado'] ?? 'disponible';

    if (empty($numero) || empty($capacidad)) {
        echo json_encode(['success' => false, 'message' => 'Número y capacidad son obligatorios']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO mesas (numero, capacidad, estado) VALUES (?, ?, ?)");
        $stmt->execute([$numero, $capacidad, $estado]);
        echo json_encode(['success' => true, 'message' => 'Mesa creada exitosamente']);
    } catch (\PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['success' => false, 'message' => 'El número de mesa ya existe']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
?>
