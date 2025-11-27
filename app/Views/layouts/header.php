<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Control de Pagos' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/circular-menu.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/modern-ui.css">
    <style>
        :root {
            --navy-blue: #003366;
            --orange: #FF6600;
            --light-orange: #FF8C42;
            --white: #FFFFFF;
        }
        body { background-color: #f8f9fa; }
        .sidebar { 
            min-height: 100vh; 
            background: linear-gradient(180deg, var(--navy-blue) 0%, #002244 100%); 
        }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { 
            color: white; 
            background-color: rgba(255, 102, 0, 0.3);
            border-left: 3px solid var(--orange);
        }
        .navbar-brand { font-weight: bold; }
        .notification-badge { position: relative; }
        .notification-badge .badge { position: absolute; top: -5px; right: -10px; }
        .btn-primary { background-color: var(--navy-blue); border-color: var(--navy-blue); }
        .btn-primary:hover { background-color: #002244; border-color: #002244; }
        .bg-primary { background-color: var(--navy-blue) !important; }
        .text-primary { color: var(--navy-blue) !important; }
        .btn-warning, .bg-warning { background-color: var(--orange) !important; border-color: var(--orange) !important; color: white !important; }
        .btn-warning:hover { background-color: var(--light-orange) !important; border-color: var(--light-orange) !important; }
        .badge.bg-light { background-color: var(--orange) !important; color: white !important; }
        .card-header.bg-primary { background-color: var(--navy-blue) !important; }
        .card-header.bg-warning { background-color: var(--orange) !important; }
        
        /* Collapsible menu styles */
        .nav-link[data-bs-toggle="collapse"] .bi-chevron-down {
            transition: transform 0.3s ease;
        }
        .nav-link[data-bs-toggle="collapse"][aria-expanded="false"] .bi-chevron-down {
            transform: rotate(-90deg);
        }
        .nav-link[data-bs-toggle="collapse"]:hover {
            background-color: rgba(255, 102, 0, 0.2) !important;
        }
    </style>
</head>
<body>
    <!-- Circular Modules Overlay -->
    <div class="modules-overlay" id="modulesOverlay" onclick="toggleModules()">
        <div class="modules-container p-5" onclick="event.stopPropagation()" style="max-height: 90vh; overflow-y: auto; width: 90%; max-width: 1200px; background: rgba(255,255,255,0.95); border-radius: 20px; box-shadow: 0 0 50px rgba(0,0,0,0.5);">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <h2 class="text-dark fw-bold mb-0">Menú de Módulos</h2>
                        <p class="text-muted">Acceso rápido a todas las funciones</p>
                    </div>
                </div>

                <!-- PAGOS -->
                <div class="row mb-4">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-cash-stack me-2"></i>Pagos</h5></div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/admin/dashboard" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-speedometer2 fs-2 text-primary mb-2"></i>
                                <h6 class="card-title text-dark small">Dashboard</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/alumnoadmin/index" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-people fs-2 text-success mb-2"></i>
                                <h6 class="card-title text-dark small">Alumnos</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/grupo/index" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-collection fs-2 text-info mb-2"></i>
                                <h6 class="card-title text-dark small">Grupos</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/reporte/index" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-file-earmark-bar-graph fs-2 text-warning mb-2"></i>
                                <h6 class="card-title text-dark small">Reportes</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/programa/index" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-journal-bookmark fs-2 text-danger mb-2"></i>
                                <h6 class="card-title text-dark small">Programas</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/dashboardejecutivo" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-graph-up-arrow fs-2 text-primary mb-2"></i>
                                <h6 class="card-title text-dark small">Ejecutivo</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/concepto/index" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-tags fs-2 text-secondary mb-2"></i>
                                <h6 class="card-title text-dark small">Conceptos</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/periodo/index" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar-range fs-2 text-dark mb-2"></i>
                                <h6 class="card-title text-dark small">Periodos</h6>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- ACADÉMICO -->
                <div class="row mb-4">
                    <div class="col-12"><h5 class="text-success border-bottom pb-2 mb-3"><i class="bi bi-mortarboard me-2"></i>Académico</h5></div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/materia" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-book fs-2 text-primary mb-2"></i>
                                <h6 class="card-title text-dark small">Materias</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/profesor" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-person-video3 fs-2 text-success mb-2"></i>
                                <h6 class="card-title text-dark small">Profesores</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/asignacion" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-diagram-3 fs-2 text-info mb-2"></i>
                                <h6 class="card-title text-dark small">Asignaciones</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/horario" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar3 fs-2 text-warning mb-2"></i>
                                <h6 class="card-title text-dark small">Horarios</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/calendario" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar-date fs-2 text-danger mb-2"></i>
                                <h6 class="card-title text-dark small">Calendario</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/asistencia" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-check2-all fs-2 text-primary mb-2"></i>
                                <h6 class="card-title text-dark small">Asistencia</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/bibliotecaadmin" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-book-half fs-2 text-secondary mb-2"></i>
                                <h6 class="card-title text-dark small">Biblioteca</h6>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- CONTROL ESCOLAR -->
                <div class="row mb-4">
                    <div class="col-12"><h5 class="text-warning border-bottom pb-2 mb-3"><i class="bi bi-clipboard-data me-2"></i>Control Escolar</h5></div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/controlescolar/index" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-file-earmark-text fs-2 text-primary mb-2"></i>
                                <h6 class="card-title text-dark small">Solicitudes</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/inscripcionadmin/index" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-person-plus fs-2 text-success mb-2"></i>
                                <h6 class="card-title text-dark small">Inscripciones</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/titulacionadmin/index" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-award fs-2 text-warning mb-2"></i>
                                <h6 class="card-title text-dark small">Titulación</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/titulacionadmin/egresados" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-mortarboard-fill fs-2 text-info mb-2"></i>
                                <h6 class="card-title text-dark small">Egresados</h6>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- RH -->
                <div class="row mb-4">
                    <div class="col-12"><h5 class="text-danger border-bottom pb-2 mb-3"><i class="bi bi-people me-2"></i>Recursos Humanos</h5></div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/nomina" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-cash fs-2 text-success mb-2"></i>
                                <h6 class="card-title text-dark small">Nómina</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="<?= BASE_URL ?>/nomina/horas" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-clock-history fs-2 text-warning mb-2"></i>
                                <h6 class="card-title text-dark small">Horas</h6>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- INVENTARIO & OTROS -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="text-info border-bottom pb-2 mb-3"><i class="bi bi-box-seam me-2"></i>Inventario</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="<?= BASE_URL ?>/inventario" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="bi bi-tools fs-2 text-primary mb-2"></i>
                                        <h6 class="card-title text-dark small">Inventario</h6>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="<?= BASE_URL ?>/adminprestamos" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="bi bi-arrow-left-right fs-2 text-success mb-2"></i>
                                        <h6 class="card-title text-dark small">Préstamos</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-secondary border-bottom pb-2 mb-3"><i class="bi bi-gear me-2"></i>Otros</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="<?= BASE_URL ?>/usuarioadmin/index" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="bi bi-person-badge fs-2 text-success mb-2"></i>
                                        <h6 class="card-title text-dark small">Usuarios</h6>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="<?= BASE_URL ?>/configuracion/index" class="card h-100 text-decoration-none hover-card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="bi bi-gear fs-2 text-secondary mb-2"></i>
                                        <h6 class="card-title text-dark small">Configuración</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->

            
            <!-- Windows Start Button (Circular Menu Trigger) -->
            <button id="windowsStartBtn" aria-label="Menú de módulos" aria-expanded="false" title="Menú de módulos" style="position: fixed; bottom: 20px; left: 20px; width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white; font-size: 1.8rem; cursor: pointer; z-index: 1500; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: all 0.3s ease;">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </button>


            <!-- Main Content -->
            <main class="<?= (isset($hideSidebar) && $hideSidebar) ? 'col-12' : 'col-12' ?> px-md-4">
                <!-- Top Navbar with Mega Menu -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4 shadow-sm">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        
                        <div class="collapse navbar-collapse" id="topNavbar">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <!-- Módulo de Pagos Dropdown -->
                                <li class="nav-item dropdown position-static">
                                    <a class="nav-link dropdown-toggle fw-bold text-primary" href="#" id="pagosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-cash-stack me-1"></i> PAGOS
                                    </a>
                                    <div class="dropdown-menu w-100 mt-0 border-0 shadow-lg p-3" aria-labelledby="pagosDropdown" style="border-top: 3px solid var(--navy-blue) !important;">
                                        <div class="container">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/admin/dashboard" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-speedometer2 fs-1 text-primary mb-2"></i>
                                                            <h6 class="card-title text-dark">Dashboard</h6>
                                                            <small class="text-muted">Resumen general</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/alumnoadmin/index" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-people fs-1 text-success mb-2"></i>
                                                            <h6 class="card-title text-dark">Alumnos</h6>
                                                            <small class="text-muted">Gestión de estudiantes</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/grupo/index" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-collection fs-1 text-info mb-2"></i>
                                                            <h6 class="card-title text-dark">Grupos</h6>
                                                            <small class="text-muted">Administración de grupos</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/reporte/index" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-file-earmark-bar-graph fs-1 text-warning mb-2"></i>
                                                            <h6 class="card-title text-dark">Reportes</h6>
                                                            <small class="text-muted">Informes financieros</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/programa/index" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-journal-bookmark fs-1 text-danger mb-2"></i>
                                                            <h6 class="card-title text-dark">Programas</h6>
                                                            <small class="text-muted">Planes de estudio</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/dashboardejecutivo" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-graph-up-arrow fs-1 text-primary mb-2"></i>
                                                            <h6 class="card-title text-dark">Ejecutivo</h6>
                                                            <small class="text-muted">Métricas clave</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/concepto/index" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-tags fs-1 text-secondary mb-2"></i>
                                                            <h6 class="card-title text-dark">Conceptos</h6>
                                                            <small class="text-muted">Catálogo de cobros</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/periodo/index" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-calendar-range fs-1 text-dark mb-2"></i>
                                                            <h6 class="card-title text-dark">Periodos</h6>
                                                            <small class="text-muted">Ciclos escolares</small>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <!-- Módulo Académico Dropdown -->
                                <li class="nav-item dropdown position-static">
                                    <a class="nav-link dropdown-toggle fw-bold text-success" href="#" id="academicoDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-mortarboard me-1"></i> ACADÉMICO
                                    </a>
                                    <div class="dropdown-menu w-100 mt-0 border-0 shadow-lg p-3" aria-labelledby="academicoDropdown" style="border-top: 3px solid #198754 !important;">
                                        <div class="container">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/materia" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-book fs-1 text-primary mb-2"></i>
                                                            <h6 class="card-title text-dark">Materias</h6>
                                                            <small class="text-muted">Gestión de asignaturas</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/asignacion" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-diagram-3 fs-1 text-info mb-2"></i>
                                                            <h6 class="card-title text-dark">Asignaciones</h6>
                                                            <small class="text-muted">Carga académica</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/horario" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-calendar3 fs-1 text-warning mb-2"></i>
                                                            <h6 class="card-title text-dark">Horarios</h6>
                                                            <small class="text-muted">Gestión de horarios</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/calendario" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-calendar-date fs-1 text-danger mb-2"></i>
                                                            <h6 class="card-title text-dark">Calendario</h6>
                                                            <small class="text-muted">Eventos escolares</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/asistencia" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-check2-all fs-1 text-primary mb-2"></i>
                                                            <h6 class="card-title text-dark">Asistencia</h6>
                                                            <small class="text-muted">Control de asistencia</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/bibliotecaadmin" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-book-half fs-1 text-secondary mb-2"></i>
                                                            <h6 class="card-title text-dark">Biblioteca</h6>
                                                            <small class="text-muted">Recursos bibliográficos</small>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <!-- Módulo Control Escolar Dropdown -->
                                <li class="nav-item dropdown position-static">
                                    <a class="nav-link dropdown-toggle fw-bold text-warning" href="#" id="controlDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-clipboard-data me-1"></i> CONTROL ESCOLAR
                                    </a>
                                    <div class="dropdown-menu w-100 mt-0 border-0 shadow-lg p-3" aria-labelledby="controlDropdown" style="border-top: 3px solid #ffc107 !important;">
                                        <div class="container">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/controlescolar/index" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-file-earmark-text fs-1 text-primary mb-2"></i>
                                                            <h6 class="card-title text-dark">Solicitudes</h6>
                                                            <small class="text-muted">Trámites y servicios</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/inscripcionadmin/index" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-person-plus fs-1 text-success mb-2"></i>
                                                            <h6 class="card-title text-dark">Inscripciones</h6>
                                                            <small class="text-muted">Nuevos ingresos</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/titulacionadmin/index" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-award fs-1 text-warning mb-2"></i>
                                                            <h6 class="card-title text-dark">Titulación</h6>
                                                            <small class="text-muted">Procesos de grado</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/titulacionadmin/egresados" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-mortarboard-fill fs-1 text-info mb-2"></i>
                                                            <h6 class="card-title text-dark">Egresados</h6>
                                                            <small class="text-muted">Base de datos egresados</small>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <!-- Módulo Recursos Humanos Dropdown -->
                                <li class="nav-item dropdown position-static">
                                    <a class="nav-link dropdown-toggle fw-bold text-danger" href="#" id="rhDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-people me-1"></i> RH
                                    </a>
                                    <div class="dropdown-menu w-100 mt-0 border-0 shadow-lg p-3" aria-labelledby="rhDropdown" style="border-top: 3px solid #dc3545 !important;">
                                        <div class="container">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/profesor" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-person-badge fs-1 text-primary mb-2"></i>
                                                            <h6 class="card-title text-dark">Profesores</h6>
                                                            <small class="text-muted">Gestión de personal</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/nomina" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-cash fs-1 text-success mb-2"></i>
                                                            <h6 class="card-title text-dark">Nómina</h6>
                                                            <small class="text-muted">Pagos a personal</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/nomina/horas" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-clock-history fs-1 text-warning mb-2"></i>
                                                            <h6 class="card-title text-dark">Horas</h6>
                                                            <small class="text-muted">Registro de horas</small>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <!-- Módulo Inventario Dropdown -->
                                <li class="nav-item dropdown position-static">
                                    <a class="nav-link dropdown-toggle fw-bold text-info" href="#" id="inventarioDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-box-seam me-1"></i> INVENTARIO
                                    </a>
                                    <div class="dropdown-menu w-100 mt-0 border-0 shadow-lg p-3" aria-labelledby="inventarioDropdown" style="border-top: 3px solid #0dcaf0 !important;">
                                        <div class="container">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/inventario" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-tools fs-1 text-primary mb-2"></i>
                                                            <h6 class="card-title text-dark">Inventario</h6>
                                                            <small class="text-muted">Gestión de activos</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/adminprestamos" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-arrow-left-right fs-1 text-success mb-2"></i>
                                                            <h6 class="card-title text-dark">Préstamos</h6>
                                                            <small class="text-muted">Control de préstamos</small>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <!-- Módulo OTROS Dropdown -->
                                <li class="nav-item dropdown position-static">
                                    <a class="nav-link dropdown-toggle fw-bold text-secondary" href="#" id="otrosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear me-1"></i> OTROS
                                    </a>
                                    <div class="dropdown-menu w-100 mt-0 border-0 shadow-lg p-3" aria-labelledby="otrosDropdown" style="border-top: 3px solid #6c757d !important;">
                                        <div class="container">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/usuarioadmin/index" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-person-badge fs-1 text-success mb-2"></i>
                                                            <h6 class="card-title text-dark">Usuarios</h6>
                                                            <small class="text-muted">Gestión de usuarios</small>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="<?= BASE_URL ?>/configuracion/index" class="card h-100 text-decoration-none hover-card">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-gear fs-1 text-secondary mb-2"></i>
                                                            <h6 class="card-title text-dark">Configuración</h6>
                                                            <small class="text-muted">Ajustes del sistema</small>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            
                            <!-- User Profile & Notifications -->
                            <div class="d-flex align-items-center ms-auto">
                                <span class="text-muted me-3 d-none d-lg-block">
                                    <i class="bi bi-person-circle"></i> <?= $_SESSION['nombre'] ?? 'Usuario' ?>
                                </span>
                                <button class="btn btn-link notification-badge position-relative text-secondary" disabled>
                                    <i class="bi bi-bell fs-5"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                        0
                                    </span>
                                </button>
                                <a href="<?= BASE_URL ?>/auth/logout" class="btn btn-outline-danger btn-sm ms-3">
                                    <i class="bi bi-box-arrow-right"></i> Salir
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>
                
                <!-- Flash Messages -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $_SESSION['error'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $_SESSION['success'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <!-- Content -->
