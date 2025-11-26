<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Reporte de Alumnos con Adeudos</h2>
    <a href="?export=csv&grupo_id=<?= $filtros['grupo_id'] ?>&periodo_id=<?= $filtros['periodo_id'] ?>" class="btn btn-success">
        <i class="bi bi-file-earmark-spreadsheet"></i> Exportar CSV
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Filtrar por Grupo</label>
                <select name="grupo_id" class="form-select">
                    <option value="">Todos los grupos</option>
                    <?php foreach ($grupos as $g): ?>
                        <option value="<?= $g['id_grupo'] ?>" <?= $filtros['grupo_id'] == $g['id_grupo'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Grupo</th>
                        <th>Programa</th>
                        <th class="text-center">Cargos Pendientes</th>
                        <th class="text-end">Deuda Total</th>
                        <th>Deuda Más Antigua</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($resultados)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No se encontraron alumnos con adeudos</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($resultados as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['alumno']) ?></td>
                                <td><?= htmlspecialchars($row['grupo']) ?></td>
                                <td><?= htmlspecialchars($row['programa']) ?></td>
                                <td class="text-center"><span class="badge bg-danger"><?= $row['cargos_pendientes'] ?></span></td>
                                <td class="text-end fw-bold text-danger">$<?= number_format($row['total_deuda'], 2) ?></td>
                                <td><?= date('d/m/Y', strtotime($row['deuda_mas_antigua'])) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>/alumnoadmin/show/<?= $row['id_alumno'] ?>" class="btn btn-sm btn-info" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
