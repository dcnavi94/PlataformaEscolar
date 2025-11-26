# Guía de Reportes - Control de Pagos

## Acceso al Sistema de Reportes

Navega a **Reportes** desde el menú lateral o usa el acceso rápido desde el Dashboard.

## Tipos de Reportes Disponibles

### 1. Alumnos con Adeudos
**Descripción**: Lista completa de estudiantes con cargos pendientes, vencidos o parciales.

**Filtros Disponibles**:
- Programa Académico
- Grupo
- Estatus de Pago

**Información que muestra**:
- Nombre completo del alumno
- Correo electrónico
- Programa académico
- Grupo
- Total adeudado
- Número de cargos vencidos

**Casos de uso**:
- Identificar alumnos morosos
- Seguimiento de cobranza
- Análisis de adeudos por programa

---

### 2. Ingresos por Periodo
**Descripción**: Desglose detallado de ingresos recibidos en un rango de fechas.

**Filtros Disponibles**:
- Fecha Inicio
- Fecha Fin
- Método de Pago

**Información que muestra**:
- Fecha del pago
- Método de pago utilizado
- Número de pagos
- Total recaudado

**Casos de uso**:
- Análisis financiero mensual
- Comparación de métodos de pago
- Reportes contables

---

### 3. Estado de Cuenta por Grupo
**Descripción**: Resumen de situación financiera de todos los alumnos de un grupo.

**Filtros Disponibles**:
- Grupo (requerido)
- Periodo

**Información que muestra**:
- Nombre y apellidos del alumno
- Saldo pendiente
- Total pagado

**Casos de uso**:
- Revisión grupal de pagos
- Identificar grupo con más morosidad
- Seguimiento por coordinador

---

### 4. Alumnos Becados
**Descripción**: Listado de estudiantes con becas académicas activas.

**Filtros Disponibles**:
- Programa Académico
- Porcentaje de Beca Mínimo

**Información que muestra**:
- Datos del alumno
- Programa inscrito
- Porcentaje de beca
- Monto de colegiatura base
- Descuento mensual aplicado

**Casos de uso**:
- Control de becas otorgadas
- Cálculo de subsidios
- Informes para dirección académica

---

### 5. Pagos por Método
**Descripción**: Estadísticas de uso de métodos de pago.

**Filtros Disponibles**:
- Fecha Inicio
- Fecha Fin
- Método de Pago

**Información que muestra**:
- Método de pago
- Cantidad de transacciones
- Total recaudado

**Casos de uso**:
- Análisis de preferencias de pago
- Comisiones bancarias
- Planificación financiera

---

### 6. Reporte de Morosidad
**Descripción**: Alumnos con cargos vencidos y análisis de severidad.

**Filtros Disponibles**:
- Programa Académico
- Grupo

**Información que muestra**:
- Datos del alumno
- Número de cargos vencidos
- Monto total vencido
- Fecha del primer vencimiento

**Casos de uso**:
- Acción de cobranza urgente
- Evaluación de riesgo financiero
- Bloqueo de servicios por morosidad

---

### 7. Cargos Generados por Periodo
**Descripción**: Resumen de cargos creados por periodo académico.

**Filtros Disponibles**:
- Periodo
- Programa Académico

**Información que muestra**:
- Nombre del periodo
- Programa académico
- Total de cargos generados
- Monto total acumulado

**Casos de uso**:
- Proyección de ingresos
- Verificación de generación de cargos
- Control administrativo

---

## Formatos de Exportación

### Vista Previa
- Visualización en pantalla
- Permite revisar antes de exportar
- Include estadísticas resumidas
- Botones para exportar directamente

### PDF
- Abre diálogo de impresión del navegador
- Permite "Guardar como PDF"
- Incluye fecha de generación
- Formato profesional con branding institucional

### Excel (CSV)
- Descarga archivo CSV
- Compatible con Microsoft Excel y Google Sheets
- Codificación UTF-8 con BOM
- Incluye encabezados de columna

---

## Proceso de Generación

1. **Seleccionar Tipo**: Click en la tarjeta del reporte deseado
2. **Configurar Filtros**: Ajustar parámetros según necesidad
3. **Elegir Formato**: Vista previa, PDF o Excel
4. **Generar**: Click en "Generar Reporte"
5. **Revisar/Descargar**: Según formato seleccionado

---

## Mejores Prácticas

### Reportes Financieros
- Exportar a Excel para análisis detallado
- Usar filtros de fecha para períodos específicos
- Comparar mes a mes

### Reportes de Cobranza
- Generar semanalmente
- Filtrar por grupo para seguimiento dirigido
- Exportar a PDF para enviar a coordinadores

### Reportes Administrativos
- Usar vista previa para verificación rápida
- Exportar a PDF para archivo oficial
- Generar al inicio y fin de cada periodo

---

## Colores Institucionales en Reportes

El sistema utiliza la paleta institucional:
- **Azul Marino (#003366)**: Headers y títulos
- **Naranja (#FF6600)**: Alertas y destacados
- **Blanco (#FFFFFF)**: Fondo y textos

---

**Última actualización:** 2025-11-23
