<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header <?= empty($result['errors']) ? 'bg-success' : 'bg-warning' ?> text-white">
                <h5 class="mb-0">Resultado de la Importación</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h1 class="display-4 text-success"><?= $result['success_count'] ?></h1>
                    <p class="lead">Alumnos importados exitosamente</p>
                </div>

                <?php if (!empty($result['errors'])): ?>
                    <div class="alert alert-danger">
                        <h6><i class="bi bi-exclamation-triangle"></i> Errores encontrados (<?= count($result['errors']) ?>)</h6>
                        <hr>
                        <ul class="mb-0" style="max-height: 200px; overflow-y: auto;">
                            <?php foreach ($result['errors'] as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i> El proceso finalizó sin errores.
                    </div>
                <?php endif; ?>

                <div class="d-grid gap-2 mt-4">
                    <a href="<?= BASE_URL ?>/alumnoadmin/index" class="btn btn-primary">
                        Ir a Lista de Alumnos
                    </a>
                    <a href="<?= BASE_URL ?>/import/index" class="btn btn-outline-secondary">
                        Importar Otro Archivo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
