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
        // Verificar si la mesa tiene pedidos activos
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM pedidos WHERE id_mesa = ? AND estado NOT IN ('pagado', 'cancelado')");
        $stmt_check->execute([$id]);
        if($stmt_check->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => 'No se puede eliminar la mesa porque tiene pedidos activos']);
            exit();
        }

        $stmt = $pdo->prepare("DELETE FROM mesas WHERE id = ?");
        $stmt->execute([$id]);
        
        if($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Mesa eliminada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'La mesa no existe']);
        }
    } catch (\PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['success' => false, 'message' => 'No se puede eliminar porque existen registros (pedidos o reservaciones) asociados a esta mesa']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
?>
