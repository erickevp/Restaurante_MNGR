<?php
// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

require_once 'includes/db.php';
try {
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE usuario = 'admin'");
    $stmt->execute([$hash]);
    echo "CONTRASEÑA_RESETEADA";
} catch(Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
?>
