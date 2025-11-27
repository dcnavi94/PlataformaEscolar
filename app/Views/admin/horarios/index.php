<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Gestión de Horarios</h1>
            <p class="text-muted mb-0">Planificación semanal por grupo</p>
        </div>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h6 class="m-0 text-white"><i class="bi bi-calendar-week me-2"></i>Seleccionar Grupo</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive table-modern-container">
                <table class="table table-modern" id="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Grupo</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grupos as $grupo): ?>
                            <tr>
                                <td class="fw-bold text-secondary">#<?= $grupo['id_grupo'] ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($grupo['nombre']) ?></div>
                                </td>
                                <td class="text-center">
                                    <a href="<?= BASE_URL ?>/horario/edit/<?= $grupo['id_grupo'] ?>" class="btn btn-modern btn-modern-primary btn-sm">
                                        <i class="bi bi-calendar-plus me-2"></i>Gestionar Horario
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
