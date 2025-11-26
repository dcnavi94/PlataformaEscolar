<div class="row mb-4">
    <div class="col-md-12">
        <h2>Bienvenido, <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellidos']) ?></h2>
        <p class="text-muted">
            Programa: <?= htmlspecialchars($alumno['programa_nombre'] ?? 'N/A') ?> | 
            Estatus: <span class="badge bg-success"><?= htmlspecialchars($alumno['estatus']) ?></span>
        </p>
    </div>
</div>

<!-- Enrolled Subjects Section -->
<div class="row mb-5">
    <div class="col-12 mb-3">
        <h4 class="fw-bold text-primary"><i class="bi bi-book"></i> Mis Materias</h4>
    </div>
    
    <?php if (empty($asignaciones)): ?>
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No tienes materias asignadas actualmente.
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($asignaciones as $asignacion): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm hover-card">
                    <!-- Course Banner -->
                    <div style="height: 100px; background-image: url('<?= $asignacion['banner_img'] ?? 'https://gstatic.com/classroom/themes/img_code.jpg' ?>'); background-size: cover; background-position: center;"></div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark mb-1">
                            <?= htmlspecialchars($asignacion['materia_nombre']) ?>
                        </h5>
                        <p class="card-text text-muted small mb-3">
                            <?= htmlspecialchars($asignacion['grupo_nombre']) ?>
                        </p>
                        
                        <div class="mt-auto">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-circle bg-light text-primary me-2" style="width: 30px; height: 30px; font-size: 0.8rem; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                    <?= strtoupper(substr($asignacion['profesor_nombre'], 0, 1)) ?>
                                </div>
                                <small class="text-muted">
                                    Prof. <?= htmlspecialchars($asignacion['profesor_nombre'] . ' ' . $asignacion['profesor_apellidos']) ?>
                                </small>
                            </div>
                            
                            <a href="<?= BASE_URL ?>/alumno/curso/<?= $asignacion['id_asignacion'] ?>" class="btn btn-outline-primary w-100">
                                <i class="bi bi-box-arrow-in-right"></i> Ir al Curso
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Financial Stats (Secondary) -->
<div class="row">
    <div class="col-12 mb-3">
        <h5 class="text-muted"><i class="bi bi-wallet2"></i> Estado de Cuenta</h5>
    </div>
    <div class="col-md-4">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Cargos Pendientes</h6>
                <h3 class="text-warning"><?= $stats['num_pendientes'] ?></h3>
                <p class="mb-0">Total: $<?= number_format($stats['total_pendiente'], 2) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Cargos Vencidos</h6>
                <h3 class="text-danger"><?= $stats['num_vencidos'] ?></h3>
                <p class="mb-0">Total: $<?= number_format($stats['total_vencido'], 2) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Pagado</h6>
                <h3 class="text-success">$<?= number_format($stats['total_pagado'], 2) ?></h3>
                <p class="mb-0">Historial de pagos</p>
            </div>
        </div>
    </div>
</div>

<?php if ($is_moroso): ?>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="alert alert-danger">
            <h5><i class="bi bi-exclamation-octagon"></i> Cuenta Bloqueada por Morosidad</h5>
            <p>Tu cuenta tiene cargos vencidos por más de 2 meses. Algunas funciones pueden estar restringidas hasta regularizar tu situación.</p>
            <p class="mb-0"><strong>Por favor contacta al administrador o realiza tus pagos pendientes.</strong></p>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
    .hover-card { transition: transform 0.2s; }
    .hover-card:hover { transform: translateY(-5px); }
</style>
