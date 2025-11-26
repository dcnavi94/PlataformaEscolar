# Manual de Administrador

## 1. Acceso al Panel Administrativo
- Ingresa a `http://localhost` con tus credenciales de ADMIN.
- Credenciales por defecto: `admin@escuela.com` / `admin123`

## 2. Gestión de Usuarios y Alumnos

### 2.1 Crear Usuario Alumno
1. Ir a **Alumnos** > **Nuevo Alumno**
2. Completar el formulario:
   - Datos personales (nombre, apellidos, correo, teléfono)
   - Asignar **Programa** y **Grupo**
   - **Porcentaje de Beca** (0-100%)
   - Estatus: INSCRITO
3. Al guardar, se genera automáticamente:
   - Usuario con correo y contraseña temporal
   - Cargo de **Inscripción**
   - Cargos de **Colegiatura** para todos los meses del cuatrimestre

### 2.2 Aplicar Becas
- El campo `porcentaje_beca` se aplica automáticamente al generar los cargos mensuales.
- Ejemplo: Beca del 50% → Colegiatura de $2000 se registra como $1000.

### 2.3 Dar de Baja a un Alumno
1. Editar el alumno y cambiar estatus a **BAJA**
2. El sistema **cancela automáticamente** todos los cargos pendientes futuros.
3. Los cargos ya pagados no se ven afectados.

### 2.4 Restablecer Contraseña
- Solo el ADMIN puede cambiar contraseñas.
- Ir a **Detalles del Alumno** > **Restablecer Contraseña**.

## 3. Gestión de Programas, Periodos y Grupos

### 3.1 Programas
- **Crear/Editar Programas**: 
  - **Tipo**: Bachillerato o Licenciatura
  - **Modalidad**: Lunes a Viernes, Sabatina, o Virtual
  - **Montos**: Colegiatura e inscripción mensuales
- Los montos se aplicarán a todos los alumnos inscritos a ese programa.
- El campo "Turno" ha sido eliminado en favor de la modalidad.

### 3.2 Periodos
- Definir los 3 cuatrimestres: Enero-Abril, Mayo-Agosto, Septiembre-Diciembre.
- Cada periodo tiene fechas de inicio y fin.
- Los periodos se pueden crear y editar desde el panel admin.

### 3.3 Grupos
- Crear grupos asociados a un Programa y Periodo.
- Cada alumno solo puede pertenecer a un grupo activo.
- **⭐ NUEVO: Generación Automática de Cargos**
  - Al **editar un grupo y cambiar su periodo**, el sistema:
    1. Detecta automáticamente el cambio de periodo
    2. Genera cargos de colegiatura para TODOS los meses del nuevo periodo
    3. Aplica automáticamente las becas de cada alumno
    4. Evita duplicados verificando cargos existentes
    5. Muestra mensaje: "Grupo actualizado y cargos generados exitosamente"
  - Esto facilita la transición de grupos entre cuatrimestres sin necesidad de generar cargos manualmente.

## 4. Configuración Financiera
1. Ir a **Configuración** > **Parámetros Financieros**
2. Configurar:
   - **Día límite de pago** (ej. día 10 de cada mes)
   - **Tipo de penalización**: Monto Fijo o Porcentaje
   - **Valor de penalización** (ej. $150 o 10%)

## 5. Gestión de Cargos y Pagos

### 5.1 Ver Estado de Cuenta de un Alumno
1. Ir a **Grupos** > Seleccionar grupo
2. Click en **Ver pagos** del alumno
3. Se muestra tabla con:
   - Concepto, Monto, Saldo Pendiente
   - Estatus (PENDIENTE, PARCIAL, PAGADO, VENCIDO, PENALIZACION)
   - Fecha límite

### 5.2 Registrar Pago Manual
1. Desde el estado de cuenta, click en **Registrar Pago**
2. Seleccionar cargos a pagar
3. Ingresar:
   - **Monto** (puede ser parcial)
   - **Método de pago**: Efectivo, Transferencia, PayPal
   - **Comprobante** (obligatorio para transferencia): subir foto/PDF
4. El sistema actualiza:
   - `saldo_pendiente` del cargo
   - Estatus: PARCIAL (si no cubre el total) o PAGADO (si cubre el total)

### 5.3 Pagos Parciales
- Si un alumno paga $500 de una colegiatura de $2000:
  - `saldo_pendiente` = $1500
  - Estatus = PARCIAL
- Puede seguir abonando hasta completar el total.

### 5.4 Revisión de Evidencias de Transferencia
1. Ir a **Pagos** > **Pendientes de Revisión**
2. Ver el comprobante subido por el alumno
3. Aprobar o Rechazar:
   - **Aprobar**: Cambia estatus a PAGADO
   - **Rechazar**: Solicita al alumno volver a subir

