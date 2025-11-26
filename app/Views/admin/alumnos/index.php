<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Alumnos</h2>
    <a href="<?= BASE_URL ?>/alumnoadmin/create" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Inscribir Alumno
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Correo</th>
                        <th>Programa</th>
                        <th>Grupo</th>
                        <th>Beca</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($alumnos)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay alumnos registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($alumnos as $a): ?>
                            <tr>
                                <td><?= $a['id_alumno'] ?></td>
                                <td><strong><?= htmlspecialchars($a['nombre'] . ' ' . $a['apellidos']) ?></strong></td>
                                <td><?= htmlspecialchars($a['correo']) ?></td>
                                <td><?= htmlspecialchars($a['programa_nombre'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($a['grupo_nombre'] ?? 'N/A') ?></td>
                                <td>
                                    <?php if ($a['porcentaje_beca'] > 0): ?>
                                        <span class="badge bg-success"><?= $a['porcentaje_beca'] ?>%</span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $badgeClass = match($a['estatus']) {
                                        'INSCRITO' => 'success',
                                        'BAJA' => 'danger',
                                        'EGRESADO' => 'info',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?>"><?= $a['estatus'] ?></span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/alumnoadmin/show/<?= $a['id_alumno'] ?>" 
                                       class="btn btn-sm btn-info" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/alumnoadmin/edit/<?= $a['id_alumno'] ?>" 
                                       class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/alumnoadmin/delete/<?= $a['id_alumno'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('¿Está seguro de eliminar este alumno?')"
                                       title="Eliminar">
                                        <i class="bi bi-trash"></i>
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
