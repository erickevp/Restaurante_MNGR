<?php
// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

session_start();
require_once '../../includes/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pedido = $_POST['id_pedido'] ?? '';
    $id_menu = $_POST['id_menu'] ?? '';
    $cantidad = $_POST['cantidad'] ?? 1;

    if (empty($id_pedido) || empty($id_menu)) {
        echo json_encode(['success' => false, 'message' => 'Faltan parámetros']);
        exit();
    }

    try {
        $pdo->beginTransaction();

        // Obtener precio del articulo
        $stmt_menu = $pdo->prepare("SELECT precio FROM menu WHERE id = ?");
        $stmt_menu->execute([$id_menu]);
        $precio = $stmt_menu->fetchColumn();

        if ($precio === false) {
            throw new \Exception("Articulo no encontrado");
        }

        // Check if item already exists in the same order
        $stmt_check = $pdo->prepare("SELECT id, cantidad FROM detalle_pedido WHERE id_pedido = ? AND id_menu = ?");
        $stmt_check->execute([$id_pedido, $id_menu]);
        $existente = $stmt_check->fetch();

        if ($existente) {
            // Update quantity
            $stmt = $pdo->prepare("UPDATE detalle_pedido SET cantidad = cantidad + ? WHERE id = ?");
            $stmt->execute([$cantidad, $existente['id']]);
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO detalle_pedido (id_pedido, id_menu, cantidad, precio) VALUES (?, ?, ?, ?)");
            $stmt->execute([$id_pedido, $id_menu, $cantidad, $precio]);
        }

        // Actualizar total del pedido
        $total_add = $precio * $cantidad;
        $stmt_total = $pdo->prepare("UPDATE pedidos SET total = total + ? WHERE id = ?");
        $stmt_total->execute([$total_add, $id_pedido]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Artículo agregado']);
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
