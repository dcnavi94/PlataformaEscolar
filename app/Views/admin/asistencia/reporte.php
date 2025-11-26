<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Reporte: <?= htmlspecialchars($grupo['nombre']) ?></h1>
        <a href="<?= BASE_URL ?>/asistencia" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Asistencia Mensual</h6>
            <form class="d-flex align-items-center" method="GET">
                <select name="month" class="form-select form-select-sm me-2">
                    <?php for($m=1; $m<=12; $m++): ?>
                        <option value="<?= sprintf('%02d', $m) ?>" <?= $month == sprintf('%02d', $m) ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <select name="year" class="form-select form-select-sm me-2">
                    <?php for($y=date('Y')-1; $y<=date('Y')+1; $y++): ?>
                        <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" style="font-size: 0.8rem;">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start" style="min-width: 200px;">Alumno</th>
                            <?php foreach ($dates as $date): ?>
                                <th><?= $date ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="text-start fw-bold"><?= htmlspecialchars($student['nombre']) ?></td>
                                <?php foreach ($dates as $date): ?>
                                    <td>
                                        <?php if (isset($student['asistencias'][$date])): 
                                            $estado = $student['asistencias'][$date]['estado'];
                                            $color = match($estado) {
                                                'PRESENTE' => 'success',
                                                'AUSENTE' => 'danger',
                                                'RETARDO' => 'warning',
                                                'JUSTIFICADO' => 'info',
                                                default => 'secondary'
                                            };
                                            $icon = match($estado) {
                                                'PRESENTE' => 'check',
                                                'AUSENTE' => 'x',
                                                'RETARDO' => 'clock',
                                                'JUSTIFICADO' => 'file-text',
                                                default => 'question'
                                            };
                                        ?>
                                            <span class="badge bg-<?= $color ?>" title="<?= $student['asistencias'][$date]['materia'] ?>">
                                                <i class="fas fa-<?= $icon ?>"></i>
                                            </span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <small class="text-muted">
                    <span class="badge bg-success"><i class="fas fa-check"></i></span> Presente
                    <span class="badge bg-danger"><i class="fas fa-x"></i></span> Ausente
                    <span class="badge bg-warning"><i class="fas fa-clock"></i></span> Retardo
                    <span class="badge bg-info"><i class="fas fa-file-text"></i></span> Justificado
                </small>
            </div>
        </div>
    </div>
</div>
