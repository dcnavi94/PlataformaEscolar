<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Conceptos de Pago</h1>
            <p class="text-muted mb-0">Catálogo de conceptos cobrables</p>
        </div>
        <a href="<?= BASE_URL ?>/concepto/create" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Concepto
        </a>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h6 class="m-0 text-white"><i class="bi bi-currency-dollar me-2"></i>Listado de Conceptos</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive table-modern-container">
                <table class="table table-modern" id="dataTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th class="text-center">Monto Default</th>
                            <th class="text-center">Tolerancia (días)</th>
                            <th class="text-center">Aplica Beca</th>
                            <th>Recargos</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($conceptos as $c): ?>
                        <tr>
                            <td>
                                <strong class="text-dark"><?= htmlspecialchars($c['nombre']) ?></strong>
                                <div class="text-muted small"><?= htmlspecialchars($c['descripcion']) ?></div>
                            </td>
                            <td class="text-center fw-bold text-success">$<?= number_format($c['monto_default'], 2) ?></td>
                            <td class="text-center"><?= $c['dias_tolerancia'] ?></td>
                            <td class="text-center">
                                <?php if ($c['aplica_beca']): ?>
                                    <span class="badge bg-success badge-modern">Sí</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary badge-modern">No</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($c['recargo_fijo'] > 0): ?>
                                    <div class="small"><span class="fw-bold">Fijo:</span> $<?= $c['recargo_fijo'] ?></div>
                                <?php endif; ?>
                                <?php if ($c['recargo_porcentaje'] > 0): ?>
                                    <div class="small"><span class="fw-bold">%:</span> <?= $c['recargo_porcentaje'] ?>%</div>
                                <?php endif; ?>
                                <?php if ($c['recargo_fijo'] == 0 && $c['recargo_porcentaje'] == 0): ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>/concepto/edit/<?= $c['id_concepto'] ?>" class="btn btn-sm btn-outline-warning rounded-circle me-1" title="Editar"><i class="bi bi-pencil"></i></a>
                                <a href="<?= BASE_URL ?>/concepto/delete/<?= $c['id_concepto'] ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('¿Eliminar?')" title="Eliminar"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
