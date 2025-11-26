<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Conceptos de Pago</h2>
    <a href="<?= BASE_URL ?>/concepto/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Concepto</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Monto Default</th>
                    <th>Tolerancia (días)</th>
                    <th>Aplica Beca</th>
                    <th>Recargos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($conceptos as $c): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($c['nombre']) ?></strong><br>
                        <small class="text-muted"><?= htmlspecialchars($c['descripcion']) ?></small>
                    </td>
                    <td>$<?= number_format($c['monto_default'], 2) ?></td>
                    <td><?= $c['dias_tolerancia'] ?></td>
                    <td>
                        <?php if ($c['aplica_beca']): ?>
                            <span class="badge bg-success">Sí</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">No</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($c['recargo_fijo'] > 0): ?>
                            Fixed: $<?= $c['recargo_fijo'] ?><br>
                        <?php endif; ?>
                        <?php if ($c['recargo_porcentaje'] > 0): ?>
                            %: <?= $c['recargo_porcentaje'] ?>%
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= BASE_URL ?>/concepto/edit/<?= $c['id_concepto'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <a href="<?= BASE_URL ?>/concepto/delete/<?= $c['id_concepto'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
