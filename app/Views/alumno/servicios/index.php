    <div class="col-md-12 mb-4">
        <h2><i class="bi bi-building me-2"></i>Servicios Escolares</h2>
        <p class="text-muted">Gestiona tus trámites y consulta tu información académica.</p>
    </div>
</div>

<div class="row">
    <!-- Formulario de Solicitud -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Nueva Solicitud</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>/servicios/store" method="POST">
                    <div class="mb-3">
                        <label for="tipo_servicio" class="form-label">Tipo de Servicio</label>
                        <select class="form-select" id="tipo_servicio" name="tipo_servicio" required>
                            <option value="">Seleccione...</option>
                            <option value="CONSTANCIA">Constancia de Estudios</option>
                            <option value="KARDEX">Kardex Académico</option>
                            <option value="ACTUALIZACION_DATOS">Actualización de Datos</option>
                            <option value="OTRO">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comentarios" class="form-label">Comentarios Adicionales</label>
                        <textarea class="form-control" id="comentarios" name="comentarios" rows="3" placeholder="Detalles específicos..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enviar Solicitud</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Historial de Solicitudes -->
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Mis Solicitudes</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Servicio</th>
                                <th>Estatus</th>
                                <th>Comentarios</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($solicitudes)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No tienes solicitudes registradas.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($solicitudes as $solicitud): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($solicitud['fecha_solicitud'])) ?></td>
                                        <td><?= str_replace('_', ' ', $solicitud['tipo_servicio']) ?></td>
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
                                        <td><small><?= htmlspecialchars($solicitud['comentarios']) ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
