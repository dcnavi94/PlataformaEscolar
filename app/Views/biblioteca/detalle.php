<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Detalles del Libro</h1>
        <a href="<?= BASE_URL ?>/biblioteca" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver a la Biblioteca
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <?php if ($libro['portada_url']): ?>
                        <img src="<?= BASE_URL . $libro['portada_url'] ?>" class="img-fluid rounded shadow" alt="<?= htmlspecialchars($libro['titulo']) ?>" style="max-height: 500px;">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center bg-light border rounded shadow" style="height: 400px;">
                            <i class="bi bi-book fa-5x text-secondary"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-8">
                    <h2 class="mb-3 text-primary font-weight-bold"><?= htmlspecialchars($libro['titulo']) ?></h2>
                    <h4 class="mb-4 text-muted">Por: <?= htmlspecialchars($libro['autor']) ?></h4>
                    
                    <div class="mb-4">
                        <h5 class="font-weight-bold">Sinopsis</h5>
                        <p class="lead text-gray-700">
                            <?= nl2br(htmlspecialchars($libro['descripcion'] ?? 'No hay descripción disponible.')) ?>
                        </p>
                    </div>

                    <div class="mt-5">
                    <div class="mt-5">
                        <?php if (!empty($libro['archivo_url'])): ?>
                            <a href="<?= BASE_URL . $libro['archivo_url'] ?>" target="_blank" class="btn btn-primary btn-lg btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="bi bi-download"></i>
                                </span>
                                <span class="text">Leer / Descargar Libro</span>
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-lg btn-icon-split" disabled>
                                <span class="icon text-white-50">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </span>
                                <span class="text">Archivo no disponible</span>
                            </button>
                        <?php endif; ?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
