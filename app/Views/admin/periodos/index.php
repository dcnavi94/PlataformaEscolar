<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Periodos Académicos</h2>
    <a href="<?= BASE_URL ?>/periodo/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Periodo</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr><th>ID</th><th>Nombre</th><th>Periodo</th><th>Fechas</th><th>Año</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($periodos as $p): ?>
                <tr>
                    <td><?= $p['id_periodo'] ?></td>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td><span class="badge bg-info"><?= $p['numero_periodo'] ?></span></td>
                    <td><?= date('d/m/Y', strtotime($p['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($p['fecha_fin'])) ?></td>
                    <td><?= $p['anio'] ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/periodo/edit/<?= $p['id_periodo'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <a href="<?= BASE_URL ?>/periodo/delete/<?= $p['id_periodo'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
