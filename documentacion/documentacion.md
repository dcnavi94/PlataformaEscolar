# Documento de Requerimientos – Plataforma de Control de Pagos

## 1. Contexto y Objetivo

Desarrollar una **plataforma de control de pagos escolares** para una institución educativa que ofrece:

- **Programas académicos**:
  - Bachillerato virtual:
    - Modalidad: virtual.
    - Turnos: lunes a viernes, sabatino.
  - Ingeniería en Software.
  - Ingeniería en Telemática.

- **Esquema académico (cuatrimestres)**:
  - Periodo 1: Enero – Abril.
  - Periodo 2: Mayo – Agosto.
  - Periodo 3: Septiembre – Diciembre.

- **Esquema de pagos**:
  - Pagos **mensuales**.
  - Pago de **inscripción**.
  - Pagos adicionales: uniforme, otros conceptos.
  - Penalización por pago atrasado (configurable).
  - **Becas y Descuentos**: Posibilidad de asignar un % de beca al alumno.
  - **Pagos Parciales**: Se permiten abonos a la deuda.
  - **Comisión Pasarela**: Se agrega un 4% adicional al pagar por PayPal.

### Objetivo – Primera Etapa

- Control integral de pagos:
  - Generación automática de cargos por cuatrimestre.
  - Control de pagos mensuales, inscripción y conceptos extras.
  - Cálculo y generación de recargos por penalización.
  - Panel administrativo para gestión.
  - Portal básico para alumnos con vista de sus cargos y pago en línea vía PayPal.
  - **Sistema de Notificaciones** (Correo y Alertas en sistema) para recordatorios de pago y confirmaciones.
  - Reportes financieros y de morosidad.
  - Soporte para importación de datos desde Excel.

### Objetivo – Segunda Etapa (Futuro - Definido)

- Evolucionar la plataforma a **portal escolar completo (LMS/SIS)**:
  - **Gestión Académica Avanzada**: Materias, Planes de Estudio, Carga Académica.
  - **Módulo Docente**: Captura de calificaciones y asistencia.
  - **Aula Virtual**: Enlaces a clases (Zoom/Meet), repositorio de archivos por materia.
  - **Evaluación Docente**: Encuestas de satisfacción.

---

## 2. Actores del Sistema

1. **Administrador (ADMIN)**
   - CRUD de alumnos, grupos, programas y conceptos de pago.
   - Gestión de cargos y registros de pago.
   - Configuración de parámetros financieros (día límite, penalización).
   - Consulta de reportes.
   - Gestión de importaciones desde Excel.
   - Consulta de bitácora de movimientos.

2. **Alumno**
   - Acceso al portal con credenciales propias.
   - Consulta de tabla de cargos (inscripción, colegiaturas, extras).
   - Visualización de estatus de pago: Pagado / Pendiente / Vencido / Penalización.
   - Realización de pagos en línea mediante **PayPal**.
   - Recepción de notificaciones (recordatorios de pago, confirmaciones).

3. **Docente (Etapa 2)**
   - Acceso a sus grupos asignados.
   - Captura de calificaciones parciales y finales.
   - Gestión de recursos del aula virtual.
   - Toma de asistencia.

*(Futuro)*: Roles adicionales, como Cajero, Coordinador, Rector, etc.

---

## 3. Requerimientos Funcionales (RF)

### 3.1 Autenticación y Seguridad

- **RF-01**: El sistema debe permitir el inicio de sesión para:
  - ADMIN.
  - ALUMNO.
- **RF-02**: Las contraseñas deben almacenarse encriptadas (p.ej. bcrypt).
- **RF-03**: Debe existir manejo de sesión y expiración por inactividad.
- **RF-04**: Cada rol tendrá acceso únicamente a los módulos autorizados:
  - ADMIN: módulos administrativos completos.
  - ALUMNO: portal personal de pagos.
