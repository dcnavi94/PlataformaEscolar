<?php 
$hideSidebar = true;
require_once '../app/Views/layouts/header_alumno.php'; 
?>

<style>
    /* Custom LMS Styles */
    :root {
        --lms-sidebar-width: 280px;
        --lms-primary: #5624d0;
        --lms-bg: #f7f9fa;
        --lms-text: #2d2f31;
    }

    body {
        background-color: var(--lms-bg);
    }

    /* Sidebar */
    .lms-sidebar {
        width: var(--lms-sidebar-width);
        position: fixed;
        top: 70px;
        bottom: 0;
        left: 0;
        background: white;
        border-right: 1px solid #d1d7dc;
        overflow-y: auto;
        z-index: 100;
    }

    .lms-content {
        margin-left: var(--lms-sidebar-width);
        padding: 2rem;
    }

    .lms-nav-item {
        display: flex;
        align-items: center;
        padding: 12px 24px;
        color: var(--lms-text);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
        border-left: 4px solid transparent;
    }

    .lms-nav-item:hover {
        background-color: #f7f9fa;
        color: var(--lms-primary);
    }

    .lms-nav-item.active {
        background-color: #f0f2f5;
        color: var(--lms-primary);
        border-left-color: var(--lms-primary);
    }

    .lms-nav-item i {
        width: 24px;
        margin-right: 12px;
    }

    /* Course Header in Sidebar */
    .course-mini-header {
        padding: 24px;
        border-bottom: 1px solid #d1d7dc;
    }

    .course-thumbnail {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 4px;
        margin-bottom: 12px;
    }

    /* Content Area */
    .lms-card {
        background: white;
        border: 1px solid #d1d7dc;
        border-radius: 0;
        margin-bottom: 24px;
    }

    .lms-card-header {
        padding: 16px 24px;
        border-bottom: 1px solid #d1d7dc;
        font-weight: 700;
        font-size: 1.1rem;
    }

    /* Curriculum Accordion */
    .module-header {
        background: #f7f9fa;
        border: 1px solid #d1d7dc;
        padding: 16px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 700;
    }
    
    .module-content {
        border: 1px solid #d1d7dc;
        border-top: none;
    }

    .content-item {
        padding: 12px 16px;
        border-bottom: 1px solid #e8eaed;
        display: flex;
        align-items: center;
        background: white;
        text-decoration: none;
        color: inherit;
        transition: background 0.2s;
    }

    .content-item:last-child {
        border-bottom: none;
    }

    .content-item:hover {
        background-color: #fcfcfc;
        color: var(--lms-primary);
    }

    .content-icon {
        margin-right: 12px;
        color: #6a6f73;
    }

    /* Stats Cards */
    .stat-card {
        background: white;
        padding: 20px;
        border: 1px solid #d1d7dc;
        text-align: center;
    }
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--lms-primary);
    }
</style>

