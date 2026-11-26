# Fruver Panorama — Sistema de Domicilios

Aplicación web (PHP + MySQL) para la venta y entrega a domicilio de frutas, verduras y carnes.

## Estructura del proyecto

```
SISTEMA-DE-WEB-DOMICILIOS/
├── controlador/          # Lógica de la aplicación (login, registro, carrito, pagos, admin, etc.)
├── modelo/               # Conexión a la base de datos y consultas SQL
├── vista/                # Interfaz
│   ├── asset/            # Imágenes y logos
│   ├── css/              # Hojas de estilo
│   ├── html/             # Páginas (fruber.php, login, carrito, admin, etc.)
│   └── js/               # Scripts del cliente
├── fruverpanorama.sql    # Script de la base de datos
└── README.md
```

## Requisitos

- PHP 8.x
- MySQL / MariaDB
- Apache (XAMPP, Laragon, WAMP, etc.)

## Instalación

1. Copia la carpeta del proyecto dentro del directorio público del servidor:
   - XAMPP: `C:\xampp\htdocs\`
   - Laragon: `C:\laragon\www\`

2. Importa la base de datos:

   ```bash
   mysql -u root -p < fruverpanorama.sql
   ```

   O desde phpMyAdmin: **Importar** → selecciona `fruverpanorama.sql`.

3. Verifica la conexión en `modelo/conexion.php`:

   ```php
   $conn = new PDO("mysql:host=localhost; dbname=sistemasinformacion_domicilio_web", "root", "");
   ```

   Ajusta el host, el usuario y la contraseña si tu servidor lo requiere.

4. Inicia Apache y MySQL y abre:

   ```
   http://localhost/SISTEMA-DE-WEB-DOMICILIOS/vista/html/fruber.php
   ```

## Páginas principales

| Página | Descripción |
|---|---|
| `vista/html/fruber.php` | Catálogo principal (público) |
| `vista/html/login.html` | Inicio de sesión |
| `vista/html/register.html` | Registro de usuarios |
| `vista/html/carrito.php` | Carrito de compras |
| `vista/html/pago.php` | Finalizar compra |
| `vista/html/detalles_pedido.php` | Historial y detalle de pedidos |
| `vista/html/admin.php` | Panel de administración (solo rol administrador) |
| `vista/html/configuraciones.php` | Configuración de la cuenta |
| `vista/html/domicilios.php` | Panel del domiciliario |

## Roles

- **Administrador** (`id_RolFK = 1`): gestiona productos, pedidos y usuarios.
- **Domiciliario**: consulta y actualiza la entrega de pedidos.
- **Cliente**: compra, paga y revisa sus pedidos.
