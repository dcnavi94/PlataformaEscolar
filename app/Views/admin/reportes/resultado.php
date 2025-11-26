<?php
// app/Views/admin/reportes/resultado.php
?>
<div class="mb-3">
    <a href="<?= BASE_URL ?>/reporte/index" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver al Generador
    </a>
    <div class="btn-group float-end">
        <button onclick="window.print()" class="btn btn-danger">
            <i class="bi bi-file-pdf"></i> Imprimir/PDF
        </button>
        <a href="?<?= http_build_query(array_merge($_GET, ['formato' =>  'excel'])) ?>" class="btn btn-success">
            <i class="bi bi-file-excel"></i> Export Excel
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-file-earmark-bar-graph"></i>
            <?= htmlspecialchars($titulo) ?>
        </h5>
        <small>Generado el: <?= date('d/m/Y H:i') ?></small>
    </div>
    <div class="card-body">
        <?php if (empty($resultados)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No se encontraron resultados con los filtros seleccionados.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="reportTable">
                    <thead class="table-dark">
                        <tr>
                            <?php foreach ($columnas as $columna): ?>
                                <th><?= htmlspecialchars($columna) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultados as $fila): ?>
                            <tr>
                                <?php foreach ($fila as $valor): ?>
                                    <td>
                                        <?php
                                        // Format numeric values
                                        if (is_numeric($valor) && strpos($valor, '.') !== false) {
                                            echo '$' . number_format((float)$valor, 2);
                                        } else {
                                            echo htmlspecialchars($valor);
                                        }
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <?php if (count($resultados) > 10): ?>
                        <tfoot class="table-secondary">
                            <tr>
                                <td colspan="<?= count($columnas) ?>">
                                    <strong>Total de registros:</strong> <?= count($resultados) ?>
                                </td>
                            </tr>
                        </tfoot>
                    <?php endif; ?>
                </table>
            </div>

            <!-- Summary Card for Financial Reports -->
            <?php if (in_array($tipo_reporte, ['ingresos_periodo', 'pagos_por_metodo', 'morosidad'])): ?>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Total Registros</h6>
                                <h3><?= count($resultados) ?></h3>
                            </div>
                        </div>
                    </div>
                    <?php if ($tipo_reporte === 'ingresos_periodo' || $tipo_reporte === 'pagos_por_metodo'): ?>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h6>Total Ingresos</h6>
                                    <h3>
                                        $<?php
                                        $total = 0;
                                        foreach ($resultados as $r) {
                                            $total += $r['total'] ?? 0;
                                        }
                                        echo number_format($total, 2);
                                        ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<style media="print">
    .btn, .float-end, a.btn-secondary {
        display: none !important;
    }
    .card {
        border: none;
        box-shadow: none;
    }
    table {
        page-break-inside: auto;
    }
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
</style>
