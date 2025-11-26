<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Alumnos del Grupo: <?= htmlspecialchars($grupo['nombre']) ?></h2>
    </div>
    <div>
        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addAlumnoModal">
            <i class="bi bi-plus-circle"></i> Agregar Alumno
        </button>
        <a href="<?= BASE_URL ?>/grupo/index" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($alumnos)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No hay alumnos inscritos en este grupo
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($alumnos as $alumno): ?>
                            <tr>
                                <td><?= $alumno['id_alumno'] ?></td>
                                <td><?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellidos']) ?></td>
                                <td><?= htmlspecialchars($alumno['correo']) ?></td>
                                <td><?= htmlspecialchars($alumno['telefono'] ?? '-') ?></td>
                                <td>
                                    <?php
                                    $badgeClass = match($alumno['estatus']) {
                                        'INSCRITO' => 'bg-success',
                                        'BAJA' => 'bg-danger',
                                        'EGRESADO' => 'bg-primary',
                                        'SUSPENDIDO' => 'bg-warning',
                                        default => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= $alumno['estatus'] ?></span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/alumnoadmin/show/<?= $alumno['id_alumno'] ?>" class="btn btn-sm btn-info" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/grupo/removeAlumno/<?= $grupo['id_grupo'] ?>/<?= $alumno['id_alumno'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('¿Está seguro de quitar al alumno del grupo?')"
                                       title="Quitar del Grupo">
                                        <i class="bi bi-x-circle"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Agregar Alumno -->
<div class="modal fade" id="addAlumnoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Alumno al Grupo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>/grupo/addAlumno" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_grupo" value="<?= $grupo['id_grupo'] ?>">
                    
                    <div class="mb-3">
                        <label for="id_alumno" class="form-label">Seleccionar Alumno</label>
                        <select class="form-select" id="id_alumno" name="id_alumno" required>
                            <option value="">Seleccione un alumno...</option>
                            <?php if (empty($disponibles)): ?>
                                <option value="" disabled>No hay alumnos disponibles en este programa</option>
                            <?php else: ?>
                                <?php foreach ($disponibles as $disponible): ?>
                                    <option value="<?= $disponible['id_alumno'] ?>">
                                        <?= htmlspecialchars($disponible['apellidos'] . ' ' . $disponible['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="form-text">Solo se muestran alumnos del mismo programa que no tienen grupo asignado.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