## 6. Generación Automática de Cargos

### 6.1 Ejecución del Cron
- El sistema ejecuta diariamente (configurar cron en el servidor):
  ```bash
  0 0 * * * php /var/www/html/app/Console/GenerateMonthlyCharges.php
  ```
- El script llama al procedimiento `sp_generar_cargos_mensuales`.

### 6.2 Lógica del Procedimiento
1. **Genera cargos** del mes actual para todos los alumnos INSCRITOS.
2. **Genera penalizaciones** para cargos vencidos (cuya fecha límite ya pasó).
3. **Envía notificaciones** a los alumnos afectados.

## 7. Sistema de Notificaciones

### 7.1 Tipos de Notificaciones Automáticas
- **Recordatorio de Pago**: 3 días antes de la fecha límite.
- **Pago Recibido**: Al confirmar un pago (manual o PayPal).
- **Cargo Vencido**: Al generar una penalización.

### 7.2 Configuración SMTP
- Editar `.env`:
  ```env
  SMTP_HOST=smtp.gmail.com
  SMTP_PORT=587
  SMTP_USER=tuemail@gmail.com
  SMTP_PASS=tu_contraseña_app
  ```
- Usar "Contraseñas de aplicación" en Gmail (no la contraseña normal).

### 7.3 Notificaciones al Admin
- Resumen diario de pagos recibidos (opcional, configurar en Ajustes).
- Alerta de errores en webhook de PayPal.

## 8. Integración con PayPal

### 8.1 Configuración
1. Crear cuenta en [PayPal Developer](https://developer.paypal.com/)
2. Obtener **Client ID** y **Secret** del sandbox.
3. Configurar en `.env`:
   ```env
   PAYPAL_MODE=sandbox
   PAYPAL_CLIENT_ID=tu_client_id
   PAYPAL_SECRET=tu_secret
   ```

### 8.2 Webhook
- URL del webhook: `https://tu-dominio.com/paypal/webhook.php`
- Configurar en el dashboard de PayPal:
  - Eventos: `PAYMENT.CAPTURE.COMPLETED`, `PAYMENT.CAPTURE.DENIED`
- El webhook valida la firma y actualiza el estatus del pago automáticamente.

### 8.3 Comisión del 4%
- Los alumnos ven el **total  + 4%** al pagar por PayPal.
- Ejemplo: Colegiatura $2000 → Total a pagar $2080.

## 9. Bloqueo por Morosidad
- Si un alumno tiene cargos vencidos por más de 2 meses (configurable), se bloquea su acceso al portal.
- Mensaje: "Tu cuenta está bloqueada por morosidad. Contacta al administrador".
- Para desbloquear: El ADMIN debe registrar los pagos pendientes.

## 10. Reportes

### 10.1 Alumnos con Pagos Pendientes
- Filtros: Mes, Cuatrimestre, Programa, Grupo, Estatus.
- Muestra: Nombre, Cargos pendientes, Total adeudado.

### 10.2 Ingresos por Periodo
- Filtros: Rango de fechas, Programa, Método de pago.
- Muestra: Total de ingresos, detalle por alumno y concepto.

### 10.3 Exportar a Excel
- Botón "Exportar a Excel" en cada reporte.
- Genera archivo CSV descargable.

## 11. Importación de Alumnos desde Excel

### 11.1 Plantilla
- Descargar plantilla desde **Alumnos** > **Importar**.
- Columnas: Nombre, Apellidos, Correo, Teléfono, Programa, Grupo, Estatus, Beca%.

### 11.2 Proceso
1. Subir archivo Excel/CSV.
2. El sistema valida los datos y muestra previsualización.
3. Indicar registros con errores (correo duplicado, programa inexistente, etc.).
4. Confirmar importación.
5. Se crean los alumnos y se generan sus cargos iniciales.

## 12. Bitácora de Movimientos
- Ver historial completo de acciones realizadas en el sistema.
- Filtros: Usuario, Tabla, Acción, Rango de fechas.
- Útil para auditoría y resolución de problemas.

## 13. Mejoras de Interfaz

### 13.1 Selector de Programas en Alumnos
- Al editar un alumno, el selector de programa muestra: **"Nombre - Modalidad"**
- Ejemplo: "Licenciatura en Administración - Lunes a Viernes"
- Facilita la identificación del programa correcto.

### 13.2 Estado de Cuenta
- La columna "Mes" ahora muestra solo el nombre del mes (Enero, Febrero, etc.)
- El año se omite para mayor claridad visual
- El periodo completo se muestra en la columna "Periodo"

---

**Nota**: Este manual cubre las funcionalidades de la Etapa 1. La Etapa 2 incluirá módulos académicos, gestión de calificaciones y aula virtual.

**Última actualización:** 2025-11-23 20:30