- **RF-05**: **Bloqueo por Morosidad**:
  - Si el alumno tiene cargos vencidos (configurable, ej. > 2 meses), se bloqueará su acceso al portal o a funciones específicas.
- **RF-06**: **Recuperación de Contraseña**:
  - Solo el ADMIN puede restablecer contraseñas de alumnos.

---

### 3.2 Gestión Académica

#### 3.2.1 Programas Académicos

- **RF-10**: El ADMIN podrá realizar CRUD de **Programas**:
  - Campos mínimos:
    - id_programa.
    - nombre (ej. “Bachillerato virtual L–V”, “Ing. en Software”).
    - tipo (Bachillerato / Licenciatura / Ingeniería).
    - modalidad (virtual).
    - turno (L–V, sabatino).
    - monto_colegiatura.
    - monto_inscripcion.
    - estado (activo/inactivo).

#### 3.2.2 Periodos (Cuatrimestres)

- **RF-11**: El sistema debe manejar los **3 periodos** anuales:
  - Enero–Abril.
  - Mayo–Agosto.
  - Septiembre–Diciembre.
- **RF-12**: Cada periodo debe registrarse con:
  - nombre, fecha_inicio, fecha_fin, año, número de periodo.

#### 3.2.3 Grupos

- **RF-13**: El ADMIN podrá realizar CRUD de **Grupos**:
  - Campos:
    - id_grupo.
    - nombre (ej. “ISW-2025-1”).
    - id_programa (FK).
    - id_periodo (FK).
    - estado (activo/inactivo).
- **RF-14**: Un alumno solo puede pertenecer a **un grupo a la vez** (validación de negocio).
- **RF-15**: Vista de listado de grupos:
  - Filtros: programa, periodo, estado.
  - Acción: “Ver alumnos del grupo”.

#### 3.2.4 Alumnos

- **RF-20**: El ADMIN podrá realizar CRUD de **Alumnos**:
  - Campos mínimos:
    - id_alumno.
    - nombre.
    - apellidos.
    - correo.
    - teléfono.
    - estatus (inscrito, baja, egresado, suspendido).
    - id_programa (FK).
    - id_grupo (FK).
    - fecha_alta.
- **RF-21**: Cambio de estatus de alumno:
  - Alumnos en estatus distinto a *inscrito* no deben recibir nuevos cargos futuros.
  - Al dar de **BAJA** (Solo ADMIN), se deben cancelar los cargos pendientes futuros.
- **RF-22**: Desde la vista de un grupo, el ADMIN podrá:
  - Visualizar tabla de alumnos del grupo.
  - Acceder al detalle de pagos de cada alumno.
- **RF-23**: **Becas**:
  - Campo para asignar `porcentaje_beca` (0-100%) al alumno.
  - Este porcentaje se descuenta automáticamente al generar cargos de Colegiatura.

---

### 3.3 Catálogo de Conceptos de Pago

- **RF-30**: El ADMIN gestionará un catálogo de **Conceptos de pago**:
  - Campos:
    - id_concepto.
    - nombre (Inscripción, Colegiatura mensual, Uniforme, etc.).
    - descripción.
    - tipo (inscripción / colegiatura / extra / penalización).
    - recurrente (boolean).
    - admite_recargo (boolean).
    - estado (activo/inactivo).
- **RF-31**: Los Programas deben poder asociar montos estándar a:
  - Inscripción.
  - Colegiatura mensual.
  - Conceptos especiales (ej. uniforme).

---

### 3.4 Configuración Financiera y Penalizaciones

- **RF-40**: El ADMIN podrá configurar la **política de pagos**:
  - día_limite_pago (por ejemplo, día 7 del mes; debe ser configurable).
  - tipo_penalizacion (monto fijo / porcentaje).
  - valor_penalizacion (valor numérico).
- **RF-41**: El sistema utilizará estos parámetros para:
  - Determinar fecha límite de cada cargo mensual.
  - Calcular o generar cargos adicionales de penalización cuando aplique.

