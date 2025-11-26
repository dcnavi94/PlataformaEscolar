<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestión de Nómina</h1>
        <div>
            <a href="<?= BASE_URL ?>/nomina/horas" class="btn btn-info me-2">
                <i class="fas fa-clock"></i> Registro de Horas
            </a>
            <a href="<?= BASE_URL ?>/nomina/generar" class="btn btn-primary">
                <i class="fas fa-file-invoice-dollar"></i> Generar Nómina
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Historial de Pagos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Profesor</th>
                            <th>Periodo</th>
                            <th>Horas</th>
                            <th>Monto Neto</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($nominas as $nomina): ?>
                            <tr>
                                <td><?= $nomina['id_nomina'] ?></td>
                                <td><?= htmlspecialchars($nomina['nombre'] . ' ' . $nomina['apellidos']) ?></td>
                                <td><?= $nomina['periodo_inicio'] ?> al <?= $nomina['periodo_fin'] ?></td>
                                <td><?= $nomina['total_horas'] ?></td>
                                <td>$<?= number_format($nomina['monto_neto'], 2) ?></td>
                                <td>
                                    <?php if ($nomina['estado'] === 'PAGADO'): ?>
                                        <span class="badge bg-success">PAGADO</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">PENDIENTE</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($nomina['estado'] === 'PENDIENTE'): ?>
                                        <a href="<?= BASE_URL ?>/nomina/pagar/<?= $nomina['id_nomina'] ?>" class="btn btn-sm btn-success" onclick="return confirm('¿Marcar como pagado?')">
                                            <i class="fas fa-check"></i> Pagar
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
