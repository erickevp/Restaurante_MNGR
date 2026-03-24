# Sistema de Gestión para Restaurante
#
# Creador: Erick Villegas - erickevp@gmail.com
# Fecha: 24/03/2026
# Versión: 1.0.0
# Licencia: GPL v3
# 

Un sistema web modular y responsivo desarrollado para la administración integral de un restaurante. Incluye control de operaciones, interfaz administrativa, y un módulo de punto de venta táctil exclusivo para meseros (tableta/móvil).

## 🚀 Tecnologías Utilizadas

- **Backend:** PHP Nativo (PDO para interacción segura con la base de datos).
- **Base de Datos:** MySQL / MariaDB.
- **Frontend:** HTML5, CSS3, JavaScript (Nativo + jQuery).
- **Framework UI:** Bootstrap 5.
- **Iconografía:** FontAwesome 6.
- **Alertas y Notificaciones:** SweetAlert2.
- **Arquitectura:** Cliente-Servidor mediante consumo de una API REST propia basada en JSON (AJAX).

## 🗂 Estructura del Proyecto

El proyecto está diseñado bajo una arquitectura modular donde las vistas (Frontend) consumen endpoints específicos del Backend (`api/`):

```text
restaurante/
├── api/                  # Endpoints del backend (Devuelve JSON puro)
│   ├── auth/             # Login, Logout y manejo de sesión
│   ├── caja/             # Turnos, ingresos, egresos, corte
│   ├── clientes/         # CRUD Clientes
│   ├── inventario/       # CRUD y Stock de Materias Primas / Insumos
│   ├── menu/             # Platillos y categorías
│   ├── mermas/           # Registro de mermas conectado al inventario
│   ├── mesas/            # Gestión física de mesas y ocupación
│   ├── pedidos/          # Creación de comandas, carga de tickets
│   ├── promociones/      # Promociones con vigencia de fechas
│   ├── reservaciones/    # Calendario de reservaciones por cliente/mesa
│   └── usuarios/         # Roles y accesos del personal
├── css/                  # Hojas de estilo personalizadas (Dashboard, Login, Punto de Venta)
├── includes/             # Archivos compartidos (db.php, header.php, footer.php)
├── js/                   # Controladores AJAX para las Vistas (1:1 con las vistas)
├── mesero/               # Módulo Táctil / Punto de Venta dedicado a Tableta
│   ├── dashboard.php     # Mapa interactivo de Mesas
│   ├── index.php         # Login táctil
│   └── pedido.php        # Interfaz de comanda y menú (Offcanvas)
├── views/                # Vistas principales de administración e interfaces
├── README.md             # Documentación principal
├── reset.php             # Archivo utilitario de soporte
└── setup_db.sql          # Script de estructura y datos iniciales de MySQL
```

## ⚙️ Instalación y Configuración

1. **Requisitos:** Servidor local (XAMPP, WAMP, Laragon, o LAMP) con PHP 8.0+ y MySQL.
2. **Base de Datos:** 
   * Entra a phpMyAdmin (o tu gestor favorito) y crea una base de datos llamada `restaurante_db`.
   * Ejecuta/Importa el archivo `setup_db.sql` incluido en la raíz del proyecto para generar todas las tablas y relaciones.
3. **Conexión:**
   * Verifica las credenciales en `includes/db.php`:
     ```php
     $host = 'localhost';
     $db   = 'restaurante_db';
     $user = 'tu_usuario'; // Ej: root
     $pass = 'tu_contraseña'; // Ej: vacío para XAMPP
     ```
4. **Ejecución:**
   * Coloca la carpeta `restaurante` dentro del directorio público de tu servidor (`htdocs`, `www`, etc).
   * Ingresa desde el navegador a: `http://localhost/restaurante/`.

## 🔑 Credenciales por Defecto

En una instalación fresca se creará automáticamente un usuario administrador predeterminado si no existiera:

- **Usuario:** `admin`
- **Contraseña:** `admin123`

*(Nota: Las contraseñas se almacenan fuertemente encriptadas usando el algoritmo BCRYPT de PHP en la base de datos).*

## 📌 Módulos Funcionales

1. **Dashboard y Autenticación:** Estadísticas en tiempo real, sesión segura, validación PDO contra inyección SQL.
2. **Personal y Roles:** Accesos jerárquicos (`admin`, `gerente`, `cajero`, `mesero`).
3. **Inventario y Mermas:** Registro de stock. Al registrar una merma esta descuenta inmediatamente de la cantidad física de insumos.
4. **Flujo de Ventas (Comandas):**
   - Panel web administrativo en `views/pedidos.php`.
   - Punto de Venta móvil en `/mesero/` (Interfaces minimalistas y táctiles).
   - Impresión de tickets dinámicos, cálculo del total automático.
5. **Caja:**
   - Control de sesiones o turnos por usuario.
   - Declaración de Fondo Inicial.
   - Egresos manuales.
   - Suma automática de pedidos 'cobrados'.
   - Corte ciego de caja al finalizar turno.

---
*Desarrollado para la optimización de flujos HORECA (Hoteles, Restaurantes y Cafeterías).*
