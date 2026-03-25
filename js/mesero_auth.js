// Sistema de Gestión para Restaurante
// Creador: Erick Villegas - erickevp@gmail.com
// Fecha: 24/03/2026
// Versión: 1.0.0
// Licencia: GPL v3

$(document).ready(function () {
    $('#loginMeseroForm').on('submit', function (e) {
        e.preventDefault();

        let btn = $('#btnLogin');
        let originalText = btn.html();

        btn.html('<i class="fa-solid fa-spinner fa-spin"></i> Validando...').prop('disabled', true);

        let data = {
            usuario: $('#usuario').val(),
            password: $('#password').val()
        };

        // Asume que js/main.js define App.api, como "mesero" es otro directorio, la ruta de la API es ../api
        App.api('../api/auth/login.php', data)
            .done(function (res) {
                if (res.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Acceso Denegado',
                        text: res.message || 'Credenciales inválidas',
                        confirmButtonColor: '#4f46e5'
                    });
                    btn.html(originalText).prop('disabled', false);
                }
            })
            .fail(function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Red',
                    text: 'No se pudo contactar al servidor.',
                });
                btn.html(originalText).prop('disabled', false);
            });
    });
});
