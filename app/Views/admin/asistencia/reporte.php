<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Reporte: <?= htmlspecialchars($grupo['nombre']) ?></h1>
            <p class="text-muted mb-0">Historial de asistencia mensual</p>
        </div>
        <a href="<?= BASE_URL ?>/asistencia" class="btn btn-modern btn-modern-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="card-modern">
        <div class="card-header-modern d-flex justify-content-between align-items-center">
            <h6 class="m-0 text-white"><i class="bi bi-calendar3 me-2"></i>Asistencia Mensual</h6>
            <form class="d-flex align-items-center" method="GET">
                <select name="month" class="form-select form-select-sm me-2 border-0 bg-white text-dark" style="opacity: 0.9;">
                    <?php for($m=1; $m<=12; $m++): ?>
                        <option value="<?= sprintf('%02d', $m) ?>" <?= $month == sprintf('%02d', $m) ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <select name="year" class="form-select form-select-sm me-2 border-0 bg-white text-dark" style="opacity: 0.9;">
                    <?php for($y=date('Y')-1; $y<=date('Y')+1; $y++): ?>
                        <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
                <button type="submit" class="btn btn-light btn-sm fw-bold text-primary">Filtrar</button>
            </form>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive table-modern-container">
                <table class="table table-modern table-sm text-center" style="font-size: 0.85rem;">
                    <thead>
                        <tr>
                            <th class="text-start" style="min-width: 200px;">Alumno</th>
                            <?php foreach ($dates as $date): ?>
                                <th class="text-center"><?= $date ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="text-start fw-bold text-dark"><?= htmlspecialchars($student['nombre']) ?></td>
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
                                            <span class="badge bg-<?= $color ?> rounded-circle p-2" title="<?= $student['asistencias'][$date]['materia'] ?>">
                                                <i class="bi bi-<?= $icon ?>"></i>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 d-flex gap-3 justify-content-center">
                <span class="badge bg-success badge-modern"><i class="bi bi-check me-1"></i> Presente</span>
                <span class="badge bg-danger badge-modern"><i class="bi bi-x me-1"></i> Ausente</span>
                <span class="badge bg-warning badge-modern"><i class="bi bi-clock me-1"></i> Retardo</span>
                <span class="badge bg-info badge-modern"><i class="bi bi-file-text me-1"></i> Justificado</span>
            </div>
        </div>
    </div>
</div>
