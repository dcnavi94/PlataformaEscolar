<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Control de Asistencia</h1>

    <div class="row">
        <?php foreach ($asignaciones as $asignacion): ?>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    <?= htmlspecialchars($asignacion['materia_nombre']) ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= htmlspecialchars($asignacion['grupo_nombre']) ?>
                                </div>
                                <div class="mt-2 text-gray-600 small">
                                    <i class="bi bi-upc-scan"></i> <?= htmlspecialchars($asignacion['materia_codigo']) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-calendar-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                        <hr>
                        <a href="<?= BASE_URL ?>/profesorasistencia/tomar/<?= $asignacion['id_asignacion'] ?>" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-check2-square"></i> Tomar Asistencia
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
