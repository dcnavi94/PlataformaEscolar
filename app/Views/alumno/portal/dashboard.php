<?php require_once '../app/Views/layouts/header_alumno.php'; ?>

<style>
.materia-tab {
    border-bottom: 3px solid transparent;
    transition: all 0.3s;
}
.materia-tab.active {
    border-bottom-color: #0d6efd;
    font-weight: 600;
}
.stat-card {
    transition: transform 0.2s;
}
.stat-card:hover {
    transform: translateY(-5px);
}
</style>

<div class="container-fluid py-4">
    <!-- Hero Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-gradient p-4 rounded text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h2 class="mb-2">¡Bienvenido, <?= htmlspecialchars($alumno['nombre'] . '  ' . $alumno['apellidos']) ?>!</h2>
                <p class="mb-0">Continúa tu aprendizaje donde lo dejaste</p>
            </div>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center stat-card">
                <div class="card-body">
                    <i class="bi bi-book text-primary" style="font-size: 2rem;"></i>
                    <h3 class="mt-2"><?= count($materias) ?></h3>
                    <p class="text-muted mb-0">Cursos Activos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center stat-card">
                <div class="card-body">
                    <i class="bi bi-clock-history text-warning" style="font-size: 2rem;"></i>
                    <h3 class="mt-2"><?= $total_pendientes ?></h3>
                    <p class="text-muted mb-0">Tareas Pendientes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center stat-card">
                <div class="card-body">
                    <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                    <h3 class="mt-2"><?= $total_entregas ?></h3>
                    <p class="text-muted mb-0">Completadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center stat-card">
                <div class="card-body">
                    <i class="bi bi-star text-info" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">0.0</h3>
                    <p class="text-muted mb-0">Promedio General</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Materias Organized Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-mortarboard"></i> Mis Cursos - Organizados por Materia</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($materias)): ?>
                        <div class="p-4">
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle"></i> No estás inscrito en ninguna materia actualmente.
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Tabs for each materia -->
                        <ul class="nav nav-tabs px-3 pt-3" id="materiasTab" role="tablist">
                            <?php $index = 0; foreach ($materias as $materia): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link materia-tab <?= $index === 0 ? 'active' : '' ?>" 
                                            id="materia-<?= $materia['id_materia'] ?>-tab" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#materia-<?= $materia['id_materia'] ?>" 
                                            type="button" 
                                            role="tab">
                                        <i class="bi bi-journal-text"></i> <?= htmlspecialchars($materia['materia_nombre']) ?>
                                    </button>
                                </li>
                            <?php $index++; endforeach; ?>
                        </ul>

                        <!-- Tab content -->
                        <div class="tab-content p-4" id="materiasTabContent">
                            <?php $index = 0; foreach ($materias as $materia): ?>
                                <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" 
                                     id="materia-<?= $materia['id_materia'] ?>" 
                                     role="tabpanel">
                                    
                                    <!-- Header -->
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div>
                                            <h3 class="text-primary mb-1"><?= htmlspecialchars($materia['materia_nombre']) ?></h3>
                                            <?php if (!empty($materia['materia_codigo'])): ?>
                                                <p class="text-muted mb-0">Código: <?= htmlspecialchars($materia['materia_codigo']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary me-2"><?= count($materia['actividades']) ?> Actividades</span>
                                            <span class="badge bg-info me-2"><?= count($materia['recursos']) ?> Recursos</span>
                                            <span class="badge bg-success"><?= count($materia['entregas']) ?> Entregas</span>
                                        </div>
                                    </div>

                                    <!-- Próximas Entregas -->
                                    <?php if (!empty($materia['actividades'])): ?>
                                        <h5 class="mb-3"><i class="bi bi-calendar-event"></i> Próximas Entregas</h5>
                                        <div class="table-responsive mb-4">
                                            <table class="table table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Actividad</th>
                                                        <th>Tipo</th>
                                                        <th>Fecha Límite</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($materia['actividades'] as $act): ?>
                                                        <tr>
                                                            <td><strong><?= htmlspecialchars($act['titulo']) ?></strong></td>
                                                            <td><span class="badge bg-secondary"><?= $act['tipo'] ?></span></td>
                                                            <td>
                                                                <?php if ($act['fecha_limite']): ?>
                                                                    <i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($act['fecha_limite'])) ?>
                                                                <?php else: ?>
                                                                    <span class="text-muted">Sin fecha</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <a href="<?= BASE_URL ?>/alumnoportal/ver/<?= $act['id_actividad'] ?>" 
                                                                   class="btn btn-sm btn-primary">
                                                                    Ver Actividad
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Recursos de Clase -->
                                    <?php if (!empty($materia['recursos'])): ?>
                                        <h5 class="mb-3"><i class="bi bi-folder"></i> Recursos de Clase</h5>
                                        <div class="list-group mb-4">
                                            <?php foreach (array_slice($materia['recursos'], 0, 5) as $rec): ?>
                                                <a href="<?= htmlspecialchars($rec['contenido']) ?>" 
                                                   target="_blank" 
                                                   class="list-group-item list-group-item-action">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h6 class="mb-1">
                                                            <i class="bi bi-file-earmark-text text-primary"></i> 
                                                            <?= htmlspecialchars($rec['titulo']) ?>
                                                        </h6>
                                                        <small class="text-muted"><?= $rec['tipo_recurso'] ?></small>
                                                    </div>
                                                    <?php if(!empty($rec['descripcion'])): ?>
                                                        <p class="mb-1 small text-muted"><?= htmlspecialchars($rec['descripcion']) ?></p>
                                                    <?php endif; ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Mis Entregas -->
                                    <?php if (!empty($materia['entregas'])): ?>
                                        <h5 class="mb-3"><i class="bi bi-file-earmark-check"></i> Mis Entregas</h5>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Actividad</th>
                                                        <th>Fecha Entrega</th>
                                                        <th>Estado</th>
                                                        <th>Calificación</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach (array_slice($materia['entregas'], 0, 5) as $ent): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($ent['actividad_titulo']) ?></td>
                                                            <td><small><?= date('d/m/Y', strtotime($ent['fecha_entrega'])) ?></small></td>
                                                            <td>
                                                                <?php 
                                                                $estadoBadge = [
                                                                    'ENVIADA' => 'primary',
                                                                    'CALIFICADA' => 'success',
                                                                    'TARDIA' => 'warning',
                                                                    'PENDIENTE' => 'secondary'
                                                                ];
                                                                $badge = $estadoBadge[$ent['estado']] ?? 'secondary';
                                                                ?>
                                                                <span class="badge bg-<?= $badge ?>"><?= $ent['estado'] ?></span>
                                                            </td>
                                                            <td>
                                                                <?php if ($ent['calificacion']): ?>
                                                                    <strong><?= $ent['calificacion'] ?></strong>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Quick Access -->
                                    <div class="alert alert-light border mt-3">
                                        <i class="bi bi-info-circle text-primary"></i> 
                                        Para ver todo el contenido detallado, visita:
                                        <a href="<?= BASE_URL ?>/alumnoportal/actividades" class="ms-2 btn btn-sm btn-outline-primary">
                                            <i class="bi bi-list-task"></i> Todas las Actividades
                                        </a>
                                        <a href="<?= BASE_URL ?>/alumnoportal/recursos" class="ms-2 btn btn-sm btn-outline-info">
                                            <i class="bi bi-folder"></i> Todos los Recursos
                                        </a>
                                        <a href="<?= BASE_URL ?>/alumnoportal/misEntregas" class="ms-2 btn btn-sm btn-outline-success">
                                            <i class="bi bi-file-earmark-check"></i> Todas mis Entregas
                                        </a>
                                    </div>
                                </div>
                            <?php $index++; endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