---

### 3.5 Generación Automática de Cargos

#### 3.5.1 Cargos por Cuatrimestre al Inscribir

- **RF-50**: Al inscribir a un alumno (estatus “inscrito” y asignación de programa+grupo):
  - Se generará un **cargo de inscripción** (si aplica el programa).
  - Se generarán cargos de **colegiatura mensual** para todos los meses del cuatrimestre actual.
- **RF-51**: Se permite la generación de **cargos retroactivos**:
  - Si el alumno se inscribe a mitad de cuatrimestre, se pueden generar los cargos de los meses anteriores del mismo cuatrimestre (según reglas del negocio, por defecto SÍ).

#### 3.5.2 Procedimiento Almacenado Mensual

- **RF-52**: Debe existir un procedimiento almacenado en MySQL, por ejemplo:
  - `sp_generar_cargos_mensuales(fecha_ejecucion)`
- Comportamiento:
  1. Leer configuración financiera (día límite, penalización).
  2. Para todos los alumnos con estatus **INSCRITO**:
     - Verificar cargos del cuatrimestre actual para el mes en curso.
     - Generar cargos mensuales que falten según el periodo.
  3. Para todos los cargos **pendientes** cuya fecha_límite ha sido superada:
     - Generar un nuevo cargo de tipo **penalización** (concepto de pago específico).
- **RF-53**: La ejecución del procedimiento será programada (CRON/tarea programada) en el servidor.

---

### 3.6 Gestión de Pagos

#### 3.6.1 Registro Manual de Pagos (ADMIN)

- **RF-60**: El ADMIN podrá registrar manualmente pagos:
  - Seleccionando un alumno.
  - Visualizando sus cargos (tabla).
  - Seleccionando un cargo o lista de cargos a pagar.
- **RF-61**: Datos del pago:
  - fecha_pago.
  - monto_total.
  - método_pago (efectivo, transferencia, depósito).
  - **Evidencia**: Si es transferencia/depósito, permitir subir foto/PDF del comprobante.
  - usuario_registro (ADMIN responsable).
- **RF-62**: Al registrar un pago:
  - Actualizar estatus de los cargos a **Pagado** (si se cubre el monto total).
- **RF-63**: Opción de **cancelar pagos o cargos** por error:
  - Sin borrar físicamente el registro (estatus Cancelado).
  - Registrando la acción en bitácora.

#### 3.6.2 Pagos en Línea vía PayPal (ALUMNO)

- **RF-70**: El ALUMNO tendrá una vista “Mis Pagos”:
  - Tabla con sus cargos:
    - Periodo/cuatrimestre.
    - Mes.
    - Concepto.
    - Monto.
    - Estatus (Pendiente/Pagado/Vencido/Penalización).
    - Fecha límite.
    - Fecha de pago (si aplica).
    - Botón “Pagar” cuando sea Pendiente.
- **RF-71**: Al hacer clic en “Pagar”:
  - El sistema calculará el total + **4% de comisión**.
  - El sistema creará una orden de pago para PayPal con este nuevo total.
  - Redirigirá al flujo de PayPal o abrirá una ventana modal según integración.
- **RF-72**: Al completarse el pago en PayPal:
  - Un **webhook** actualizará el estado del pago:
    - Marcando los cargos correspondientes como Pagado.
    - Guardando referencia de transacción PayPal.
- **RF-73**: Se manejarán los estados:
  - Pendiente de confirmación.
  - Exitoso.
  - Fallido.
  - Cancelado.

---

### 3.7 Sistema de Notificaciones

- **RF-75**: El sistema debe contar con un módulo de notificaciones internas y por correo electrónico.
- **RF-76**: **Notificaciones al Alumno**:
  - **Recordatorio de Pago**: 3 días antes de la fecha límite (Automático).
  - **Pago Recibido**: Confirmación inmediata tras pago exitoso (PayPal o Manual).
  - **Cargo Vencido**: Alerta cuando se genera una penalización.
