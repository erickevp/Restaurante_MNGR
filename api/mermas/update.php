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
    $id_inventario = $_POST['id_inventario'] ?? '';
    $cantidad_nueva = $_POST['cantidad'] ?? '';
    $motivo = $_POST['motivo'] ?? '';

    if (empty($id) || empty($id_inventario) || empty($cantidad_nueva) || empty($motivo)) {
        echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
        exit();
    }

    try {
        $pdo->beginTransaction();

        // Obtener la cantidad anterior
        $stmt_old = $pdo->prepare("SELECT cantidad FROM mermas WHERE id = ?");
        $stmt_old->execute([$id]);
        $cantidad_anterior = $stmt_old->fetchColumn();

        if ($cantidad_anterior === false) {
            throw new \Exception("La merma no existe");
        }

        // Diferencia a aplicar al inventario (si subió la merma, la diferencia es positiva -> restar más del inv)
        $diferencia = $cantidad_nueva - $cantidad_anterior;

        // Actualizar merma
        $stmt = $pdo->prepare("UPDATE mermas SET id_inventario = ?, cantidad = ?, motivo = ? WHERE id = ?");
        $stmt->execute([$id_inventario, $cantidad_nueva, $motivo, $id]);

        // Ajustar inventario
        $stmt_inv = $pdo->prepare("UPDATE inventario SET cantidad = cantidad - ? WHERE id = ?");
        $stmt_inv->execute([$diferencia, $id_inventario]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Merma actualizada exitosamente']);
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
