<?php require_once '../app/Views/layouts/header_alumno.php'; ?>

<style>
.activity-card {
    transition: transform 0.2s;
    border-left: 4px solid;
    cursor: pointer;
}
.activity-card:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.activity-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
</style>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-clipboard-check"></i> Mis Actividades</h2>
            <p class="text-muted">Todas las tareas y actividades de tus cursos</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <ul class="nav nav-pills mb-4" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#todas" type="button">
                Todas (<?= count($actividades ?? []) ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pendientes" type="button">
                Pendientes 
                <?php 
                $pendientes_count = 0;
                foreach ($actividades ?? [] as $act) {
                    if ($act['status'] == 'PENDIENTE' || ($act['status'] == 'VENCIDA' && !$act['id_entrega'])) {
                        $pendientes_count++;
                    }
                }
                ?>
                (<?= $pendientes_count ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#completadas" type="button">
                Completadas
                <?php 
                $completadas_count = 0;
                foreach ($actividades ?? [] as $act) {
                    if ($act['status'] == 'ENTREGADA') {
                        $completadas_count++;
                    }
                }
                ?>
                (<?= $completadas_count ?>)
            </button>
        </li>
    </ul>

    <div class="tab-content">
        <!-- Todas -->
        <div class="tab-pane fade show active" id="todas" role="tabpanel">
            <?php if (empty($actividades)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No hay actividades disponibles aún.
                </div>
            <?php else: ?>
                <?php 
                $actividadesPorMateria = [];
                foreach ($actividades as $act) {
                    $actividadesPorMateria[$act['materia_nombre']][] = $act;
                }
                ?>

                <?php foreach ($actividadesPorMateria as $materia => $acts): ?>
                    <h5 class="mb-3 mt-4"><i class="bi bi-book"></i> <?= htmlspecialchars($materia) ?></h5>
                    
                    <?php foreach ($acts as $act): ?>
                        <?php 
                        // Determine colors and icons by type
                        $typeConfig = [
                            'TAREA' => ['color' => '#4facfe', 'icon' => 'bi-file-earmark-text', 'bg' => '#e3f2fd'],
                            'EXAMEN' => ['color' => '#f093fb', 'icon' => 'bi-clipboard-check', 'bg' => '#fce4ec'],
                            'PROYECTO' => ['color' => '#43e97b', 'icon' => 'bi-kanban', 'bg' => '#e8f5e9'],
                            'LECTURA' => ['color' => '#fa709a', 'icon' => 'bi-book-half', 'bg' => '#fff3e0'],
                            'OTRO' => ['color' => '#667eea', 'icon' => 'bi-star', 'bg' => '#ede7f6']
                        ];
                        $config = $typeConfig[$act['tipo']] ?? $typeConfig['OTRO'];
                        
                        $statusBadge = match($act['status']) {
                            'ENTREGADA' => '<span class="badge bg-success">Entregada</span>',
                            'VENCIDA' => '<span class="badge bg-danger">Vencida</span>',
                            'PENDIENTE' => '<span class="badge bg-warning text-dark">Pendiente</span>',
                            default => '<span class="badge bg-secondary">Nuevo</span>'
                        };
                        ?>
                        
                        <div class="card activity-card mb-3" 
                             style="border-left-color: <?= $config['color'] ?>;"
                             onclick="window.location.href='<?= BASE_URL ?>/alumnoportal/ver/<?= $act['id_actividad'] ?>'">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="activity-icon" style="background-color: <?= $config['bg'] ?>; color: <?= $config['color'] ?>;">
                                            <i class="bi <?= $config['icon'] ?>"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5 class="mb-1"><?= htmlspecialchars($act['titulo']) ?></h5>
                                                <p class="text-muted mb-2 small">
                                                    <?= htmlspecialchars(substr($act['descripcion'] ?? '', 0, 120)) ?>...
                                                </p>
                                                <div class="d-flex gap-2 align-items-center flex-wrap">
                                                    <span class="badge" style="background-color: <?= $config['color'] ?>;">
                                                        <?= $act['tipo'] ?>
                                                    </span>
                                                    <?= $statusBadge ?>
                                                    <?php if ($act['puntos_max'] > 0): ?>
                                                        <small class="text-muted">
                                                            <i class="bi bi-star"></i> <?= $act['puntos_max'] ?> pts
                                                        </small>
                                                    <?php endif; ?>
                                                    <?php if ($act['fecha_limite']): ?>
                                                        <small class="text-muted">
                                                            <i class="bi bi-calendar-event"></i>
                                                            <?= date('d M Y', strtotime($act['fecha_limite'])) ?>
                                                        </small>
                                                    <?php endif; ?>
                                                    <?php if ($act['id_entrega'] && $act['calificacion'] !== null): ?>
                                                        <small class="fw-bold text-success">
                                                            <i class="bi bi-check-circle-fill"></i>
                                                            <?= number_format($act['calificacion'], 1) ?> / <?= $act['puntos_max'] ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="<?= BASE_URL ?>/alumnoportal/ver/<?= $act['id_actividad'] ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    Ver Detalles →
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pendientes -->
        <div class="tab-pane fade" id="pendientes" role="tabpanel">
            <div class="row">
                <?php 
                $hasPendientes = false;
                foreach ($actividades ?? [] as $act): 
                    if ($act['status'] == 'PENDIENTE' || ($act['status'] == 'VENCIDA' && !$act['id_entrega'])):
                        $hasPendientes = true;
                        $config = $typeConfig[$act['tipo']] ?? $typeConfig['OTRO'];
                ?>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100" onclick="window.location.href='<?= BASE_URL ?>/alumnoportal/ver/<?= $act['id_actividad'] ?>'" style="cursor: pointer;">
                                <div class="card-body">
                                    <div class="d-flex align-items-start mb-2">
                                        <div class="activity-icon me-3" style="background-color: <?= $config['bg'] ?>; color: <?= $config['color'] ?>; width: 50px; height: 50px; font-size: 1.3rem;">
                                            <i class="bi <?= $config['icon'] ?>"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= htmlspecialchars($act['titulo']) ?></h6>
                                            <small class="text-muted"><?= htmlspecialchars($act['materia_nombre']) ?></small>
                                        </div>
                                    </div>
                                    <p class="small text-muted"><?= htmlspecialchars(substr($act['descripcion'] ?? '', 0, 80)) ?>...</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <?php if ($act['fecha_limite']): ?>
                                            <small class="<?= strtotime($act['fecha_limite']) < time() ? 'text-danger' : 'text-warning' ?>">
                                                <i class="bi bi-clock"></i>
                                                <?= strtotime($act['fecha_limite']) < time() ? 'Vencida' : 'Vence ' . date('d/m', strtotime($act['fecha_limite'])) ?>
                                            </small>
                                        <?php else: ?>
                                            <small class="text-muted">Sin fecha límite</small>
                                        <?php endif; ?>
                                        <span class="badge" style="background-color: <?= $config['color'] ?>;"><?= $act['tipo'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php 
                    endif;
                endforeach; 
                if (!$hasPendientes): ?>
                    <div class="col-12">
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> ¡Excelente! No tienes tareas pendientes.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Completadas -->
        <div class="tab-pane fade" id="completadas" role="tabpanel">
            <div class="row">
                <?php 
                $hasCompletadas = false;
                foreach ($actividades ?? [] as $act): 
                    if ($act['status'] == 'ENTREGADA'):
                        $hasCompletadas = true;
                        $config = $typeConfig[$act['tipo']] ?? $typeConfig['OTRO'];
                ?>
                        <div class="col-md-12 mb-2">
                            <div class="card" onclick="window.location.href='<?= BASE_URL ?>/alumnoportal/ver/<?= $act['id_actividad'] ?>'" style="cursor: pointer;">
                                <div class="card-body py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            <div>
                                                <strong><?= htmlspecialchars($act['titulo']) ?></strong>
                                                <small class="text-muted ms-2"><?= htmlspecialchars($act['materia_nombre']) ?></small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <?php if ($act['calificacion'] !== null): ?>
                                                <div class="text-center">
                                                    <div class="fw-bold text-success"><?= number_format($act['calificacion'], 1) ?></div>
                                                    <small class="text-muted">/ <?= $act['puntos_max'] ?></small>
                                                </div>
                                            <?php else: ?>
                                                <span class="badge bg-info">En revisión</span>
                                            <?php endif; ?>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($act['fecha_entrega'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php 
                    endif;
                endforeach;
                if (!$hasCompletadas): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Aún no has completado ninguna actividad.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
