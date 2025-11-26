<?php require_once '../app/Views/layouts/header_alumno.php'; ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-mortarboard-fill"></i> Mi Proceso de Titulación</h2>
        </div>
    </div>

    <?php if (!$proceso): ?>
        <!-- No tiene proceso activo -->
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow">
                    <div class="card-body text-center p-5">
                        <i class="bi bi-file-earmark-text text-primary" style="font-size: 4rem;"></i>
                        <h3 class="mt-3">Inicia tu Proceso de Titulación</h3>
                        <p class="text-muted">Aún no has iniciado tu proceso de titulación. Revisa los requisitos y cuando estés listo, envía tu solicitud.</p>
                        
                        <?php if (!empty($requisitos_programa)): ?>
                            <div class="alert alert-info text-start mt-4">
                                <h6><i class="bi bi-info-circle"></i> Requisitos para tu programa:</h6>
                                <ul class="mb-0">
                                    <?php foreach ($requisitos_programa as $req): ?>
                                        <li><?= htmlspecialchars($req['nombre_requisito']) ?>
                                            <?php if ($req['es_obligatorio']): ?>
                                                <span class="badge bg-danger">Obligatorio</span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <a href="<?= BASE_URL ?>/titulacionalumno/solicitar" class="btn btn-primary btn-lg mt-3">
                            <i class="bi bi-plus-circle"></i> Solicitar Titulación
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Tiene proceso activo -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Folio: <?= htmlspecialchars($proceso['numero_folio']) ?></h5>
                            <span class="badge bg-light text-dark">
                                Estado: <?= $proceso['estado'] ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Modalidad:</strong> <?= $proceso['modalidad'] ?></p>
                                <p><strong>Fecha Solicitud:</strong> <?= date('d/m/Y', strtotime($proceso['fecha_solicitud'])) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Progreso:</strong></p>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: <?= $progreso ?>%">
                                        <?= $progreso ?>%
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($proceso['observaciones']): ?>
                            <div class="alert alert-info mt-3">
                                <strong>Observaciones:</strong> <?= htmlspecialchars($proceso['observaciones']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requisitos -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Requisitos de Titulación</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php foreach ($cumplimientos as $cum): ?>
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-5">
                                            <h6 class="mb-1"><?= htmlspecialchars($cum['nombre_requisito']) ?>
                                                <?php if ($cum['es_obligatorio']): ?>
                                                    <span class="badge bg-danger">Obligatorio</span>
                                                <?php endif; ?>
                                            </h6>
                                            <small class="text-muted"><?= htmlspecialchars($cum['descripcion']) ?></small>
                                        </div>
                                        <div class="col-md-3">
                                            <?php
                                            $estadoBadge = [
                                                'PENDIENTE' => 'secondary',
                                                'CARGADO' => 'warning',
                                                'APROBADO' => 'success',
                                                'RECHAZADO' => 'danger'
                                            ];
                                            ?>
                                            <span class="badge bg-<?= $estadoBadge[$cum['estado']] ?>">
                                                <?= $cum['estado'] ?>
                                            </span>
                                            <?php if ($cum['fecha_carga']): ?>
                                                <br><small class="text-muted">Cargado: <?= date('d/m/Y', strtotime($cum['fecha_carga'])) ?></small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <?php if ($cum['estado'] == 'PENDIENTE' || $cum['estado'] == 'RECHAZADO'): ?>
                                                <form method="POST" 
                                                      action="<?= BASE_URL ?>/titulacionalumno/subirDocumento/<?= $cum['id_cumplimiento'] ?>" 
                                                      enctype="multipart/form-data" class="d-inline">
                                                    <div class="input-group input-group-sm">
                                                        <input type="file" name="documento" class="form-control" required>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="bi bi-upload"></i> Subir
                                                        </button>
                                                    </div>
                                                </form>
                                            <?php elseif ($cum['documento_url']): ?>
                                                <a href="<?= BASE_URL ?>/<?= $cum['documento_url'] ?>" 
                                                   target="_blank" class="btn btn-sm btn-info">
                                                    <i class="bi bi-file-earmark-pdf"></i> Ver Documento
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if ($cum['comentarios']): ?>
                                        <div class="mt-2 alert alert-secondary mb-0">
                                            <small><strong>Comentarios:</strong> <?= htmlspecialchars($cum['comentarios']) ?></small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($proceso['estado'] == 'TITULADO'): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-success">
                        <h5><i class="bi bi-check-circle"></i> ¡Felicidades! Has completado tu proceso de titulación</h5>
                        <p class="mb-0">
                            <a href="<?= BASE_URL ?>/titulacionalumno/certificado" class="btn btn-success">
                                <i class="bi bi-download"></i> Descargar Certificado
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
