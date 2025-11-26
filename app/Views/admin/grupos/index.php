<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Grupos</h2>
    <a href="<?= BASE_URL ?>/grupo/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Grupo</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr><th>ID</th><th>Nombre</th><th>Programa</th><th>Periodo</th><th># Alumnos</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($grupos as $g): ?>
                <tr>
                    <td><?= $g['id_grupo'] ?></td>
                    <td><strong><?= htmlspecialchars($g['nombre']) ?></strong></td>
                    <td><?= htmlspecialchars($g['programa_nombre']) ?></td>
                    <td><?= htmlspecialchars($g['periodo_nombre']) ?></td>
                    <td><span class="badge bg-primary"><?= $g['num_alumnos'] ?></span></td>
                    <td><span class="badge bg-<?= $g['estado'] === 'ACTIVO' ? 'success' : 'secondary' ?>"><?= $g['estado'] ?></span></td>
                    <td>
                        <a href="<?= BASE_URL ?>/grupo/alumnos/<?= $g['id_grupo'] ?>" class="btn btn-sm btn-info"><i class="bi bi-people"></i></a>
                        <a href="<?= BASE_URL ?>/grupo/edit/<?= $g['id_grupo'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <a href="<?= BASE_URL ?>/grupo/delete/<?= $g['id_grupo'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
