<?php
$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
?>

<div class="mb-4">
    <a href="<?= BASE_URL ?>/alumnoadmin/index" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Volver a Lista
    </a>
</div>

<div class="row">
    <!-- Student Information Sidebar -->
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-circle"></i> Información</h5>
            </div>
            <div class="card-body">
                <h6><?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellidos']) ?></h6>
                <p class="mb-1 small"><i class="bi bi-envelope"></i> <?= htmlspecialchars($alumno['correo']) ?></p>
                <?php if ($alumno['telefono']): ?>
                    <p class="mb-1 small"><i class="bi bi-telephone"></i> <?= htmlspecialchars($alumno['telefono']) ?></p>
                <?php endif; ?>
                
                <hr>
                
                <p class="mb-1 small"><strong>Programa:</strong><br><?= htmlspecialchars($alumno['programa_nombre'] ?? 'N/A') ?></p>
                <p class="mb-1 small"><strong>Grupo:</strong><br><?= htmlspecialchars($alumno['grupo_nombre'] ?? 'N/A') ?></p>
                <p class="mb-1 small"><strong>Beca:</strong> 
                    <?php if ($alumno['porcentaje_beca'] > 0): ?>
                        <span class="badge bg-success"><?= $alumno['porcentaje_beca'] ?>%</span>
                    <?php else: ?>
                        <span class="text-muted">Sin beca</span>
                    <?php endif; ?>
                </p>
                <p class="mb-1 small"><strong>Estatus:</strong> 
                    <span class="badge bg-<?= $alumno['estatus'] === 'INSCRITO' ? 'success' : 'danger' ?>">
                        <?= $alumno['estatus'] ?>
                    </span>
                </p>

                <hr>

                <div class="d-grid">
                    <a href="<?= BASE_URL ?>/alumnoadmin/edit/<?= $alumno['id_alumno'] ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-12 mb-2">
                <div class="card text-center border-warning">
                    <div class="card-body p-2">
                        <h6 class="text-muted mb-0 small">Pendiente</h6>
                        <h5 class="text-warning mb-0">$<?= number_format($totales['pendiente'], 2) ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-2">
                <div class="card text-center border-danger">
                    <div class="card-body p-2">
                        <h6 class="text-muted mb-0 small">Vencido</h6>
                        <h5 class="text-danger mb-0">$<?= number_format($totales['vencido'], 2) ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-2">
                <div class="card text-center border-success">
                    <div class="card-body p-2">
                        <h6 class="text-muted mb-0 small">Pagado</h6>
                        <h5 class="text-success mb-0">$<?= number_format($totales['pagado'], 2) ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabbed Content -->
    <div class="col-md-9">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-3" id="alumnoTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pagos-tab" data-bs-toggle="tab" data-bs-target="#pagos" type="button">
                    <i class="bi bi-credit-card"></i> Pagos y Cargos
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="calificaciones-tab" data-bs-toggle="tab" data-bs-target="#calificaciones" type="button">
                    <i class="bi bi-star"></i> Calificaciones
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="progreso-tab" data-bs-toggle="tab" data-bs-target="#progreso" type="button">
                    <i class="bi bi-graph-up"></i> Progreso Académico
                </button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="alumnoTabsContent">
            <!-- Pagos Tab -->
            <div class="tab-pane fade show active" id="pagos" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Estado de Cuenta</h5>
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#pagoManualModal">
                            <i class="bi bi-plus-circle"></i> Registrar Pago Manual
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Concepto</th>
                                        <th>Mes</th>
                                        <th>Periodo</th>
                                        <th>Monto</th>
                                        <th>Saldo</th>
                                        <th>Vencimiento</th>
                                        <th>Estatus</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($cargos)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No hay cargos registrados</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($cargos as $cargo): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($cargo['concepto_nombre']) ?></td>
                                                <td><?= $meses[$cargo['mes']] ?? $cargo['mes'] ?></td>
                                                <td><?= htmlspecialchars($cargo['periodo_nombre'] ?? '-') ?></td>
                                                <td>$<?= number_format($cargo['monto'], 2) ?></td>
                                                <td>$<?= number_format($cargo['saldo_pendiente'], 2) ?></td>
                                                <td><?= date('d/m/Y', strtotime($cargo['fecha_limite'])) ?></td>
                                                <td>
                                                    <?php
                                                    $badgeClass = match($cargo['estatus']) {
                                                        'PAGADO' => 'success',
                                                        'PENDIENTE' => 'warning',
                                                        'VENCIDO' => 'danger',
                                                        'PENALIZACION' => 'dark',
                                                        'PARCIAL' => 'info',
                                                        default => 'secondary'
                                                    };
                                                    ?>
                                                    <span class="badge bg-<?= $badgeClass ?>"><?= $cargo['estatus'] ?></span>
                                                </td>
                                                <td>
                                                    <?php if ($cargo['estatus'] !== 'PAGADO' && $cargo['estatus'] !== 'CANCELADO'): ?>
                                                        <a href="<?= BASE_URL ?>/pago/registrar/<?= $cargo['id_cargo'] ?>" 
                                                           class="btn btn-sm btn-success" title="Registrar Pago">
                                                            <i class="bi bi-cash"></i>
                                                        </a>
                                                    <?php endif; ?>
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

            <!-- Calificaciones Tab -->
            <div class="tab-pane fade" id="calificaciones" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-star"></i> Historial de Calificaciones</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($calificaciones)): ?>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> No hay calificaciones registradas aún.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Materia</th>
                                            <th>Actividad</th>
                                            <th>Tipo</th>
                                            <th>Calificación</th>
                                            <th>Fecha</th>
                                            <th>Retroalimentación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($calificaciones as $cal): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($cal['materia_nombre']) ?></strong></td>
                                                <td><?= htmlspecialchars($cal['actividad_titulo']) ?></td>
                                                <td><span class="badge bg-secondary"><?= $cal['tipo'] ?></span></td>
                                                <td>
                                                    <strong class="text-primary">
                                                        <?= number_format($cal['calificacion'], 2) ?> / <?= $cal['puntos_max'] ?>
                                                    </strong>
                                                    <?php 
                                                    $porcentaje = ($cal['calificacion'] / $cal['puntos_max']) * 100;
                                                    if ($porcentaje >= 70): ?>
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    <?php else: ?>
                                                        <i class="bi bi-exclamation-circle-fill text-danger"></i>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($cal['fecha_entrega'])) ?></td>
                                                <td>
                                                    <?php if ($cal['retroalimentacion']): ?>
                                                        <small><?= htmlspecialchars(substr($cal['retroalimentacion'], 0, 50)) ?>...</small>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Progreso Tab -->
            <div class="tab-pane fade" id="progreso" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Progreso por Materia</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($progreso)): ?>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> No hay progreso académico registrado aún.
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($progreso as $prog): ?>
                                    <?php 
                                    $porcentaje = $prog['total_actividades'] > 0 ? 
                                        ($prog['calificadas'] / $prog['total_actividades']) * 100 : 0;
                                    $promedio = $prog['promedio'] ?? 0;
                                    ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6 class="card-title"><?= htmlspecialchars($prog['materia_nombre']) ?></h6>
                                                
                                                <div class="mb-2">
                                                    <small class="text-muted">Progreso de Entregas</small>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar <?= $porcentaje >= 70 ? 'bg-success' : ($porcentaje >= 50 ? 'bg-warning' : 'bg-danger') ?>" 
                                                             role="progressbar" 
                                                             style="width: <?= $porcentaje ?>%">
                                                            <?= round($porcentaje) ?>%
                                                        </div>
                                                    </div>
                                                    <small><?= $prog['calificadas'] ?> de <?= $prog['total_actividades'] ?> actividades</small>
                                                </div>

                                                <?php if ($promedio > 0): ?>
                                                    <div class="mt-3">
                                                        <small class="text-muted">Promedio</small>
                                                        <h4 class="mb-0 <?= $promedio >= 70 ? 'text-success' : ($promedio >= 60 ? 'text-warning' : 'text-danger') ?>">
                                                            <?= number_format($promedio, 1) ?>
                                                        </h4>
                                                    </div>
                                                <?php else: ?>
                                                    <p class="text-muted mb-0"><small>Sin calificaciones aún</small></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Pago Manual -->
