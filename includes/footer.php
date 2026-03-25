<?php
// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3 
?>
<?php if (isset($_SESSION['usuario_id'])): ?>
    </div> <!-- Cierre del container-fluid -->
<?php endif; ?>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 para alertas atractivas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- JS Global -->
    <script src="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'js/main.js' : '../js/main.js'; ?>"></script>
    
    <?php if(isset($custom_js)): ?>
        <?php foreach($custom_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
