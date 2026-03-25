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
    $nombre = $_POST['nombre'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? '';

    if (empty($id) || empty($nombre) || empty($usuario) || empty($rol)) {
        echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
        exit();
    }

    try {
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, usuario = ?, password = ?, rol = ? WHERE id = ?");
            $stmt->execute([$nombre, $usuario, $hash, $rol, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, usuario = ?, rol = ? WHERE id = ?");
            $stmt->execute([$nombre, $usuario, $rol, $id]);
        }
        
        echo json_encode(['success' => true, 'message' => 'Usuario actualizado exitosamente']);
    } catch (\PDOException $e) {
        if ($e->getCode() == 23000) { 
            echo json_encode(['success' => false, 'message' => 'El nombre de usuario ya existe']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
?>
