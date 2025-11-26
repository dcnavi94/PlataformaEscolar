<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Programas Académicos</h2>
    <a href="<?= BASE_URL ?>/programa/create" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Programa
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Modalidad</th>
                        <th>Colegiatura</th>
                        <th>Inscripción</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($programas)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay programas registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($programas as $programa): ?>
                            <tr>
                                <td><?= $programa['id_programa'] ?></td>
                                <td><strong><?= htmlspecialchars($programa['nombre']) ?></strong></td>
                                <td><?= htmlspecialchars($programa['tipo']) ?></td>
                                <td><?= htmlspecialchars($programa['modalidad']) ?></td>
                                <td>$<?= number_format($programa['monto_colegiatura'], 2) ?></td>
                                <td>$<?= number_format($programa['monto_inscripcion'], 2) ?></td>
                                <td>
                                    <span class="badge bg-<?= $programa['estado'] === 'ACTIVO' ? 'success' : 'secondary' ?>">
                                        <?= $programa['estado'] ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/programa/edit/<?= $programa['id_programa'] ?>" 
                                       class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/programa/delete/<?= $programa['id_programa'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('¿Está seguro de eliminar este programa?')"
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
