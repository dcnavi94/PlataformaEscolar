<?php require_once '../app/Views/layouts/header_alumno.php'; ?>

<style>
.resource-card {
    transition: transform 0.2s;
    cursor: pointer;
    border: none;
}
.resource-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.resource-icon {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto;
}
</style>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-folder"></i> Recursos de Clase</h2>
            <p class="text-muted">Materiales, links y documentos de tus cursos</p>
        </div>
    </div>

    <?php if (empty($recursos)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No hay recursos disponibles aún.
        </div>
    <?php else: ?>
        <?php 
        // Group resources by subject
        $recursosPorMateria = [];
        foreach ($recursos as $rec) {
            $recursosPorMateria[$rec['materia_nombre']][] = $rec;
        }
        ?>

        <?php foreach ($recursosPorMateria as $materia => $recs): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="mb-3"><i class="bi bi-book"></i> <?= htmlspecialchars($materia) ?></h4>
                </div>

                <?php foreach ($recs as $rec): ?>
                    <?php 
                    // Configure icon and color by type
                    $typeConfig = [
                        'LINK' => [
                            'icon' => 'bi-link-45deg',
                            'color' => '#4facfe',
                            'bg' => '#e3f2fd',
                            'label' => 'Enlace'
                        ],
                        'VIDEO' => [
                            'icon' => 'bi-play-circle',
                            'color' => '#f093fb',
                            'bg' => '#fce4ec',
                            'label' => 'Video'
                        ],
                        'DOCUMENTO' => [
                            'icon' => 'bi-file-earmark-pdf',
                            'color' => '#43e97b',
                            'bg' => '#e8f5e9',
                            'label' => 'Documento'
                        ],
                        'PRESENTACION' => [
                            'icon' => 'bi-file-earmark-slides',
                            'color' => '#fa709a',
                            'bg' => '#fff3e0',
                            'label' => 'Presentación'
                        ],
                        'OTRO' => [
                            'icon' => 'bi-file-earmark',
                            'color' => '#667eea',
                            'bg' => '#ede7f6',
                            'label' => 'Otro'
                        ]
                    ];
                    $config = $typeConfig[$rec['tipo']] ?? $typeConfig['OTRO'];
                    ?>

                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card resource-card h-100">
                            <div class="card-body text-center">
                                <div class="resource-icon mb-3" style="background-color: <?= $config['bg'] ?>; color: <?= $config['color'] ?>;">
                                    <i class="bi <?= $config['icon'] ?>"></i>
                                </div>

                                <h6 class="card-title mb-2"><?= htmlspecialchars($rec['titulo']) ?></h6>
                                
                                <span class="badge mb-3" style="background-color: <?= $config['color'] ?>;">
                                    <?= $config['label'] ?>
                                </span>

                                <?php if ($rec['descripcion']): ?>
                                    <p class="card-text small text-muted mb-3">
                                        <?= htmlspecialchars(substr($rec['descripcion'], 0, 80)) ?>
                                        <?= strlen($rec['descripcion']) > 80 ? '...' : '' ?>
                                    </p>
                                <?php endif; ?>

                                <small class="text-muted d-block mb-3">
                                    <i class="bi bi-calendar"></i>
                                    <?= date('d M Y', strtotime($rec['fecha_publicacion'])) ?>
                                </small>

                                <?php if ($rec['url']): ?>
                                    <a href="<?= htmlspecialchars($rec['url']) ?>" 
                                       target="_blank" 
                                       class="btn btn-sm w-100"
                                       style="background-color: <?= $config['color'] ?>; color: white;">
                                        <i class="bi bi-box-arrow-up-right"></i> Abrir
                                    </a>
                                <?php elseif ($rec['archivo']): ?>
                                    <a href="<?= BASE_URL ?>/uploads/<?= $rec['archivo'] ?>" 
                                       target="_blank" 
                                       class="btn btn-sm w-100"
                                       style="background-color: <?= $config['color'] ?>; color: white;">
                                        <i class="bi bi-download"></i> Descargar
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Quick Actions -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="mb-3">Accesos Rápidos</h5>
                    <a href="<?= BASE_URL ?>/alumnoportal/dashboard" class="btn btn-primary me-2">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                    <a href="<?= BASE_URL ?>/alumnoportal/actividades" class="btn btn-info me-2">
                        <i class="bi bi-clipboard-check"></i> Actividades
                    </a>
                    <a href="<?= BASE_URL ?>/alumnoportal/misEntregas" class="btn btn-success">
                        <i class="bi bi-file-earmark-check"></i> Mis Entregas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
