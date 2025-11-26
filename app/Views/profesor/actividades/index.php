<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="bi bi-clipboard-check"></i> Mis Actividades</h2>
                <a href="<?= BASE_URL ?>/actividad/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nueva Actividad
                </a>
            </div>
        </div>
    </div>

    <?php if (empty($actividades)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No has creado actividades aún. 
            <a href="<?= BASE_URL ?>/actividad/create">Crear primera actividad</a>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Tipo</th>
                                <th>Materia/Grupo</th>
                                <th>Fecha Límite</th>
                                <th>Entregas</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($actividades as $act): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($act['titulo']) ?></strong>
                                        <?php if ($act['archivo_adjunto']): ?>
                                            <i class="bi bi-paperclip text-muted"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?= $act['tipo'] ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= htmlspecialchars($act['materia_nombre'] ?? '') ?></small><br>
                                        <small><?= htmlspecialchars($act['grupo_nombre'] ?? '') ?></small>
                                    </td>
                                    <td>
                                        <?php if ($act['fecha_limite']): ?>
                                            <?= date('d/m/Y H:i', strtotime($act['fecha_limite'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Sin límite</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/actividad/entregas/<?= $act['id_actividad'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-file-earmark-text"></i> <?= $act['total_entregas'] ?? 0 ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($act['estado'] == 'ACTIVA'): ?>
                                            <span class="badge bg-success">Activa</span>
                                        <?php elseif ($act['estado'] == 'CERRADA'): ?>
                                            <span class="badge bg-danger">Cerrada</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Borrador</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/actividad/entregas/<?= $act['id_actividad'] ?>" class="btn btn-sm btn-info" title="Ver entregas">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/actividad/delete/<?= $act['id_actividad'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('¿Eliminar esta actividad?')"
                                           title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
