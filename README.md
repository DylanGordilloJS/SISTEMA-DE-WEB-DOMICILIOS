# 🍎 Fruver Panorama — Sistema de Información de Domicilios Web

Plataforma web diseñada para optimizar y automatizar la gestión de pedidos y entregas a domicilio del establecimiento **Fruver Panorama** 

---

## 🌐 Enlaces del Proyecto

* **🚀 Aplicación Desplegada (Demo en vivo):** [https://fruverpanorama.infinityfreeapp.com/vista/html/login.html](https://fruverpanorama.infinityfreeapp.com/vista/html/login.html)
* **📖 Manual de Usuario:** [Ver Manual en Google Drive](https://drive.google.com/file/d/1LnwNWP8uuQGUOLQik6gym-LRNwLRp0wl/view?usp=sharing)

---

## 🎯 Problema que Soluciona

Anteriormente, **Fruver Panorama** gestionaba sus pedidos a domicilio de manera completamente manual mediante llamadas telefónicas y mensajes de WhatsApp, lo que ocasionaba:

* **Errores humanos y reprocesos** al transcribir manualmente productos, cantidades y direcciones.
* **Falta de trazabilidad y control**, sin una base de datos centralizada para registrar clientes, historial de compras o estados de entrega.
* **Demoras en el despacho** y desorganización en la asignación de rutas para los domiciliarios.
* **Limitada visibilidad comercial**, impidiendo consultar reportes de ventas o identificar los productos más solicitados.

### 💡 La Solución
Este sistema centraliza todo el flujo de domicilios en una aplicación web bajo arquitectura **MVC (Modelo-Vista-Controlador)**, conectando en tiempo real a **Clientes**, **Administradores** y **Domiciliarios** desde la selección en el catálogo hasta la confirmación de entrega.

---

## 👥 Roles y Funcionalidades Principales

### 🛒 Cliente
* Registro e inicio de sesión con validación de credenciales.
* Exploración de catálogo visual de productos (frutas, verduras, carnes, etc.).
* Carrito de compras interactivo con cálculo automático de subtotales y total a pagar.
* Confirmación de domicilio (dirección de entrega, teléfono y método de pago contra entrega).
* Seguimiento en tiempo real del estado del pedido (`Pendiente`, `En camino`, `Entregado`, `Cancelado`) y opción de cancelación mientras esté pendiente.

### 🛠️ Administrador
* Gestión integral de usuarios (crear, consultar, actualizar e inactivar cuentas).
* Administración de productos del catálogo (añadir, editar precios y eliminar artículos).
* Supervisión global de pedidos y domicilios registrados en el sistema.

### 🛵 Domiciliario
* Panel de control con métricas rápidas (pedidos pendientes, en camino, entregados hoy y total recaudado).
* Consulta de pedidos asignados con datos de contacto y dirección exacta del cliente.
* Actualización del estado del trayecto (`En camino` / `Entregado`) con notificación al cliente.

---

## 🛠️ Stack Tecnológico

* **Frontend:** HTML5, CSS3, JavaScript.
* **Backend:** PHP (Arquitectura Modelo-Vista-Controlador - MVC).
* **Base de Datos:** MySQL (`sistemasinformacion_domicilio_web`).
* **Seguridad:** Cifrado de contraseñas mediante `password_hash()` de PHP y control de acceso basado en roles.
* **Servidor / Despliegue:** Apache / InfinityFree.

---

## 🗄️ Estructura de la Base de Datos

El sistema opera sobre un modelo relacional compuesto por 6 tablas principales:
1. `Rol`: Define los perfiles de acceso (`Administrador`, `Cliente`, `Domiciliario`).
2. `Usuario`: Almacena datos personales, credenciales cifradas y dirección base.
3. `domiciliario`: Extiende la información del usuario repartidor y su vehículo asignado.
4. `Producto`: Catálogo de artículos disponibles y sus precios unitarios.
5. `Pedido`: Registra la cabecera de cada orden, dirección de destino, estado y total.
6. `Detalle_Pedido`: Desglose de productos, cantidades y precios por cada pedido.

---

## ⚙️ Instalación y Despliegue Local

Si deseas ejecutar el proyecto en un entorno local (como XAMPP, WAMP o Laragon):

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/DylanGordilloJS/SISTEMA-DE-WEB-DOMICILIOS.git
   ```
2. **Configurar la base de datos:**
   * Inicia los servicios de **Apache** y **MySQL**.
   * Crea la base de datos ejecutando el script SQL del proyecto (`CREATE DATABASE sistemasinformacion_domicilio_web;`) e importa las tablas (`Rol`, `Usuario`, `domiciliario`, `Pedido`, `Producto`, `Detalle_Pedido`).
3. **Configurar la conexión:**
   * Verifica las credenciales de conexión a MySQL (`host`, `usuario`, `contraseña` y `nombre de BD`) en el archivo de configuración del modelo.
4. **Ejecutar:**
   * Ubica la carpeta del proyecto dentro del directorio raíz de tu servidor local (ej. `htdocs/`) y accede desde tu navegador a `http://localhost/<nombre-carpeta>/vista/html/login.html`.