<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Reporte de Ingresos</h2>
    <a href="?export=csv&fecha_inicio=<?= $fecha_inicio ?>&fecha_fin=<?= $fecha_fin ?>" class="btn btn-success">
        <i class="bi bi-file-earmark-spreadsheet"></i> Exportar CSV
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" class="form-control" value="<?= $fecha_inicio ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha Fin</label>
                <input type="date" name="fecha_fin" class="form-control" value="<?= $fecha_fin ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h6>Total Ingresos</h6>
                <h3>$<?= number_format($totales['total_general'], 2) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h6>Transacciones</h6>
                <h3><?= $totales['total_transacciones'] ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Concepto</th>
                        <th>Método de Pago</th>
                        <th class="text-center">Transacciones</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($resultados)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay ingresos en el periodo seleccionado</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($resultados as $row): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($row['fecha'])) ?></td>
                                <td><?= htmlspecialchars($row['concepto']) ?></td>
                                <td><span class="badge bg-secondary"><?= $row['metodo_pago'] ?></span></td>
                                <td class="text-center"><?= $row['transacciones'] ?></td>
                                <td class="text-end fw-bold">$<?= number_format($row['total'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
