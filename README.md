# 🥖 Proyecto Integrador - Parvi Pan

Sistema de gestión de materias primas y solicitudes con control de inventario FEFO, alertas automáticas y dashboards dinámicos por rol.

## 📋 Requisitos

- Windows
- XAMPP instalado (Apache + MySQL)
- PHP 7.4 o superior
- Navegador web

## 🚀 Instalación

### 1. Copiar el proyecto
Copia la carpeta del proyecto a tu directorio de XAMPP:
```
C:\xampp\htdocs\proyecto-integrador
```

### 2. Iniciar servicios
Asegúrate de que Apache y MySQL estén en ejecución desde el panel de control de XAMPP.

### 3. Importar la base de datos
Abre phpMyAdmin en `http://localhost/phpmyadmin` y ejecuta en orden:

1. **Primero**: Importa el archivo `BD/parvin 2.0.sql` (estructura, triggers, procedimientos)
2. **Segundo**: Importa el archivo `BD/datos_prueba.sql` (usuarios e insumos de prueba, limpia datos previos)

> ⚠️ La base de datos se llamará `Gestionpanaderia` automáticamente.

### 4. Configurar conexión
Edita `Config/config.php` si tus credenciales MySQL son diferentes:
```php
define('DB_SERVIDOR', 'localhost');
define('DB_USER', 'root');
define('DB_CLAVE', '');
define('DB_NOMBRE', 'Gestionpanaderia');
define('BASE_URL', 'http://localhost/proyecto-integrador/');
```

### 5. Verificar .htaccess
El archivo `.htaccess` debe existir en la raíz para el enrutamiento front controller.

## 🔐 Usuarios de prueba

| Rol | Nombre | Cédula (contraseña) | Usuario (sistema) | Permisos |
|-----|--------|---------------------|-------------------|----------|
| **Gerente** | `Laura` | `123456` | `gerente` | **TODO**: usuarios, inventario, solicitudes, proveedores, lotes, movimientos, reportes |
| **Bodega** | `Carlos` | `234567` | `bodega` | Inventario, aprobar/rechazar solicitudes, lotes, proveedores, alertas |
| **Cocinero** | `Pedro` | `345678` | `cocinero` | Ver semáforo de stock, crear solicitudes, ver sus solicitudes |
| **Pastelero** | `María` | `456789` | `pastelero` | Igual que cocinero: ver stock, crear/ver solicitudes |

### Cómo iniciar sesión
1. Abre `http://localhost/proyecto-integrador/`
2. Ingresa el **nombre** de la persona (ej: `Laura`, `Pedro`, `Carlos`, `María`)
3. Ingresa la **cédula** como contraseña (ej: `123456`)
4. El sistema te redirigirá automáticamente al dashboard según tu rol

## 📊 Roles del sistema

### 🔴 Cocinero / Pastelero
- **Dashboard**: Ve semáforo de stock (crítico, atención, óptimo)
- **Solicitar insumos**: Puede crear solicitudes seleccionando insumos y cantidades
- **Mis solicitudes**: Ve el historial de sus solicitudes con estados (pendiente, aprobada, rechazada)
- **No puede**: Aprobar solicitudes, ver usuarios, gestionar inventario

### 🔵 Bodega
- **Dashboard**: KPIs de insumos, alertas, solicitudes pendientes, lotes
- **Inventario**: Semáforo de stock, vencimientos FEFO, alertas activas
- **Gestionar solicitudes**: Puede ver todas las solicitudes, aprobarlas (descuenta inventario con FEFO) o rechazarlas con motivo
- **No puede**: Ver ni gestionar usuarios

### 🟡 Gerente
- **Dashboard completo**: Todo lo de bodega más:
  - Listado de empleados con sus roles y estados
  - Análisis de mermas por insumo
  - Gestión de usuarios (crear, editar, desactivar)
  - Movimientos de inventario
  - Reportes completos del sistema
- Puede realizar **todas** las funciones anteriores

## 🗂️ Estructura del proyecto

```
proyecto-integrador/
├── BD/                    # Scripts SQL
│   ├── parvin 2.0.sql     # Estructura completa de BD
│   └── datos_prueba.sql   # Usuarios e insumos de prueba
├── Config/
│   └── config.php         # Conexión BD y constantes
├── Controller/            # Controladores MVC
│   ├── UsuarioController.php
│   ├── DashboardController.php
│   ├── SolicitudController.php
│   ├── InsumoController.php
│   ├── ProveedorController.php
│   ├── LoteController.php
│   ├── MovimientoController.php
│   └── AlertaController.php
├── Core/
│   └── BaseDatos.php      # Conexión PDO
├── Entities/              # Entidades del dominio
├── Model/                 # Modelos de acceso a datos
├── Public/                # Recursos públicos
│   ├── plantilla.html     # Template base con navbar dinámico por rol
│   ├── css/
│   ├── img/
│   └── js/
├── View/                  # Vistas PHP
│   ├── dashboard/         # Dashboards por rol
│   ├── solicitud/         # Vistas de solicitudes
│   ├── usuario/
│   ├── insumo/
│   ├── proveedor/
│   ├── lote/
│   ├── movimiento/
│   └── alerta/
├── index.php              # Front controller
└── .htaccess              # Reglas de reescritura
```

## 🔄 Flujo de trabajo

1. **Cocinero/Pastelero** detecta necesidad de materia prima
2. Crea una **solicitud** desde su dashboard seleccionando insumos y cantidades
3. La solicitud queda en estado **pendiente**
4. **Bodega** o **Gerente** revisa la solicitud en su panel
5. Puede **aprobar** (descuenta inventario automáticamente usando método FEFO - primeras en vencer, primeras en salir) o **rechazar** con motivo
6. Si se aprueba, el stock se actualiza y se registran los movimientos
7. Los triggers automáticos generan alertas si el stock baja del mínimo

## 🧩 Características técnicas

- **MVC nativo**: Controladores, modelos y vistas organizados
- **FEFO automático**: Al aprobar, consume lotes próximos a vencer primero
- **Triggers MySQL**: Auditoría de stock, alertas de stock mínimo, sincronización de inventario
- **Eventos programados**: Revisión nocturna de vencimientos (7 días)
- **Procedimientos almacenados**: Registro de entradas con transacciones ACID
- **Vistas SQL**: Semáforo de stock, inventario FEFO, análisis de mermas
- **Navbar dinámico**: Cambia según el rol del usuario autenticado
- **Protección de rutas**: Si no hay sesión, redirige al login
- **Login flexible**: Acepta nombre de la persona o nombre de usuario + cédula

## 📝 Notas

- No modificar las vistas para acceder directamente a la base de datos
- Todo acceso a datos debe hacerse desde Modelos → Controladores → Vistas
- Los triggers y eventos requieren que el motor de eventos de MySQL esté activo (`SET GLOBAL event_scheduler = ON`)
- Para debug, habilitar errores en `php.ini`: `display_errors = On`
- Si el login falla, verifica que los datos en `BD/datos_prueba.sql` estén correctamente importados