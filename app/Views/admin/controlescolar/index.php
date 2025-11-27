<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Control Escolar - Solicitudes</h1>
            <p class="text-muted mb-0">Gestión de trámites y documentos</p>
        </div>
        <button type="button" class="btn btn-modern btn-modern-primary" data-bs-toggle="modal" data-bs-target="#nuevaSolicitudModal">
            <i class="bi bi-plus-lg me-2"></i>Nueva Solicitud
        </button>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h6 class="m-0 text-white"><i class="bi bi-clipboard-data me-2"></i>Historial de Solicitudes</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive table-modern-container">
                <table class="table table-modern align-middle" id="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Alumno</th>
                            <th>Programa</th>
                            <th>Servicio</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Estatus</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($solicitudes)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    No hay solicitudes registradas
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($solicitudes as $solicitud): ?>
                                <tr>
                                    <td class="fw-bold text-secondary">#<?= $solicitud['id_solicitud'] ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($solicitud['apellidos'] . ' ' . $solicitud['nombre']) ?></div>
                                        <div class="small text-muted"><?= htmlspecialchars($solicitud['correo']) ?></div>
                                    </td>
                                    <td><small><?= htmlspecialchars($solicitud['programa_nombre']) ?></small></td>
                                    <td>
                                        <span class="fw-bold text-primary"><?= str_replace('_', ' ', $solicitud['tipo_servicio']) ?></span>
                                        <?php if (!empty($solicitud['comentarios'])): ?>
                                            <div class="small text-muted fst-italic mt-1">"<?= htmlspecialchars($solicitud['comentarios']) ?>"</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center text-muted small"><?= date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud'])) ?></td>
                                    <td class="text-center">
                                        <?php
                                        $badgeClass = match($solicitud['estatus']) {
                                            'PENDIENTE' => 'secondary',
                                            'EN_PROCESO' => 'info text-dark',
                                            'ENTREGADO' => 'success',
                                            'RECHAZADO' => 'danger',
                                            default => 'light text-dark'
                                        };
                                        $iconClass = match($solicitud['estatus']) {
                                            'PENDIENTE' => 'hourglass',
                                            'EN_PROCESO' => 'gear-wide-connected',
                                            'ENTREGADO' => 'check-circle',
                                            'RECHAZADO' => 'x-circle',
                                            default => 'circle'
                                        };
                                        ?>
                                        <span class="badge bg-<?= $badgeClass ?> badge-modern">
                                            <i class="bi bi-<?= $iconClass ?> me-1"></i><?= $solicitud['estatus'] ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <form action="<?= BASE_URL ?>/controlescolar/updateStatus" method="POST" class="d-inline-block">
                                            <input type="hidden" name="id_solicitud" value="<?= $solicitud['id_solicitud'] ?>">
                                            <select name="estatus" class="form-select form-select-sm border-0 bg-light fw-bold text-secondary" style="width: 140px; cursor: pointer;" onchange="this.form.submit()">
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
        </div>
    </div>
</div>

<!-- Modal Nueva Solicitud -->
<div class="modal fade" id="nuevaSolicitudModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Nueva Solicitud de Servicio</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>/controlescolar/store" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Alumno</label>
                        <select name="id_alumno" class="form-select form-control-lg" required>
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
                        <label class="form-label fw-bold text-secondary">Tipo de Servicio</label>
                        <select name="tipo_servicio" class="form-select form-control-lg" required>
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
                        <label class="form-label fw-bold text-secondary">Comentarios (Opcional)</label>
                        <textarea name="comentarios" class="form-control" rows="3" placeholder="Detalles adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">Guardar Solicitud</button>
                </div>
            </form>
        </div>
    </div>
</div>
