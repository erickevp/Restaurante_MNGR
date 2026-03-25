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
    $id_inventario = $_POST['id_inventario'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';
    $motivo = $_POST['motivo'] ?? '';

    if (empty($id_inventario) || empty($cantidad) || empty($motivo)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit();
    }

    try {
        $pdo->beginTransaction();

        // Registrar merma
        $stmt = $pdo->prepare("INSERT INTO mermas (id_inventario, cantidad, motivo) VALUES (?, ?, ?)");
        $stmt->execute([$id_inventario, $cantidad, $motivo]);

        // Descontar inventario
        $stmt_inv = $pdo->prepare("UPDATE inventario SET cantidad = cantidad - ? WHERE id = ?");
        $stmt_inv->execute([$cantidad, $id_inventario]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Merma registrada e inventario actualizado']);
    } catch (\PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
