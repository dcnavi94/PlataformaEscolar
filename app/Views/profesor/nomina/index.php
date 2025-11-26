<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Mis Pagos y Nómina</h1>

    <div class="row">
        <!-- Información Fiscal -->
        <div class="col-md-12 mb-4">
            <div class="card shadow h-100 py-2 border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Información Fiscal
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                RFC: <?= htmlspecialchars($profesor['rfc'] ?? 'N/A') ?> | 
                                Tipo: <?= htmlspecialchars($profesor['tipo_contrato'] ?? 'N/A') ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Historial de Pagos -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recibos de Nómina</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Periodo</th>
                                    <th>Monto Neto</th>
                                    <th>Estado</th>
                                    <th>Fecha Pago</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($nominas as $nomina): ?>
                                    <tr>
                                        <td><?= $nomina['periodo_inicio'] ?> - <?= $nomina['periodo_fin'] ?></td>
                                        <td>$<?= number_format($nomina['monto_neto'], 2) ?></td>
                                        <td>
                                            <?php if ($nomina['estado'] === 'PAGADO'): ?>
                                                <span class="badge bg-success">PAGADO</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">PENDIENTE</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $nomina['fecha_pago'] ?? '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Horas -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Registro de Horas</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Horas</th>
                                    <th>Actividad</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($horas as $h): ?>
                                    <tr>
                                        <td><?= $h['fecha'] ?></td>
                                        <td><?= $h['horas'] ?></td>
                                        <td><?= $h['tipo_actividad'] ?></td>
                                        <td>
                                            <?php if ($h['estado'] === 'APROBADO'): ?>
                                                <span class="badge bg-success">APROBADO</span>
                                            <?php elseif ($h['estado'] === 'PAGADO'): ?>
                                                <span class="badge bg-info">PAGADO</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">PENDIENTE</span>
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
    </div>
</div>
