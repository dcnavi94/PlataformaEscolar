<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Horario: <?= htmlspecialchars($grupo['nombre']) ?></h1>
        <a href="<?= BASE_URL ?>/horario" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="row">
        <!-- Formulario -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Agregar Clase</h6>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>/horario/save/<?= $grupo['id_grupo'] ?>" method="POST">
                        <div class="mb-3">
                            <label for="id_asignacion" class="form-label">Materia / Profesor</label>
                            <select class="form-select" id="id_asignacion" name="id_asignacion" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($asignaciones as $a): ?>
                                    <option value="<?= $a['id_asignacion'] ?>">
                                        <?= htmlspecialchars($a['materia_nombre'] . ' - ' . $a['profesor_nombre'] . ' ' . $a['profesor_apellidos']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="dia" class="form-label">Día</label>
                            <select class="form-select" id="dia" name="dia" required>
                                <option value="LUNES">Lunes</option>
                                <option value="MARTES">Martes</option>
                                <option value="MIERCOLES">Miércoles</option>
                                <option value="JUEVES">Jueves</option>
                                <option value="VIERNES">Viernes</option>
                                <option value="SABADO">Sábado</option>
                                <option value="DOMINGO">Domingo</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="inicio" class="form-label">Inicio</label>
                                <input type="time" class="form-control" id="inicio" name="inicio" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fin" class="form-label">Fin</label>
                                <input type="time" class="form-control" id="fin" name="fin" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="aula" class="form-label">Aula / Laboratorio</label>
                            <input type="text" class="form-control" id="aula" name="aula" placeholder="Ej. A-101">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Agregar al Horario</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Visualización Horario -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Horario Semanal</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Hora</th>
                                    <th>Lunes</th>
                                    <th>Martes</th>
                                    <th>Miércoles</th>
                                    <th>Jueves</th>
                                    <th>Viernes</th>
                                    <th>Sábado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Simple list view for now, grid logic is complex for PHP-only rendering without JS library
                                // Ideally this would be a JS calendar, but for MVP we list the entries
                                ?>
                            </tbody>
                        </table>
                        
                        <!-- List View Fallback -->
                        <table class="table table-striped mt-3">
                            <thead>
                                <tr>
                                    <th>Día</th>
                                    <th>Horario</th>
                                    <th>Materia</th>
                                    <th>Profesor</th>
                                    <th>Aula</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($horarios as $h): ?>
                                    <tr>
                                        <td><span class="badge bg-info"><?= $h['dia_semana'] ?></span></td>
                                        <td><?= substr($h['hora_inicio'], 0, 5) ?> - <?= substr($h['hora_fin'], 0, 5) ?></td>
                                        <td><?= htmlspecialchars($h['materia']) ?></td>
                                        <td><?= htmlspecialchars($h['profesor_nombre'] . ' ' . $h['profesor_apellidos']) ?></td>
                                        <td><?= htmlspecialchars($h['aula']) ?></td>
                                        <td>
                                            <a href="<?= BASE_URL ?>/horario/delete/<?= $h['id_horario'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