- **RF-77**: **Notificaciones al ADMIN**:
  - Resumen diario de pagos recibidos (opcional).
  - Alerta de errores en webhooks de PayPal.
- **RF-78**: Centro de Notificaciones en el Portal:
  - Icono de campana en el header con contador de no leídos.
  - Listado de últimas notificaciones.

---

### 3.7 Vistas y UX – ADMIN

#### 3.7.1 Vista de Grupos y Alumnos

- **RF-80**: Vista “Grupos” (tabla):
  - Columnas: Nombre, Programa, Periodo, Año, #Alumnos, Estado, Acciones.
- **RF-81**: Al seleccionar un grupo:
  - Mostrar tabla de alumnos:
    - Nombre, apellidos, correo, estatus, acciones.
  - Opción “Ver pagos” por alumno.

#### 3.7.2 Vista de Pagos por Alumno

- **RF-82**: Vista de pagos por alumno (ADMIN):
  - Tabla por cuatrimestre o lista de cargos:
    - Mes / Periodo.
    - Concepto.
    - Monto.
    - Estatus (Pagado/Pendiente/Vencido/Penalización).
    - Fecha límite.
    - Fecha de pago.
    - Usuario que registró el pago.
    - Método de pago.
- **RF-83**: Filtros:
  - Por periodo/cuatrimestre.
  - Por estatus.
  - Por tipo de concepto (colegiatura, inscripción, extra, penalización).

---

### 3.8 Reportes

- **RF-90**: Módulo de reportes con al menos:

  1. **Relación de alumnos con pagos pendientes**:
     - Filtros: mes, cuatrimestre, grupo, programa, estatus.
     - Resultado: lista de alumnos con cargos pendientes.

  2. **Ingresos cobrados por periodo**:
     - Filtros: rango de fechas, programa, grupo, método de pago.
     - Resultado: total de ingresos, detalle por alumno y concepto.

- **RF-91**: Los reportes deben poder **exportarse a Excel (CSV/XLSX)**.
- **RF-92**: Opción futura de exportar a PDF (opcional).

---

### 3.9 Bitácora / Auditoría

- **RF-100**: Registrar en **bitácora** cada operación relevante:
  - Tablas afectadas: alumnos, grupos, programas, cargos, pagos, configuración, importaciones.
  - Acciones: INSERT, UPDATE, DELETE, LOGIN, IMPORT, CANCELACIÓN.
  - Datos mínimos:
    - id_usuario.
    - tabla_afectada.
    - id_registro.
    - acción.
    - descripción.
    - fecha_hora.
    - IP.
- **RF-101**: Módulo de consulta de bitácora para ADMIN con filtros por:
  - usuario, fecha, acción, tabla.

---

### 3.10 Importación desde Excel

- **RF-110**: El ADMIN podrá importar datos desde archivos Excel:
  - Importación de **alumnos** con una plantilla definida:
    - nombre, apellidos, correo, teléfono, programa, grupo, estatus.
- **RF-111**: El sistema debe validar los datos antes de aplicar la importación:
  - Mostrar previsualización.
  - Indicar registros con errores.
- **RF-112**: Registrará en bitácora cada importación:
  - Nombre de archivo.
  - Usuario que importó.
  - Número de registros correctos y con error.

---

### 3.11 Portal Escolar (Segunda Etapa – Especificaciones)

#### 3.11.1 Gestión Académica (Materias y Planes)
- **RF-200**: CRUD de **Materias** (Nombre, Clave, Créditos).
- **RF-201**: Gestión de **Planes de Estudio** (Malla curricular):
  - Asignación de materias por cuatrimestre a un Programa.
- **RF-202**: **Carga Académica**:
  - Asignación de materias a Grupos en un Periodo específico.
  - Asignación de **Docentes** a esas materias-grupo.

