<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Mi Horario de Clases</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Horario Semanal</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Día</th>
                            <th>Hora</th>
                            <th>Materia</th>
                            <th>Grupo</th>
                            <th>Aula</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($horarios as $h): ?>
                            <tr>
                                <td><span class="badge bg-success"><?= $h['dia_semana'] ?></span></td>
                                <td><?= substr($h['hora_inicio'], 0, 5) ?> - <?= substr($h['hora_fin'], 0, 5) ?></td>
                                <td><?= htmlspecialchars($h['materia']) ?></td>
                                <td><?= htmlspecialchars($h['grupo']) ?></td>
                                <td><?= htmlspecialchars($h['aula']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($horarios)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay clases asignadas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
