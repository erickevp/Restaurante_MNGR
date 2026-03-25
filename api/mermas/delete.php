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

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
        exit();
    }

    try {
        $pdo->beginTransaction();

        // Obtener datos para restaurar inventario
        $stmt_old = $pdo->prepare("SELECT id_inventario, cantidad FROM mermas WHERE id = ?");
        $stmt_old->execute([$id]);
        $merma = $stmt_old->fetch();

        if ($merma) {
            // Restaurar inventario
            $stmt_inv = $pdo->prepare("UPDATE inventario SET cantidad = cantidad + ? WHERE id = ?");
            $stmt_inv->execute([$merma['cantidad'], $merma['id_inventario']]);

            // Eliminar merma
            $stmt_del = $pdo->prepare("DELETE FROM mermas WHERE id = ?");
            $stmt_del->execute([$id]);

            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Merma eliminada y el inventario fue restaurado']);
        } else {
            throw new \Exception("Registro de merma no encontrado");
        }
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