#### 3.11.2 Módulo Docente y Calificaciones
- **RF-210**: Portal para el Docente.
- **RF-211**: Captura de **Calificaciones**:
  - Parcial 1, Parcial 2, Final.
  - Cálculo automático de promedios.
  - Cierre de actas (bloqueo de edición).
- **RF-212**: Toma de **Asistencia** (diaria o por sesión).

#### 3.11.3 Aula Virtual
- **RF-220**: Por cada materia-grupo activa:
  - Espacio para publicar **Avisos**.
  - Espacio para subir **Recursos** (PDF, Word, Links).
  - Enlace permanente a videollamada (Zoom/Meet/Teams).
- **RF-221**: Entrega de tareas básica (subida de archivos por parte del alumno).

---

## 4. Requerimientos No Funcionales (RNF)

### 4.1 Tecnológicos

- **RNF-01**: Lenguaje backend: **PHP** con patrón **Modelo-Vista-Controlador (MVC)**.
- **RNF-02**: Motor de base de datos: **MySQL/MariaDB**.
- **RNF-03**: Frontend basado en **Bootstrap** (versión actual), con diseño **responsive**.
- **RNF-04**: Utilizar **Docker** para contenerización:
  - Definir `docker-compose.yml` con servicios:
    - `web`: servidor web + PHP.
    - `db`: MySQL/MariaDB.
    - (Opcional) `phpmyadmin`.
- **RNF-05**: Objetivo de despliegue: **DigitalOcean Droplet** (Linux).

### 4.2 Arquitectura y Calidad

- **RNF-10**: Estructura MVC recomendada:
  - **Modelos**: Alumno, Grupo, Programa, ConceptoPago, Cargo, Pago, Usuario, Bitacora, Configuracion, Periodo, Importacion.
  - **Controladores**: AuthController, AlumnoController, GrupoController, ProgramaController, PagoController, ReporteController, ConfiguracionController, PaypalController/WebhookController, ImportacionController.
  - **Vistas**: plantillas con Bootstrap, layout base reutilizable.
- **RNF-11**: El código debe ser modular, documentado y mantenible para soportar futuras extensiones.
- **RNF-12**: En términos de UX:
  - Navegación clara por menú.
  - Tablas con paginación, filtros y búsqueda.
  - Indicadores visuales (badges de colores) para estados de pago.

### 4.3 Seguridad y Datos

- **RNF-20**: Validación de datos en frontend y backend.
- **RNF-21**: Protección contra inyección SQL (consultas preparadas/ORM).
- **RNF-22**: Implementación de tokens CSRF en formularios críticos.
- **RNF-23**: Definir mecanismos de **backup automático** de la base de datos:
  - Respaldos periódicos.
  - Almacenamiento en ubicación segura.

---

## 5. Modelo de Datos – Propuesta de Tablas (Alto Nivel)

1. **usuarios**
   - id_usuario (PK)
   - nombre
   - correo
   - contraseña_hash
   - rol (ADMIN/ALUMNO)
   - estado

2. **alumnos**
   - id_alumno (PK)
   - id_usuario (FK, opcional)
   - nombre
   - apellidos
   - correo
   - teléfono
   - estatus
   - id_programa (FK)
   - id_grupo (FK)
   - fecha_alta

3. **programas**
   - id_programa (PK)
   - nombre
   - tipo
   - modalidad
   - turno
   - monto_colegiatura
   - monto_inscripcion
   - estado

4. **periodos**
   - id_periodo (PK)
   - nombre
   - fecha_inicio
   - fecha_fin
   - año
   - numero_periodo

5. **grupos**
   - id_grupo (PK)
   - nombre
   - id_programa (FK)
   - id_periodo (FK)
   - estado

6. **conceptos_pago**
   - id_concepto (PK)
   - nombre
   - descripcion
   - tipo (inscripción / colegiatura / extra / penalización)
   - recurrente (bool)
   - admite_recargo (bool)
   - estado

