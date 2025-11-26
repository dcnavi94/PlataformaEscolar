# Guía Paso a Paso para el Desarrollo del Proyecto

Esta guía detalla el proceso secuencial para construir la Plataforma de Control de Pagos.

## Fase 1: Preparación del Entorno

1.  **Estructura de Carpetas**:
    Crea la estructura de directorios base:
    ```bash
    mkdir -p app/Config app/Controllers app/Models app/Views/layouts app/Views/auth app/Views/admin app/Views/alumno app/Core public/css public/js docker/php
    ```

2.  **Configuración de Docker**:
    - Crea el archivo `docker-compose.yml` en la raíz.
    - Define los servicios `web` (Apache/PHP), `db` (MySQL) y `pma` (PhpMyAdmin).
    - Crea el `Dockerfile` en `docker/php/` para instalar extensiones como `mysqli`, `pdo_mysql`, `gd`, etc.

3.  **Inicialización**:
    - Ejecuta `docker-compose up -d` para levantar los servicios.
    - Verifica que puedes acceder a `localhost:80` (Web) y `localhost:8080` (PhpMyAdmin).

## Fase 2: Base de Datos

1.  **Script SQL**:
    - Crea un archivo `schema.sql` con todas las sentencias `CREATE TABLE` definidas en el documento de requerimientos.
    - Incluye las tablas: `usuarios`, `alumnos`, `programas`, `periodos`, `grupos`, `conceptos_pago`, `cargos`, `pagos`, `configuracion_financiera`.

2.  **Procedimientos Almacenados**:
    - Escribe y ejecuta el script para crear el SP `sp_generar_cargos_mensuales`.

3.  **Datos de Prueba**:
    - Inserta un usuario ADMIN inicial (ej. `admin@escuela.com` / `admin123`).
    - Inserta algunos catálogos de prueba (Programas, Periodos).

## Fase 3: Backend Core (PHP)

1.  **Conexión a BD**:
    - Crea la clase `app/Core/Database.php` para manejar la conexión PDO.

2.  **Enrutador (Router)**:
    - En `public/index.php`, captura la URL y despacha al controlador correspondiente.
    - Implementa un `app/Core/App.php` o `Router.php`.

3.  **Controlador Base**:
    - Crea `app/Core/Controller.php` con métodos para cargar modelos y vistas (`model()`, `view()`).

## Fase 4: Autenticación

1.  **Login**:
    - Crea `AuthController.php`.
    - Crea la vista `app/Views/auth/login.php`.
    - Implementa la lógica de validación de credenciales y creación de sesión.

2.  **Middleware**:
    - Asegura que las rutas de `/admin` verifiquen `$_SESSION['rol'] == 'ADMIN'`.

## Fase 5: Módulos Administrativos

1.  **CRUDs Básicos**:
    - Implementa controladores y vistas para:
        - Programas (`ProgramaController`)
        - Periodos (`PeriodoController`)
        - Grupos (`GrupoController`)
        - Conceptos (`ConceptoController`)

2.  **Gestión de Alumnos**:
    - Implementa el formulario de inscripción.
    - **Importante**: Al guardar un alumno nuevo, dispara la lógica para generar sus cargos iniciales (Inscripción + Colegiaturas del cuatrimestre).

## Fase 6: Módulo Financiero

1.  **Generación de Cargos**:
    - Implementa la lógica para invocar el SP `sp_generar_cargos_mensuales`.
    - Puedes crear un endpoint secreto o un script CLI para probarlo.

2.  **Registro de Pagos**:
    - Crea la vista donde el ADMIN busca un alumno y ve sus cargos.
    - Implementa el formulario para registrar un pago (seleccionar cargos -> sumar total -> guardar pago).

## Fase 7: Portal del Alumno

1.  **Vista "Mis Pagos"**:
    - Crea `AlumnoController` y la vista correspondiente.
    - Muestra la tabla de cargos filtrada por el ID del alumno logueado.

2.  **Integración PayPal**:
    - Agrega el botón de PayPal (SDK JS o Formulario Standard).
    - Configura el `return_url` y `notify_url` (Webhook).
    - Implementa `PaypalController` para procesar la confirmación del pago.

## Fase 8: Reportes y Finalización

1.  **Reportes**:
    - Crea consultas SQL para "Ingresos por mes" y "Alumnos morosos".
    - Muestra los resultados en tablas HTML.
    - Agrega un botón para exportar a Excel (puedes generar un CSV simple con headers PHP).

8.  **Pruebas Finales**:
    - Realiza un ciclo completo: Inscribir -> Generar Cargos -> Pagar (Admin y Alumno) -> Verificar Reporte.

## Fase 9: Sistema de Notificaciones

1.  **Backend**:
    - Crea `NotificationModel` y `NotificationController`.
    - Implementa método `crearNotificacion($usuario_id, $titulo, $mensaje)`.
    - Integra el envío de correos con `PHPMailer` o `mail()` nativo.

2.  **Triggers de Evento**:
    - Al generar penalización -> Crear notificación "Cargo Vencido".
    - Al recibir pago -> Crear notificación "Pago Exitoso".

3.  **Frontend**:
    - Agrega el icono de campana en el navbar (`layouts/header.php`).
    - Crea la vista de listado de notificaciones.
