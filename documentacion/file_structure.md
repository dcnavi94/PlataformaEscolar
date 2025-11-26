# Estructura de Archivos del Proyecto

```
ControlPagos/
├─ app/
│  ├─ Config/
│  ├─ Controllers/
│  ├─ Models/
│  ├─ Views/
│  │   ├─ layouts/
│  │   ├─ auth/
│  │   ├─ admin/
│  │   └─ alumno/
│  └─ Core/
├─ public/
│  ├─ index.php
│  ├─ css/
│  └─ js/
├─ docker/
│  └─ php/Dockerfile
├─ docker-compose.yml
├─ schema.sql
├─ PASO_A_PASO.md
├─ documentacion.md
└─ documentacion/   <-- carpeta con la documentación generada
    ├─ file_structure.md
    ├─ procedures.md
    ├─ user_manual.md
    └─ admin_manual.md
```

- **app/**: Código fuente MVC.
- **public/**: Raíz pública del servidor web.
- **docker/**: Configuración Docker.
- **documentacion/**: Contiene la documentación detallada del proyecto.