7. **configuracion_financiera**
   - id_config (PK)
   - dia_limite_pago
   - tipo_penalizacion (monto / porcentaje)
   - valor_penalizacion
   - fecha_ultima_modificacion

8. **cargos**
   - id_cargo (PK)
   - id_alumno (FK)
   - id_grupo (FK)
   - id_concepto (FK)
   - id_periodo (FK)
   - mes
   - anio
   - monto
   - estatus (Pendiente / Pagado / Vencido / Cancelado / Penalización)
   - fecha_generacion
   - fecha_limite
   - fecha_pago (nullable)
   - id_pago (FK nullable)

9. **pagos**
   - id_pago (PK)
   - id_alumno (FK)
   - fecha_pago
   - monto_total
   - metodo_pago (efectivo/transferencia/PayPal)
   - id_usuario_registro (FK usuarios)
   - referencia_externa (id transacción PayPal u otro)
   - estado

10. **pago_detalle**
    - id_pago_detalle (PK)
    - id_pago (FK)
    - id_cargo (FK)
    - monto_aplicado

11. **bitacora**
    - id_bitacora (PK)
    - id_usuario (FK)
    - tabla_afectada
    - id_registro
    - accion
    - descripcion
    - fecha_hora
    - ip

12. **importaciones**
    - id_importacion (PK)
    - tipo (alumnos/grupos/pagos)
    - archivo_nombre
    - estado
    - total_registros
    - registros_correctos
    - registros_error
    - fecha
    - id_usuario (FK)

---

## 6. Flujos Clave del Sistema

### 6.1 Flujo: Inscripción de Alumno

1. ADMIN crea o actualiza alumno:
   - Datos personales.
   - Programa.
   - Grupo.
   - Estatus: INSCRITO.
2. El sistema genera:
   - Cargo de **inscripción** (si aplica).
   - Cargos de **colegiatura mensual** para los meses del cuatrimestre actual.
   - Opcional: cargos retroactivos por meses anteriores del mismo cuatrimestre.
3. Se registra evento en **bitácora**.

### 6.2 Flujo: Generación Mensual Automática

1. CRON ejecuta `sp_generar_cargos_mensuales(fecha_ejecucion)`.
2. El procedimiento:
   - Lee configuración financiera.
   - Genera cargos mensuales para alumnos inscritos en el cuatrimestre activo.
   - Genera cargos de penalización para cargos vencidos.
3. Registra movimientos en bitácora.

### 6.3 Flujo: Pago Manual (ADMIN)

1. ADMIN selecciona alumno.
2. Visualiza tabla de cargos.
3. Selecciona uno o varios cargos pendientes.
4. Registra pago indicando:
   - Monto total.
   - Fecha.
   - Método de pago.
5. El sistema:
   - Crea registro en tabla **pagos**.
   - Crea registros en **pago_detalle**.
   - Actualiza cargos a estatus Pagado.
6. Registra operación en bitácora.

### 6.4 Flujo: Pago en Línea (ALUMNO – PayPal)

1. ALUMNO inicia sesión en portal.
2. En “Mis Pagos” ve la lista de cargos pendientes.
3. Selecciona cargo(s) y pulsa “Pagar”.
4. El sistema envía orden a PayPal (API) y redirige a la pasarela.
5. Al completar el pago, PayPal notifica vía webhook:
   - El sistema valida la transacción.
   - Marca los cargos como Pagados.
   - Crea registro en pagos y pago_detalle.
6. Se actualiza estado en la vista del alumno y se registra en bitácora.

### 6.5 Flujo: Reportes

1. ADMIN accede al módulo Reportes.
2. Elige tipo de reporte (pendientes por mes/grupo o ingresos por periodo).
3. Aplica filtros (fechas, programa, grupo, método de pago).
4. El sistema genera la lista y totales.
5. ADMIN puede exportar el resultado a Excel.


