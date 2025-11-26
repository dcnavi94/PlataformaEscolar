<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Historial de Pagos</h2>
        <?php if (isset($alumno)): ?>
            <p class="text-muted">
                Alumno: <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellidos']) ?>
            </p>
        <?php endif; ?>
    </div>
    <?php if (isset($alumno)): ?>
        <a href="<?= BASE_URL ?>/alumnoadmin/show/<?= $alumno['id_alumno'] ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <?php if (!isset($alumno)): ?>
                            <th>Alumno</th>
                        <?php endif; ?>
                        <th>Concepto</th>
                        <th>Monto</th>
                        <th>Método</th>
                        <th>Referencia</th>
                        <th>Comprobante</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pagos)): ?>
                        <tr>
                            <td colspan="<?= isset($alumno) ? 7 : 8 ?>" class="text-center text-muted">
                                No hay pagos registrados
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pagos as $pago): ?>
                            <tr>
                                <td><?= $pago['id_pago'] ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($pago['fecha_pago'])) ?></td>
                                <?php if (!isset($alumno)): ?>
                                    <td><?= htmlspecialchars($pago['alumno_nombre'] . ' ' . $pago['alumno_apellidos']) ?></td>
                                <?php endif; ?>
                                <td><?= htmlspecialchars($pago['concepto_nombre'] ?? 'N/A') ?></td>
                                <td class="fw-bold text-success">$<?= number_format($pago['monto_total'], 2) ?></td>
                                <td>
                                    <span class="badge bg-info"><?= $pago['metodo_pago'] ?></span>
                                </td>
                                <td><?= htmlspecialchars($pago['referencia'] ?? '-') ?></td>
                                <td>
                                    <?php if ($pago['comprobante_url']): ?>
                                        <a href="<?= BASE_URL . $pago['comprobante_url'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-file-earmark"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/pago/eliminar/<?= $pago['id_pago'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('¿Está seguro de eliminar este pago? Se restaurará el saldo del cargo.')"
                                       title="Eliminar">
                                        <i class="bi bi-trash"></i>
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
