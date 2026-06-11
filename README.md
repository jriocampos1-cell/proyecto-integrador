# Proyecto Integrador - Parvi Pan

Este proyecto es una aplicación PHP basada en el patrón MVC (Modelo-Vista-Controlador) para administrar inventario, solicitudes, proveedores, lotes y alertas en un entorno con XAMPP.

## Requisitos

- Windows
- XAMPP instalado (Apache + MySQL)
- PHP 7.4 o superior
- Navegador web

## Instalación

1. Copia la carpeta del proyecto a tu directorio de XAMPP, por ejemplo:
   - `C:\xampp\htdocs\proyecto-integrador`

2. Asegúrate de que Apache y MySQL estén en ejecución desde el panel de control de XAMPP.

3. Importa la base de datos MySQL:
   - Abre `phpMyAdmin` en `http://localhost/phpmyadmin`
   - Crea una base de datos nueva, por ejemplo `parvin`
   - Importa el archivo SQL localizado en `BD/parvin 2.0.sql`

4. Configura los datos de conexión en `Config/config.php`:
   - `DB_SERVIDOR`
   - `DB_USER`
   - `DB_CLAVE`
   - `DB_NOMBRE`

5. Verifica que la constante `BASE_URL` apunte a la ruta correcta del proyecto, por ejemplo:
   - `http://localhost/proyecto-integrador/`

6. Asegúrate de que el archivo `.htaccess` exista en la raíz del proyecto para habilitar el front controller.

## Ejecución

1. Abre el navegador y ve a la URL base del proyecto, por ejemplo:
   - `http://localhost/proyecto-integrador/`

2. Inicia sesión con un usuario existente o crea uno si tu aplicación lo permite.

3. Usa los menús para navegar entre:
   - Inventario
   - Proveedores
   - Lotes
   - Movimientos
   - Solicitudes
   - Alertas
   - Usuarios

## Estructura de carpetas

- `BD/`: Archivos de base de datos y scripts SQL.
- `Config/`: Configuración principal de la base de datos y rutas.
- `Controller/`: Controladores del proyecto.
- `Core/`: Conexión a la base de datos y utilidades centrales.
- `Entities/`: Clases de entidades del dominio.
- `Model/`: Lógica de acceso a datos y consultas.
- `Public/`: Plantillas HTML, CSS y JavaScript.
- `View/`: Vistas PHP para mostrar datos al usuario.

## Notas

- No modifiques las vistas directamente para acceder a la base de datos. El acceso a los datos debe hacerse desde los modelos y controladores.
- Si hay algún error de conexión, revisa los datos en `Config/config.php` y la importación de la base de datos.
- Para depurar problemas en PHP, revisa los mensajes de error de Apache y habilita el reporte de errores en el archivo `php.ini` si es necesario.