<div class="modal fade" id="pagoManualModal" tabindex="-1" aria-labelledby="pagoManualModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>/pago/registrarManual" method="POST">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="pagoManualModalLabel">
                        <i class="bi bi-cash-coin"></i> Registrar Pago Manual
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_alumno" value="<?= $alumno['id_alumno'] ?>">
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Alumno:</strong> <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellidos']) ?>
                    </div>

                    <div class="mb-3">
                        <label for="id_concepto" class="form-label">Concepto de Pago *</label>
                        <select class="form-select" id="id_concepto" name="id_concepto" required>
                            <option value="">Seleccione un concepto...</option>
                            <?php foreach ($conceptos as $concepto): ?>
                                <option value="<?= $concepto['id_concepto'] ?>">
                                    <?= htmlspecialchars($concepto['nombre']) ?>
                                    <?php if ($concepto['monto_default'] > 0): ?>
                                        - $<?= number_format($concepto['monto_default'], 2) ?>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Seleccione el tipo de pago que está registrando</small>
                    </div>

                    <div class="mb-3">
                        <label for="monto_pago" class="form-label">Monto *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="monto_pago" name="monto" 
                                   step="0.01" min="0.01" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="metodo_pago" class="form-label">Método de Pago *</label>
                        <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                            <option value="">Seleccione método...</option>
                            <option value="EFECTIVO">Efectivo</option>
                            <option value="TARJETA">Tarjeta</option>
                            <option value="TRANSFER">Transferencia</option>
                            <option value="CHEQUE">Cheque</option>
                            <option value="DEPOSITO">Depósito</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="referencia_pago" class="form-label">Referencia / Folio</label>
                        <input type="text" class="form-control" id="referencia_pago" name="referencia_pago" 
                               placeholder="Número de referencia, folio o comprobante">
                        <small class="text-muted">Opcional: Número de transacción, folio de recibo, etc.</small>
                    </div>

                    <div class="mb-3">
                        <label for="notas_pago" class="form-label">Notas / Observaciones</label>
                        <textarea class="form-control" id="notas_pago" name="notas" rows="2" 
                                  placeholder="Información adicional sobre el pago"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Registrar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
