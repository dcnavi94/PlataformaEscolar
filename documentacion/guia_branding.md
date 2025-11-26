# Guía de Branding Institucional - Control de Pagos

## Paleta de Colores

### Colores Primarios

```css
--navy-blue: #003366    /* Azul Marino - Color Principal */
--orange: #FF6600       /* Naranja - Color de Acento */
--white: #FFFFFF        /* Blanco - Color Base */
--light-orange: #FF8C42 /* Naranja Claro - Hover States */
```

### Uso de Colores

#### Azul Marino (#003366)
- **Uso Principal**: Encabezados, sidebar, botones primarios
- **Aplicaciones**:
  - Fondo del sidebar
  - Títulos principales (h1, h2)
  - Botones de acción primaria
  - Badges de estado activo

#### Naranja (#FF6600)
- **Uso de Acento**: Llamadas a la acción, alertas importantes
- **Aplicaciones**:
  - Botones de advertencia
  - Efectos hover en menús
  - Bordes de elementos activos
  - Iconos destacados

#### Blanco (#FFFFFF)
- **Uso Base**: Fondos, textos sobre fondos oscuros
- **Aplicaciones**:
  - Texto en sidebar
  - Fondo de tarjetas
  - Fondo de formularios

---

## Logo Institucional

### Archivo
- **Nombre**: `logoTransp.png`
- **Ubicación**: `/public/logoTransp.png`
- **Formato**: PNG con transparencia

### Uso del Logo

#### En Sidebar
```html
<img src="<?= BASE_URL ?>/logoTransp.png" 
     alt="Logo" 
     style="max-width: 120px; height: auto;">
```

#### En Login
```html
<img src="<?= BASE_URL ?>/logoTransp.png" 
     alt="Logo Institucional" 
     style="max-width: 150px; height: auto;">
```

### Especificaciones
- **Tamaño Sidebar**: 120px de ancho máximo
- **Tamaño Login**: 150px de ancho máximo
- **Proporción**: Automática para mantener aspecto
- **Margen inferior**: 10-20px

---

## Componentes de UI

### Botones

#### Botón Primario
```css
.btn-primary {
    background-color: #003366;
    border-color: #003366;
    color: white;
}
.btn-primary:hover {
    background-color: #002244;
}
```

#### Botón Naranja
```css
.btn-warning {
    background-color: #FF6600;
    border-color: #FF6600;
    color: white;
}
.btn-warning:hover {
    background-color: #FF8C42;
}
```

### Sidebar

```css
.sidebar {
    background: linear-gradient(180deg, #003366 0%, #002244 100%);
}

.sidebar .nav-link:hover {
    background-color: rgba(255, 102, 0, 0.3);
    border-left: 3px solid #FF6600;
}
```

### Cards

```css
.card-header.bg-primary {
    background-color: #003366;
    color: white;
}

.card-header.bg-warning {
    background-color: #FF6600;
    color: white;
}
```

### Badges

```css
.badge.bg-light {
    background-color: #FF6600;
    color: white;
}
```

---

## Gradientes

### Login Background
```css
background: linear-gradient(135deg, #003366 0%, #FF6600 100%);
```

### Sidebar Background
```css
background: linear-gradient(180deg, #003366 0%, #002244 100%);
```

---

## Iconografía

### Bootstrap Icons
El sistema usa Bootstrap Icons con colores institucionales:

```html
<!-- Azul Marino -->
<i class="bi bi-icon-name" style="color: #003366;"></i>

<!-- Naranja -->
<i class="bi bi-icon-name" style="color: #FF6600;"></i>
```

### Iconos Principales por Sección
- **Dashboard**: `bi-speedometer2`
- **Alumnos**: `bi-person-badge`
- **Grupos**: `bi-people`
- **Reportes**: `bi-graph-up`
- **Pagos**: `bi-credit-card`
- **Configuración**: `bi-gear`

---

## Tipografía

### Fuentes
- **Principal**: System fonts (Arial, sans-serif)
- **Monoespaciada**: Para códigos y números

### Jerarquía
```css
h1, h2 { color: #003366; font-weight: bold; }
h3, h4 { color: #003366; }
h5, h6 { color: #666; }
```

---

## Efectos y Animaciones

### Hover en Tarjetas
```css
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(255, 102, 0, 0.3);
    border-color: #FF6600;
}
```

### Hover en Links
```css
a:hover {
    color: #FF6600;
}
```

---

## Implementación en Nuevas Vistas

### Template Base
```html
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5><i class="bi bi-icon"></i> Título</h5>
    </div>
    <div class="card-body">
        <!-- Contenido -->
        <button class="btn btn-primary">Acción Principal</button>
        <button class="btn btn-warning">Acción Secundaria</button>
    </div>
</div>
```

### CSS Variables (Usar siempre)
```css
var(--navy-blue)
var(--orange)
var(--light-orange)
var(--white)
```

---

## Checklist de Branding

Al crear nuevas vistas, verificar:

- [ ] Colores institucionales aplicados
- [ ] Logo visible (si aplica)
- [ ] Botones usan paleta correcta
- [ ] Hover states con naranja
- [ ] Cards con headers azul marino
- [ ] Iconos con colores apropiados
- [ ] Gradientes institucionales
- [ ] Sombras y efectos consistentes

---

## Archivos Principales

### CSS Global
- `app/Views/layouts/header.php` - Estilos globales

### Vistas Clave
- `app/Views/auth/login.php` - Login
- `app/Views/admin/reportes/index.php` - Reportes
- `app/Views/admin/dashboard.php` - Dashboard

---

**Última actualización:** 2025-11-23
**Versión:** 1.0
