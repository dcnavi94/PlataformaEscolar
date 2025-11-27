

<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Gestión de Préstamos</h1>
            <p class="text-muted mb-0">Control de material y equipos</p>
        </div>
        <a href="/adminprestamos/create" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle me-2"></i>Nuevo Préstamo
        </a>
    </div>

    <div class="card-modern">
        <div class="card-header-modern d-flex justify-content-between align-items-center">
            <h6 class="m-0 text-white"><i class="bi bi-clock-history me-2"></i>Historial de Préstamos</h6>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="/adminprestamos" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar usuario o material..." value="<?= htmlspecialchars($filters['search']) ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="estado" class="form-select">
                            <option value="">Todos los Estados</option>
                            <option value="ACTIVO" <?= $filters['estado'] == 'ACTIVO' ? 'selected' : '' ?>>Activo</option>
                            <option value="DEVUELTO" <?= $filters['estado'] == 'DEVUELTO' ? 'selected' : '' ?>>Devuelto</option>
                            <option value="VENCIDO" <?= $filters['estado'] == 'VENCIDO' ? 'selected' : '' ?>>Vencido</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100 fw-bold">Filtrar</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive table-modern-container">
                <table class="table table-modern align-middle" id="dataTable">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Material</th>
                            <th class="text-center">Fecha Préstamo</th>
                            <th class="text-center">Devolución Esperada</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prestamos as $prestamo): ?>
                            <tr>
                                <td class="fw-bold text-dark"><?= htmlspecialchars($prestamo['usuario_nombre']) ?></td>
                                <td><span class="badge bg-info badge-modern"><?= $prestamo['rol'] ?></span></td>
                                <td><?= htmlspecialchars($prestamo['material']) ?></td>
                                <td class="text-center text-muted small"><?= date('d/m/Y H:i', strtotime($prestamo['fecha_prestamo'])) ?></td>
                                <td class="text-center text-muted small"><?= date('d/m/Y', strtotime($prestamo['fecha_devolucion_esperada'])) ?></td>
                                <td class="text-center">
                                    <?php
                                    $badgeClass = match($prestamo['estado']) {
                                        'ACTIVO' => 'primary',
                                        'DEVUELTO' => 'success',
                                        'VENCIDO' => 'danger',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?> badge-modern"><?= $prestamo['estado'] ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($prestamo['estado'] == 'ACTIVO' || $prestamo['estado'] == 'VENCIDO'): ?>
                                        <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#returnModal<?= $prestamo['id_prestamo'] ?>">
                                            <i class="bi bi-check-circle me-1"></i> Devolver
                                        </button>

                                        <!-- Return Modal -->
                                        <div class="modal fade" id="returnModal<?= $prestamo['id_prestamo'] ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg">
                                                    <form action="/adminprestamos/returnLoan/<?= $prestamo['id_prestamo'] ?>" method="POST">
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title fw-bold"><i class="bi bi-check-circle me-2"></i>Registrar Devolución</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body p-4 text-start">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold text-secondary">Estado del Material</label>
                                                                <select name="estado" class="form-select form-control-lg">
                                                                    <option value="DEVUELTO">En Buen Estado</option>
                                                                    <option value="DANADO">Dañado</option>
                                                                    <option value="PERDIDO">Perdido</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold text-secondary">Observaciones</label>
                                                                <textarea name="observaciones" class="form-control" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-success px-4">Confirmar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
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


