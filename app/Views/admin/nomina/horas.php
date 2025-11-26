<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Registro de Horas</h1>

    <div class="row">
        <!-- Formulario de Registro -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Registrar Horas</h6>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>/nomina/horas" method="POST">
                        <div class="mb-3">
                            <label for="id_profesor" class="form-label">Profesor</label>
                            <select class="form-select" id="id_profesor" name="id_profesor" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($profesores as $profesor): ?>
                                    <option value="<?= $profesor['id_profesor'] ?>">
                                        <?= htmlspecialchars($profesor['apellidos'] . ' ' . $profesor['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>
                        <div class="mb-3">
                            <label for="horas" class="form-label">Horas</label>
                            <input type="number" step="0.5" class="form-control" id="horas" name="horas" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_actividad" class="form-label">Actividad</label>
                            <select class="form-select" id="tipo_actividad" name="tipo_actividad">
                                <option value="CLASE">Clase</option>
                                <option value="ADMINISTRATIVO">Administrativo</option>
                                <option value="TALLER">Taller</option>
                                <option value="OTRO">Otro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Registrar</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Lista de Pendientes -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Horas Pendientes de Aprobación</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Profesor</th>
                                    <th>Fecha</th>
                                    <th>Horas</th>
                                    <th>Actividad</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pending as $p): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellidos']) ?></td>
                                        <td><?= $p['fecha'] ?></td>
                                        <td><?= $p['horas'] ?></td>
                                        <td><?= $p['tipo_actividad'] ?></td>
                                        <td>
                                            <form action="<?= BASE_URL ?>/nomina/horas" method="POST" style="display:inline;">
                                                <input type="hidden" name="approve_id" value="<?= $p['id_registro'] ?>">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Aprobar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($pending)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No hay registros pendientes.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
