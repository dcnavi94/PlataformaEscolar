# Changelog - Control de Pagos

## [2025-11-23 PM] - Sistema de Reportes y Branding Institucional

### Agregado
- **Sistema de Reportes Configurable**: Interfaz completamente rediseñada con 7 tipos de reportes
  - Tarjetas visuales para selección de tipo de reporte
  - Filtros dinámicos según el tipo seleccionado
  - Exportación a PDF (vía impresión) y Excel (CSV)
  - Vista previa antes de exportar
- **Branding Institucional**: Aplicados colores institucionales en toda la interfaz
  - Azul marino (#003366) como color principal
  - Naranja (#FF6600) como color de acento
  - Blanco como color base
- **Logo Institucional**: Agregado logoTransp.png en:
  - Sidebar del panel administrativo
  - Página de inicio de sesión
  
### Modificado
- **Interfaz de Login**: Rediseñada con colores institucionales y logo
- **Sidebar**: Actualizado con gradiente azul marino y efectos hover naranja
- **Botones y Badges**: Actualizados a paleta institucional
- **Dashboard**: Corregidos enlaces de accesos rápidos
- **Menú de Reportes**: Corrigió ruta para acceder correctamente

### Reportes Disponibles
1. Alumnos con Adeudos
2. Ingresos por Periodo
3. Estado de Cuenta por Grupo
4. Alumnos Becados
5. Pagos por Método
6. Reporte de Morosidad
7. Cargos Generados por Periodo

## [2025-11-23 AM] - Mejoras y Correcciones

### Agregado
- **Generación Automática de Cargos en Grupos**: Al cambiar el periodo de un grupo, el sistema ahora genera automáticamente todos los cargos de colegiatura para los meses del nuevo periodo.
- **Vista de Edición de Periodos**: Creada vista `edit.php` faltante para editar periodos académicos.
- **Migración de Base de Datos**: Script `migration_update_modalidad.sql` para actualizar valores ENUM de modalidad.

### Modificado
- **Programas Académicos**: 
  - Tipo ahora limitado a: Bachillerato y Licenciatura
  - Modalidad actualizada a: Lunes a Viernes, Sabatina, Virtual
  - Eliminado campo "Turno" de formularios y tabla
- **Base de Datos**: Columna `modalidad` en tabla `programas` actualizada con nuevos valores ENUM
- **UI de Estado de Cuenta**: Columna "Mes" ahora muestra solo el nombre del mes sin el año
- **Selector de Programas**: En editar alumno, ahora muestra "Nombre del Programa - Modalidad" para mejor identificación

### Corregido
- **Formulario de Programas**: Restaurados campos faltantes (nombre, tipo, modalidad, turno) en formularios de crear y editar
- **Error SQL**: Solucionado error de truncamiento de datos en columna modalidad al guardar "Lunes a Viernes"

### Técnico
- **GrupoController**: Agregado método privado `generateChargesForGroup()` que:
  - Calcula todos los meses entre fechas de inicio y fin del periodo
  - Genera cargos para cada alumno INSCRITO en el grupo
  - Aplica descuentos de beca automáticamente
  - Previene duplicados verificando cargos existentes
  - Utiliza configuración financiera para establecer fechas límite de pago

### Archivos Modificados
- `app/Views/admin/programas/edit.php`
- `app/Views/admin/programas/create.php`
- `app/Views/admin/programas/index.php`
- `app/Views/admin/periodos/edit.php` (NUEVO)
- `app/Views/admin/alumnos/show.php`
- `app/Views/admin/alumnos/edit.php`
- `app/Controllers/GrupoController.php`
- `schema.sql`
- `PROJECT_STATUS.md`
- `documentacion/admin_manual.md`

---

## Versiones Anteriores
Ver commits en el repositorio para historial completo.
