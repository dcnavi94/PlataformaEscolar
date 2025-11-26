<?php require_once '../app/Views/layouts/header_profesor.php'; ?>

<div class="mb-4">
    <h2><i class="bi bi-pencil-square"></i> Calificar Estudiantes</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/profesor/dashboard">Mi Panel</a></li>
            <li class="breadcrumb-item active">Calificar</li>
        </ol>
    </nav>
</div>

<!-- Assignment Info Card -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-book"></i> <?= htmlspecialchars($asignacion['materia_nombre']) ?>
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <strong>Código:</strong> <?= htmlspecialchars($asignacion['materia_codigo']) ?>
            </div>
            <div class="col-md-3">
                <strong>Grupo:</strong> <?= htmlspecialchars($asignacion['grupo_nombre']) ?>
            </div>
            <div class="col-md-3">
                <strong>Programa:</strong> <?= htmlspecialchars($asignacion['programa_nombre']) ?>
            </div>
            <div class="col-md-3">
                <strong>Estado:</strong> 
                <?php if ($isClosed): ?>
                    <span class="badge bg-success"><i class="bi bi-lock"></i> Cerrada</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark"><i class="bi bi-unlock"></i> Abierta</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($isClosed): ?>
    <!-- Read-only view -->
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> <strong>Esta asignación está cerrada.</strong> 
        Las calificaciones ya han sido registradas y no pueden ser modificadas. 
        Si necesita hacer cambios, contacte al administrador.
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Calificaciones Registradas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Matrícula</th>
                            <th>Estudiante</th>
                            <th>Calificación</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiantes as $e): ?>
                            <tr>
                                <td><?= htmlspecialchars($e['matricula'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($e['apellidos'] . ', ' . $e['nombre']) ?></td>
                                <td>
                                    <?php if ($e['calificacion'] !== null): ?>
                                        <strong><?= number_format($e['calificacion'], 2) ?></strong>
                                    <?php else: ?>
                                        <span class="text-muted">Sin calificación</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($e['observaciones'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Editable grading form -->
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i> <strong>Importante:</strong> 
        Una vez que envíe las calificaciones, la asignación se cerrará y <strong>NO podrá modificarlas</strong>. 
        Asegúrese de revisar todas las calificaciones antes de enviar.
    </div>

    <?php if (empty($estudiantes)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No hay estudiantes inscritos en este grupo.
        </div>
    <?php else: ?>
        <form method="POST" action="<?= BASE_URL ?>/profesor/guardarCalificaciones" 
              onsubmit="return confirm('¿Está seguro? Una vez enviadas, las calificaciones no se pueden modificar.')">
            <input type="hidden" name="id_asignacion" value="<?= $asignacion['id_asignacion'] ?>">
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check"></i> Lista de Estudiantes 
                        <span class="badge bg-secondary"><?= count($estudiantes) ?> total</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="15%">Matrícula</th>
                                    <th width="35%">Estudiante</th>
                                    <th width="20%">Calificación (0-100) <span class="text-danger">*</span></th>
                                    <th width="30%">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($estudiantes as $e): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($e['matricula'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($e['apellidos'] . ', ' . $e['nombre']) ?></td>
                                        <td>
                                            <input type="number" 
                                                   class="form-control" 
                                                   name="calificacion[<?= $e['id_alumno'] ?>]" 
                                                   min="0" 
                                                   max="100" 
                                                   step="0.01"
                                                   placeholder="0-100"
                                                   required>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="observaciones[<?= $e['id_alumno'] ?>]" 
                                                   placeholder="Opcional">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="<?= BASE_URL ?>/profesor/dashboard" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Guardar Calificaciones y Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
<?php endif; ?>

<?php require_once '../app/Views/layouts/footer.php'; ?>