<!-- Sidebar Navigation -->
<div class="lms-sidebar">
    <div class="course-mini-header">
        <img src="<?= $asignacion['banner_img'] ?? 'https://gstatic.com/classroom/themes/img_code.jpg' ?>" class="course-thumbnail" alt="Course Image">
        <h6 class="fw-bold mb-1"><?= htmlspecialchars($asignacion['materia_nombre']) ?></h6>
        <p class="text-muted small mb-0">Prof. <?= htmlspecialchars($asignacion['profesor_nombre'] . ' ' . $asignacion['profesor_apellidos']) ?></p>
    </div>

    <div class="nav flex-column mt-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="lms-nav-item active" id="v-pills-dashboard-tab" data-bs-toggle="pill" href="#v-pills-dashboard" role="tab">
            <i class="fas fa-chart-line"></i> Vista General
        </a>
        <a class="lms-nav-item" id="v-pills-curriculum-tab" data-bs-toggle="pill" href="#v-pills-curriculum" role="tab">
            <i class="fas fa-layer-group"></i> Contenido del Curso
        </a>
        <a class="lms-nav-item" id="v-pills-grades-tab" data-bs-toggle="pill" href="#v-pills-grades" role="tab">
            <i class="fas fa-star"></i> Calificaciones
        </a>
        <a class="lms-nav-item" href="/alumno/dashboard">
            <i class="fas fa-arrow-left"></i> Volver a Mis Materias
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="lms-content">
    <div class="tab-content" id="v-pills-tabContent">
        
        <!-- DASHBOARD TAB -->
        <div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Vista General</h2>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="stat-card">
                        <div class="stat-number">0%</div>
                        <div class="text-muted">Progreso del Curso</div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card">
                        <div class="stat-number">
                            <?php 
                                $totalContent = count($orphanedContent);
                                foreach($modulos as $m) $totalContent += count($m['temas']); // Approx
                                echo $totalContent; 
                            ?>
                        </div>
                        <div class="text-muted">Lecciones Totales</div>
                    </div>
                </div>
            </div>

            <div class="lms-card">
                <div class="lms-card-header">
                    Anuncios Recientes
                </div>
                <div class="p-4 text-center text-muted">
                    <i class="fas fa-bullhorn fa-3x mb-3 opacity-25"></i>
                    <p>No hay anuncios recientes.</p>
                </div>
            </div>
        </div>

        <!-- CURRICULUM TAB -->
        <div class="tab-pane fade" id="v-pills-curriculum" role="tabpanel">
            <h2 class="fw-bold mb-4">Contenido del Curso</h2>

            <!-- Orphaned Content -->
            <?php if (!empty($orphanedContent)): ?>
                <div class="mb-4">
                    <div class="module-header rounded-top">
                        <span><i class="fas fa-box-open me-2"></i> Contenido General</span>
                    </div>
                    <div class="module-content bg-white">
                        <?php foreach ($orphanedContent as $item): ?>
                            <a href="<?= $item['item_type'] == 'ACTIVIDAD' ? '/alumno/actividad/' . $item['id_actividad'] : ($item['url'] ?? '#') ?>" class="content-item" <?= $item['item_type'] == 'RECURSO' && $item['tipo'] == 'LINK' ? 'target="_blank"' : '' ?>>
                                <div class="content-icon">
                                    <?php if ($item['item_type'] == 'ACTIVIDAD'): ?>
                                        <i class="fas fa-clipboard-list text-primary"></i>
                                    <?php else: ?>
                                        <i class="fas fa-file-alt text-danger"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold"><?= htmlspecialchars($item['titulo']) ?></div>
                                    <div class="small text-muted">
                                        <?= $item['item_type'] == 'ACTIVIDAD' ? 'Entrega: ' . ($item['fecha_limite'] ?? 'Sin fecha') : 'Recurso' ?>
                                    </div>
                                </div>
                                <?php if ($item['item_type'] == 'ACTIVIDAD'): ?>
                                    <span class="badge bg-secondary">Pendiente</span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Modules -->
            <?php if (empty($modulos) && empty($orphanedContent)): ?>
                <div class="text-center py-5">
                    <img src="/assets/img/empty.svg" alt="Empty" style="max-width: 200px; opacity: 0.5;">
                    <h4 class="mt-3">Aún no hay contenido</h4>
                    <p class="text-muted">Tu profesor no ha publicado contenido para este curso.</p>
                </div>
            <?php else: ?>
                <?php foreach ($modulos as $modulo): ?>
                    <div class="mb-4">
                        <div class="module-header" data-bs-toggle="collapse" data-bs-target="#mod-<?= $modulo['id_modulo'] ?>">
                            <div>
                                <i class="fas fa-chevron-down me-2"></i>
                                <?= htmlspecialchars($modulo['titulo']) ?>
                            </div>
                        </div>
                        <div class="collapse show" id="mod-<?= $modulo['id_modulo'] ?>">
                            <div class="module-content bg-white">
                                <?php if (!empty($modulo['descripcion'])): ?>
                                    <div class="p-3 border-bottom bg-light small text-muted">
                                        <?= htmlspecialchars($modulo['descripcion']) ?>
                                    </div>
                                <?php endif; ?>

                                <?php foreach ($modulo['temas'] as $tema): ?>
                                    <div class="bg-light p-2 border-bottom fw-bold text-uppercase small text-muted ps-3">
                                        <?= htmlspecialchars($tema['titulo']) ?>
                                    </div>
                                    
                                    <?php foreach ($tema['contenido'] as $item): ?>
                                        <a href="<?= $item['item_type'] == 'ACTIVIDAD' ? '/alumno/actividad/' . $item['id_actividad'] : ($item['url'] ?? '#') ?>" class="content-item ps-4" <?= $item['item_type'] == 'RECURSO' && $item['tipo'] == 'LINK' ? 'target="_blank"' : '' ?>>
                                            <div class="content-icon">
                                                <?php if ($item['item_type'] == 'ACTIVIDAD'): ?>
                                                    <i class="fas fa-clipboard-list text-primary"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-play-circle text-secondary"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold"><?= htmlspecialchars($item['titulo']) ?></div>
                                                <div class="small text-muted">
                                                    <?= $item['item_type'] == 'ACTIVIDAD' ? 'Tarea' : 'Recurso' ?>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- GRADES TAB -->
        <div class="tab-pane fade" id="v-pills-grades" role="tabpanel">
            <h2 class="fw-bold mb-4">Mis Calificaciones</h2>
            <div class="lms-card">
                <div class="p-5 text-center text-muted">
                    <i class="fas fa-chart-bar fa-3x mb-3 opacity-25"></i>
                    <p>Las calificaciones estarán disponibles pronto.</p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
