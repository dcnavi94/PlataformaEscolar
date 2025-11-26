<?php 
$hideSidebar = true;
require_once '../app/Views/layouts/header.php'; 
?>

<style>
    /* Custom LMS Styles */
    :root {
        --lms-sidebar-width: 280px;
        --lms-primary: #5624d0; /* Udemy-like purple */
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
        top: 70px; /* Adjust based on header height */
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
    }

    .content-item:last-child {
        border-bottom: none;
    }

    .content-item:hover {
        background-color: #fcfcfc;
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
        <p class="text-muted small mb-0"><?= htmlspecialchars($asignacion['grupo_nombre']) ?></p>
        <button class="btn btn-outline-secondary btn-sm w-100 mt-3" data-bs-toggle="modal" data-bs-target="#customizeThemeModal">
            <i class="fas fa-pen me-2"></i> Cambiar Imagen
        </button>
    </div>

    <div class="nav flex-column mt-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="lms-nav-item active" id="v-pills-dashboard-tab" data-bs-toggle="pill" href="#v-pills-dashboard" role="tab">
            <i class="fas fa-chart-line"></i> Vista General
        </a>
        <a class="lms-nav-item" id="v-pills-curriculum-tab" data-bs-toggle="pill" href="#v-pills-curriculum" role="tab">
            <i class="fas fa-layer-group"></i> Contenido del Curso
        </a>
        <a class="lms-nav-item" id="v-pills-students-tab" data-bs-toggle="pill" href="#v-pills-students" role="tab">
            <i class="fas fa-users"></i> Estudiantes
        </a>
        <a class="lms-nav-item" id="v-pills-announcements-tab" data-bs-toggle="pill" href="#v-pills-announcements" role="tab">
            <i class="fas fa-bullhorn"></i> Anuncios
        </a>
        <a class="lms-nav-item" href="/profesor/dashboard">
            <i class="fas fa-arrow-left"></i> Volver al Panel
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="lms-content">
    <div class="tab-content" id="v-pills-tabContent">
        
        <!-- DASHBOARD TAB -->
        <div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Vista General del Curso</h2>
                <a href="/curso/createContent/<?= $asignacion['id_asignacion'] ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Crear Contenido
                </a>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-number"><?= count($alumnos) ?></div>
                        <div class="text-muted">Estudiantes Inscritos</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-number"><?= count($modulos) ?></div>
                        <div class="text-muted">Módulos Publicados</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-number">
                            <?php 
                                $totalContent = count($orphanedContent);
                                foreach($modulos as $m) $totalContent += count($m['temas']); // Approx
                                echo $totalContent; // Simplification
                            ?>
                        </div>
                        <div class="text-muted">Actividades y Recursos</div>
                    </div>
                </div>
            </div>

            <div class="lms-card">
                <div class="lms-card-header">
                    Actividad Reciente
                </div>
                <div class="p-4 text-center text-muted">
                    <i class="fas fa-chart-bar fa-3x mb-3 opacity-25"></i>
                    <p>No hay actividad reciente para mostrar.</p>
                </div>
            </div>
        </div>

        <!-- CURRICULUM TAB -->
        <div class="tab-pane fade" id="v-pills-curriculum" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Contenido del Curso</h2>
                <div>
                    <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#newModuleModal">
                        <i class="fas fa-folder-plus me-2"></i> Nuevo Módulo
                    </button>
                    <a href="/curso/createContent/<?= $asignacion['id_asignacion'] ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Crear Contenido
                    </a>
                </div>
            </div>

            <!-- Orphaned Content -->
            <?php if (!empty($orphanedContent)): ?>
                <div class="mb-4">
                    <div class="module-header rounded-top">
                        <span><i class="fas fa-box-open me-2"></i> Contenido General</span>
                    </div>
                    <div class="module-content bg-white">
                        <?php foreach ($orphanedContent as $item): ?>
                            <div class="content-item">
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
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="/curso/editContent/<?= strtolower($item['item_type']) == 'actividad' ? 'activity' : 'resource' ?>/<?= $item['item_type'] == 'ACTIVIDAD' ? $item['id_actividad'] : $item['id_recurso'] ?>">Editar</a></li>
                                        <li><a class="dropdown-item text-danger" href="/curso/deleteContent/<?= strtolower($item['item_type']) == 'actividad' ? 'activity' : 'resource' ?>/<?= $item['item_type'] == 'ACTIVIDAD' ? $item['id_actividad'] : $item['id_recurso'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a></li>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Modules -->
            <?php if (empty($modulos) && empty($orphanedContent)): ?>
                <div class="text-center py-5">
                    <img src="/assets/img/empty.svg" alt="Empty" style="max-width: 200px; opacity: 0.5;">
                    <h4 class="mt-3">Empieza a crear tu curso</h4>
                    <p class="text-muted">Crea módulos y añade lecciones para estructurar tu contenido.</p>
                </div>
            <?php else: ?>
                <?php foreach ($modulos as $modulo): ?>
                    <div class="mb-4">
                        <div class="module-header" data-bs-toggle="collapse" data-bs-target="#mod-<?= $modulo['id_modulo'] ?>">
                            <div>
                                <i class="fas fa-chevron-down me-2"></i>
                                <?= htmlspecialchars($modulo['titulo']) ?>
                            </div>
                            <div class="dropdown" onclick="event.stopPropagation();">
                                <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#newTopicModal" onclick="setModuloId(<?= $modulo['id_modulo'] ?>)">Añadir Tema</a></li>
                                    <li><a class="dropdown-item text-danger" href="/curso/deleteModulo/<?= $modulo['id_modulo'] ?>" onclick="return confirm('¿Eliminar módulo?')">Eliminar Módulo</a></li>
                                </ul>
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
                                    <div class="bg-light p-2 border-bottom fw-bold text-uppercase small text-muted ps-3 d-flex justify-content-between">
                                        <span><?= htmlspecialchars($tema['titulo']) ?></span>
                                        <div>
                                            <button class="btn btn-link btn-sm p-0 me-2" onclick="editTema(<?= $tema['id_tema'] ?>, '<?= htmlspecialchars($tema['titulo']) ?>', '<?= htmlspecialchars($tema['descripcion']) ?>')"><i class="fas fa-pen"></i></button>
                                            <a href="/curso/deleteTema/<?= $tema['id_tema'] ?>" class="text-danger" onclick="return confirm('¿Eliminar?')"><i class="fas fa-times"></i></a>
                                        </div>
                                    </div>
                                    
                                    <?php foreach ($tema['contenido'] as $item): ?>
                                        <div class="content-item ps-4">
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
                                            <div class="dropdown">
                                                <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="/curso/editContent/<?= strtolower($item['item_type']) == 'actividad' ? 'activity' : 'resource' ?>/<?= $item['item_type'] == 'ACTIVIDAD' ? $item['id_actividad'] : $item['id_recurso'] ?>">Editar</a></li>
                                                    <li><a class="dropdown-item text-danger" href="/curso/deleteContent/<?= strtolower($item['item_type']) == 'actividad' ? 'activity' : 'resource' ?>/<?= $item['item_type'] == 'ACTIVIDAD' ? $item['id_actividad'] : $item['id_recurso'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- STUDENTS TAB -->
        <div class="tab-pane fade" id="v-pills-students" role="tabpanel">
            <h2 class="fw-bold mb-4">Estudiantes Inscritos</h2>
            <div class="lms-card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Nombre</th>
                                <th>Email</th>
                                <th>Progreso</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alumnos as $alumno): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white me-3" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                                <?= strtoupper(substr($alumno['nombre'], 0, 1)) ?>
                                            </div>
                                            <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellidos']) ?>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($alumno['email']) ?></td>
                                    <td style="width: 200px;">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted">0% completado</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary">Ver Notas</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ANNOUNCEMENTS TAB -->
        <div class="tab-pane fade" id="v-pills-announcements" role="tabpanel">
            <h2 class="fw-bold mb-4">Anuncios</h2>
            
            <div class="lms-card p-4 mb-4">
                <form action="/curso/storeAnuncio" method="POST">
                    <input type="hidden" name="id_asignacion" value="<?= $asignacion['id_asignacion'] ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Publicar un nuevo anuncio</label>
                        <textarea name="mensaje" class="form-control" rows="3" placeholder="Escribe algo para tu clase..." required></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Publicar</button>
                    </div>
                </form>
            </div>

            <?php 
            $announcements = array_filter($stream, function($item) { return $item['item_type'] == 'ANUNCIO'; });
            if (empty($announcements)): 
            ?>
                <div class="text-center text-muted py-5">
                    <i class="fas fa-bullhorn fa-3x mb-3 opacity-25"></i>
                    <p>No hay anuncios publicados aún.</p>
                </div>
            <?php else: ?>
                <?php foreach ($announcements as $item): ?>
                    <div class="lms-card p-4">
                        <div class="d-flex mb-3">
                            <div class="avatar-circle bg-dark text-white me-3">
                                <?= strtoupper(substr($item['profesor_nombre'], 0, 1)) ?>
                            </div>
                            <div>
                                <div class="fw-bold"><?= htmlspecialchars($item['profesor_nombre'] . ' ' . $item['profesor_apellidos']) ?></div>
                                <div class="small text-muted"><?= date('d M, Y \a \l\a\s H:i', strtotime($item['fecha_publicacion'])) ?></div>
                            </div>
                        </div>
                        <div class="text-dark">
                            <?= nl2br(htmlspecialchars($item['mensaje'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- Modals (Keep existing modals) -->
<!-- New Module Modal -->
<div class="modal fade" id="newModuleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/curso/storeModulo" method="POST">
                <input type="hidden" name="id_asignacion" value="<?= $asignacion['id_asignacion'] ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Módulo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Título del Módulo</label>
                        <input type="text" name="titulo" class="form-control" required placeholder="Ej. Unidad 1: Introducción">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción (Opcional)</label>
                        <textarea name="descripcion" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Módulo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- New Topic Modal -->
<div class="modal fade" id="newTopicModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/curso/storeTema" method="POST">
                <input type="hidden" name="id_modulo" id="modal_id_modulo">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Tema</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Título del Tema</label>
                        <input type="text" name="titulo" class="form-control" required placeholder="Ej. Lección 1.1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción (Opcional)</label>
                        <textarea name="descripcion" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Tema</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Topic Modal -->
<div class="modal fade" id="editTopicModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/curso/updateTema" method="POST">
                <input type="hidden" name="id_tema" id="edit_id_tema">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Tema</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Título del Tema</label>
                        <input type="text" name="titulo" id="edit_titulo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Customize Theme Modal -->
<div class="modal fade" id="customizeThemeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/curso/updateTheme" method="POST">
                <input type="hidden" name="id_asignacion" value="<?= $asignacion['id_asignacion'] ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Personalizar Tema</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Selecciona una imagen para el encabezado del curso.</p>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="cursor-pointer">
                                <input type="radio" name="banner_img" value="https://gstatic.com/classroom/themes/img_code.jpg" class="d-none" checked>
                                <img src="https://gstatic.com/classroom/themes/img_code.jpg" class="img-fluid rounded border border-2 border-transparent theme-option" style="height: 80px; object-fit: cover;">
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="cursor-pointer">
                                <input type="radio" name="banner_img" value="https://gstatic.com/classroom/themes/img_read.jpg" class="d-none">
                                <img src="https://gstatic.com/classroom/themes/img_read.jpg" class="img-fluid rounded border border-2 border-transparent theme-option" style="height: 80px; object-fit: cover;">
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="cursor-pointer">
                                <input type="radio" name="banner_img" value="https://gstatic.com/classroom/themes/img_breakfast.jpg" class="d-none">
                                <img src="https://gstatic.com/classroom/themes/img_breakfast.jpg" class="img-fluid rounded border border-2 border-transparent theme-option" style="height: 80px; object-fit: cover;">
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="cursor-pointer">
                                <input type="radio" name="banner_img" value="https://gstatic.com/classroom/themes/img_bookclub.jpg" class="d-none">
                                <img src="https://gstatic.com/classroom/themes/img_bookclub.jpg" class="img-fluid rounded border border-2 border-transparent theme-option" style="height: 80px; object-fit: cover;">
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">O pega una URL de imagen</label>
                        <input type="url" name="banner_img_custom" class="form-control" placeholder="https://ejemplo.com/imagen.jpg" onchange="document.querySelector('input[name=banner_img]:checked').checked = false; this.name='banner_img'">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .theme-option { cursor: pointer; opacity: 0.7; transition: 0.2s; }
    input[name="banner_img"]:checked + .theme-option {
        border-color: var(--lms-primary) !important;
        opacity: 1;
    }
    .theme-option:hover { opacity: 1; }
</style>

<script>
function setModuloId(id) {
    document.getElementById('modal_id_modulo').value = id;
}

function editTema(id, titulo, descripcion) {
    document.getElementById('edit_id_tema').value = id;
    document.getElementById('edit_titulo').value = titulo;
    document.getElementById('edit_descripcion').value = descripcion;
    var myModal = new bootstrap.Modal(document.getElementById('editTopicModal'));
    myModal.show();
}
</script>

<?php require_once '../app/Views/layouts/footer.php'; ?>
