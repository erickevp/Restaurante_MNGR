<?php
// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

require_once 'includes/db.php';
try {
    echo "Conexion exitosa.\n";
    $stmt = $pdo->query("SELECT id, usuario, rol, password FROM usuarios");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($users)) {
        echo "No hay usuarios en la base de datos.\n";
        // Vamos a insertar uno por defecto
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $insert = $pdo->prepare("INSERT INTO usuarios (nombre, usuario, password, rol) VALUES ('Administrador Inicial', 'admin', ?, 'admin')");
        $insert->execute([$hash]);
        echo "Usuario admin insertado con contraseña: admin123\n";
    } else {
        print_r($users);
    }
} catch(Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
