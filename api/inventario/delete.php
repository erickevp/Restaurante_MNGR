<?php
// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

session_start();
require_once '../../includes/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
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
        // Verificar si está en mermas (regla de negocio simple)
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM mermas WHERE id_inventario = ?");
        $stmt_check->execute([$id]);
        
        if($stmt_check->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => 'No se puede eliminar porque tiene historial de mermas']);
        } else {
            $stmt = $pdo->prepare("DELETE FROM inventario WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Artículo eliminado']);
        }
    } catch (\PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['success' => false, 'message' => 'No se puede eliminar por restricciones de integridad']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
?>
