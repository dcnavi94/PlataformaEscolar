<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Asistencia: <?= htmlspecialchars($asignacion['materia_nombre']) ?> (<?= htmlspecialchars($asignacion['grupo_nombre']) ?>)</h1>
        <a href="<?= BASE_URL ?>/profesorasistencia" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Alumnos</h6>
            <form class="d-flex align-items-center" method="GET">
                <label class="me-2 mb-0">Fecha:</label>
                <input type="date" name="fecha" class="form-control form-control-sm" value="<?= $fecha ?>" onchange="this.form.submit()">
            </form>
        </div>
        <div class="card-body">
            <form action="<?= BASE_URL ?>/profesorasistencia/save/<?= $asignacion['id_asignacion'] ?>" method="POST">
                <input type="hidden" name="fecha" value="<?= $fecha ?>">
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Alumno</th>
                                <th class="text-center" width="100">Presente</th>
                                <th class="text-center" width="100">Ausente</th>
                                <th class="text-center" width="100">Retardo</th>
                                <th class="text-center" width="100">Justificado</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alumnos as $alumno): 
                                $estado = $asistenciaMap[$alumno['id_alumno']]['estado'] ?? 'PRESENTE'; // Default to Presente
                                $obs = $asistenciaMap[$alumno['id_alumno']]['observaciones'] ?? '';
                            ?>
                                <tr>
                                    <td class="align-middle">
                                        <?= htmlspecialchars($alumno['apellidos'] . ' ' . $alumno['nombre']) ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" name="asistencia[<?= $alumno['id_alumno'] ?>]" value="PRESENTE" <?= $estado === 'PRESENTE' ? 'checked' : '' ?>>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" name="asistencia[<?= $alumno['id_alumno'] ?>]" value="AUSENTE" <?= $estado === 'AUSENTE' ? 'checked' : '' ?>>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" name="asistencia[<?= $alumno['id_alumno'] ?>]" value="RETARDO" <?= $estado === 'RETARDO' ? 'checked' : '' ?>>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" name="asistencia[<?= $alumno['id_alumno'] ?>]" value="JUSTIFICADO" <?= $estado === 'JUSTIFICADO' ? 'checked' : '' ?>>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="observaciones[<?= $alumno['id_alumno'] ?>]" value="<?= htmlspecialchars($obs) ?>" placeholder="Opcional">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-save"></i> Guardar Asistencia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
