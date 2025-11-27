<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Control de Pagos' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php if (!isset($hideSidebar) || !$hideSidebar): ?>
            <nav class="col-md-2 d-md-block sidebar px-0">
                <div class="position-sticky pt-3">
                    <div class="text-center text-white mb-4">
                        <img src="<?= BASE_URL ?>/logoTransp.png" alt="Logo" style="max-width: 120px; height: auto; margin-bottom: 10px;">
                        <h5>Control de Pagos</h5>
                        <small><?= $_SESSION['nombre'] ?? '' ?></small><br>
                        <span class="badge bg-light text-dark mt-2"><?= $_SESSION['rol'] ?? '' ?></span>
                    </div>
                    
                    <ul class="nav flex-column">
                        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'ADMIN'): ?>
                        <!-- MÓDULO DE PAGOS -->
                        <li class="nav-item">
                            <a class="nav-link text-white fw-bold d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#moduloPagos" role="button" aria-expanded="true" aria-controls="moduloPagos" style="cursor: pointer;">
                                <span><i class="bi bi-cash-stack me-2"></i> MÓDULO DE PAGOS</span>
                                <i class="bi bi-chevron-down"></i>
                            </a>
                        </li>
                        <div class="collapse show" id="moduloPagos">
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/dashboard">
                                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'alumnoadmin') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/alumnoadmin/index">
                                    <i class="bi bi-people me-2"></i> Alumnos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'grupo') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/grupo/index">
                                    <i class="bi bi-collection me-2"></i> Grupos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'pago') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/pago/historial">
                                    <i class="bi bi-credit-card me-2"></i> Pagos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'programa') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/programa/index">
                                    <i class="bi bi-journal-bookmark me-2"></i> Programas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'dashboardejecutivo') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/dashboardejecutivo">
                                    <i class="bi bi-graph-up-arrow me-2"></i> Dashboard Ejecutivo
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'concepto') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/concepto/index">
                                    <i class="bi bi-tags me-2"></i> Conceptos de Pago
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'periodo') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/periodo/index">
                                    <i class="bi bi-calendar-range me-2"></i> Periodos
                                </a>
                            </li>
                        </div>

                        <!-- MÓDULO ACADÉMICO -->
                        <li class="nav-item mt-3">
                            <a class="nav-link text-white fw-bold d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#moduloAcademico" role="button" aria-expanded="true" aria-controls="moduloAcademico" style="cursor: pointer;">
                                <span><i class="bi bi-mortarboard me-2"></i> MÓDULO ACADÉMICO</span>
                                <i class="bi bi-chevron-down"></i>
                            </a>
                        </li>
                        <div class="collapse show" id="moduloAcademico">
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'materia') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/materia">
                                    <i class="bi bi-book me-2"></i> Materias
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'profesor') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/profesor">
                                    <i class="bi bi-person-video3 me-2"></i> Profesores
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'asignacion') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/asignacion">
                                    <i class="bi bi-diagram-3 me-2"></i> Asignaciones
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'horario') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/horario">
                                    <i class="bi bi-calendar3 me-2"></i> Horarios
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'calendario') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/calendario">
                                    <i class="bi bi-calendar-date me-2"></i> Calendario Escolar
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'asistencia') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/asistencia">
                                    <i class="bi bi-check2-all me-2"></i> Reportes de Asistencia
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'bibliotecaadmin') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/bibliotecaadmin">
                                    <i class="bi bi-book-half me-2"></i> Biblioteca Digital
                                </a>
                            </li>
                        </div>

                        <!-- MÓDULO INVENTARIO -->
                        <li class="nav-item mt-3">
                            <a class="nav-link text-white fw-bold d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#moduloInventario" role="button" aria-expanded="true" aria-controls="moduloInventario" style="cursor: pointer;">
                                <span><i class="bi bi-box-seam me-2"></i> MÓDULO INVENTARIO</span>
                                <i class="bi bi-chevron-down"></i>
                            </a>
                        </li>
                        <div class="collapse show" id="moduloInventario">
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'inventario') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/inventario">
                                    <i class="bi bi-tools me-2"></i> Materiales
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'adminprestamos') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/adminprestamos">
                                    <i class="bi bi-arrow-left-right me-2"></i> Préstamos
                                </a>
                            </li>
                        </div>

                        <!-- RECURSOS HUMANOS -->
                        <li class="nav-item mt-3">
                            <a class="nav-link text-white fw-bold d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#moduloRH" role="button" aria-expanded="true" aria-controls="moduloRH" style="cursor: pointer;">
                                <span><i class="bi bi-people-fill me-2"></i> RECURSOS HUMANOS</span>
                                <i class="bi bi-chevron-down"></i>
                            </a>
                        </li>
                        <div class="collapse show" id="moduloRH">
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'nomina') !== false && strpos($_SERVER['REQUEST_URI'], 'horas') === false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/nomina">
                                    <i class="bi bi-cash-coin me-2"></i> Gestión de Pagos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'horas') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/nomina/horas">
                                    <i class="bi bi-clock-history me-2"></i> Registro de Horas
                                </a>
                            </li>
                        </div>

                        <!-- SERVICIOS -->
                        <li class="nav-item mt-3">
                            <a class="nav-link text-white fw-bold d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#moduloServicios" role="button" aria-expanded="true" aria-controls="moduloServicios" style="cursor: pointer;">
                                <span><i class="bi bi-briefcase me-2"></i> SERVICIOS</span>
                                <i class="bi bi-chevron-down"></i>
                            </a>
                        </li>
                        <div class="collapse show" id="moduloServicios">
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'titulacionadmin') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/titulacionadmin/index">
                                    <i class="bi bi-mortarboard-fill me-2"></i> Titulación
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'inscripcionadmin') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/inscripcionadmin">
                                    <i class="bi bi-person-plus-fill me-2"></i> Inscripciones
                                </a>
                            </li>
                        </div>
                        
                        <!-- OTRO -->
                        <li class="nav-item mt-3">
                            <a class="nav-link text-white fw-bold d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#moduloOtro" role="button" aria-expanded="true" aria-controls="moduloOtro" style="cursor: pointer;">
                                <span><i class="bi bi-gear-fill me-2"></i> OTRO</span>
                                <i class="bi bi-chevron-down"></i>
                            </a>
                        </li>
                        <div class="collapse show" id="moduloOtro">
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'controlescolar') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/controlescolar/index">
                                    <i class="bi bi-clipboard-data me-2"></i> Control Escolar
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'usuarioadmin') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/usuarioadmin/index">
                                    <i class="bi bi-person-badge me-2"></i> Usuarios
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'configuracion') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/configuracion/index">
                                    <i class="bi bi-gear me-2"></i> Configuración
                                </a>
                            </li>
                        </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'PROFESOR'): ?>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="<?= BASE_URL ?>/profesor/dashboard">
                                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="nav-item mt-4">
                            <a class="nav-link text-danger" href="<?= BASE_URL ?>/auth/logout">
                                <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <?php endif; ?>

            <!-- Main Content -->
            <main class="<?= (isset($hideSidebar) && $hideSidebar) ? 'col-12' : 'col-md-10 ms-sm-auto' ?> px-md-4">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h1"><?= $title ?? 'Dashboard' ?></span>
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-3">
                                <i class="bi bi-person-circle"></i> <?= $_SESSION['nombre'] ?? 'Usuario' ?>
                            </span>
                            <button class="btn btn-link notification-badge position-relative" disabled>
                                <i class="bi bi-bell fs-5 text-muted"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                    0
                                </span>
                            </button>
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
