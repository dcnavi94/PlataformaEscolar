# Manual de Usuario

## 1. Acceso al Portal
- El alumno ingresa a `http://localhost` (o la URL del servidor).
- Inicia sesión con su **correo** y **contraseña** (asignada por el ADMIN).

## 2. Dashboard
- Muestra un resumen del estado de cuenta: **cargos pendientes**, **cargos vencidos**, **saldo total**.
- Si el alumno tiene cargos vencidos por más de 2 meses, el sistema mostrará un **mensaje de bloqueo** y deshabilitará la opción de pagar nuevos cargos.

## 3. Mis Pagos
- Tabla con todos los cargos (inscripción, colegiatura, extras).
- Cada fila muestra:
  - Periodo / Cuatrimestre
  - Mes
  - Concepto
  - Monto
  - **Estatus** (Pendiente, Parcial, Pagado, Vencido, Penalización)
  - Fecha límite
  - Botón **Pagar** (solo si el cargo está pendiente o parcial).

## 4. Pago Parcial
- Al hacer clic en **Pagar**, el alumno puede ingresar el **monto a pagar** (menor o igual al saldo pendiente).
- El sistema registra el pago como **PARCIAL** y actualiza el `saldo_pendiente`.
- Se genera una notificación de **Pago Parcial**.

## 5. Pago con PayPal
- El total a pagar incluye un **4 % de comisión** que el sistema calcula automáticamente.
- Se redirige al flujo de PayPal (sandbox o producción).
- Al completarse, PayPal envía un webhook y el portal muestra el cargo como **Pagado** y genera una notificación de **Pago Recibido**.

## 6. Subida de Evidencia (Transferencia)
- Si el método de pago es **Transferencia**, el alumno debe subir una foto o PDF del comprobante.
- El archivo se guarda en `uploads/comprobantes/` y su URL se almacena en la tabla `pagos.comprobante_url`.
- El ADMIN revisa la evidencia y marca el pago como **Pagado**.

## 7. Notificaciones
- **Recordatorio de Pago**: 3 días antes de la fecha límite.
- **Pago Recibido**: al confirmar el pago (PayPal o evidencia aprobada).
- **Cargo Vencido**: cuando se genera una penalización.
- Todas las notificaciones aparecen en el ícono de campana del header.

## 8. Cerrar Sesión
- Botón **Logout** en el menú superior.

---

*Este manual está pensado para la versión inicial del sistema. Futuras versiones podrán incluir gestión de becas, historial de pagos, y acceso a reportes.*
