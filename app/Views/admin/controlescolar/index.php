<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-clipboard-data me-2"></i>Control Escolar - Solicitudes</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaSolicitudModal">
        <i class="bi bi-plus-lg me-2"></i>Nueva Solicitud
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Alumno</th>
                        <th>Programa</th>
                        <th>Servicio</th>
                        <th>Fecha</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($solicitudes)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay solicitudes registradas</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($solicitudes as $solicitud): ?>
                            <tr>
                                <td>#<?= $solicitud['id_solicitud'] ?></td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($solicitud['apellidos'] . ' ' . $solicitud['nombre']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($solicitud['correo']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($solicitud['programa_nombre']) ?></td>
                                <td>
                                    <span class="fw-bold"><?= str_replace('_', ' ', $solicitud['tipo_servicio']) ?></span>
                                    <?php if (!empty($solicitud['comentarios'])): ?>
                                        <br><small class="text-muted">"<?= htmlspecialchars($solicitud['comentarios']) ?>"</small>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud'])) ?></td>
                                <td>
                                    <?php
                                    $badgeClass = match($solicitud['estatus']) {
                                        'PENDIENTE' => 'bg-secondary',
                                        'EN_PROCESO' => 'bg-info text-dark',
                                        'ENTREGADO' => 'bg-success',
                                        'RECHAZADO' => 'bg-danger',
                                        default => 'bg-light text-dark'
                                    };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= $solicitud['estatus'] ?></span>
                                </td>
                                <td>
                                    <form action="<?= BASE_URL ?>/controlescolar/updateStatus" method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="id_solicitud" value="<?= $solicitud['id_solicitud'] ?>">
                                        <select name="estatus" class="form-select form-select-sm" style="width: 130px;" onchange="this.form.submit()">
                                            <option value="PENDIENTE" <?= $solicitud['estatus'] == 'PENDIENTE' ? 'selected' : '' ?>>Pendiente</option>
                                            <option value="EN_PROCESO" <?= $solicitud['estatus'] == 'EN_PROCESO' ? 'selected' : '' ?>>En Proceso</option>
                                            <option value="ENTREGADO" <?= $solicitud['estatus'] == 'ENTREGADO' ? 'selected' : '' ?>>Entregado</option>
                                            <option value="RECHAZADO" <?= $solicitud['estatus'] == 'RECHAZADO' ? 'selected' : '' ?>>Rechazado</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

<!-- Modal Nueva Solicitud -->
<div class="modal fade" id="nuevaSolicitudModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Solicitud de Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>/controlescolar/store" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Alumno</label>
                        <select name="id_alumno" class="form-select" required>
                            <option value="">Seleccione un alumno...</option>
                            <?php foreach ($alumnos as $alumno): ?>
                                <option value="<?= $alumno['id_alumno'] ?>">
                                    <?= htmlspecialchars($alumno['apellidos'] . ' ' . $alumno['nombre']) ?> 
                                    (<?= htmlspecialchars($alumno['grupo_nombre'] ?? 'Sin grupo') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de Servicio</label>
                        <select name="tipo_servicio" class="form-select" required>
                            <option value="">Seleccione un servicio...</option>
                            <option value="CONSTANCIA_ESTUDIOS">Constancia de Estudios</option>
                            <option value="KARDEX">Kardex de Calificaciones</option>
                            <option value="BOLETA">Boleta</option>
                            <option value="CERTIFICADO">Certificado Parcial/Total</option>
                            <option value="TITULO">Título Profesional</option>
                            <option value="CREDENCIAL">Reposición de Credencial</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comentarios (Opcional)</label>
                        <textarea name="comentarios" class="form-control" rows="3" placeholder="Detalles adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Solicitud</button>
                </div>
            </form>
        </div>
    </div>
</div>
    </div>
</div>
