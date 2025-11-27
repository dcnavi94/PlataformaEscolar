<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Biblioteca Digital</h1>
            <p class="text-muted mb-0">Repositorio de recursos educativos</p>
        </div>
        <a href="<?= BASE_URL ?>/bibliotecaadmin/create" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-upload"></i> Subir Libro
        </a>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h6 class="m-0 text-white"><i class="bi bi-book-half me-2"></i>Catálogo de Libros</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive table-modern-container">
                <table class="table table-modern" id="dataTable">
                    <thead>
                        <tr>
                            <th width="80" class="text-center">Portada</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Categoría</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($libros as $libro): ?>
                            <tr>
                                <td class="text-center">
                                    <?php if ($libro['portada_url']): ?>
                                        <img src="<?= BASE_URL . $libro['portada_url'] ?>" alt="Portada" class="shadow-sm" style="height: 60px; width: auto; border-radius: 4px;">
                                    <?php else: ?>
                                        <div class="bg-light d-inline-flex align-items-center justify-content-center rounded" style="width: 45px; height: 60px;">
                                            <i class="bi bi-book text-muted fs-4"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle">
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($libro['titulo']) ?></div>
                                </td>
                                <td class="align-middle text-muted"><?= htmlspecialchars($libro['autor']) ?></td>
                                <td class="align-middle"><span class="badge bg-light text-dark border"><?= htmlspecialchars($libro['categoria_nombre']) ?></span></td>
                                <td class="align-middle text-center">
                                    <a href="<?= BASE_URL . $libro['archivo_url'] ?>" target="_blank" class="btn btn-sm btn-outline-info rounded-circle me-1" title="Ver Archivo">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/bibliotecaadmin/delete/<?= $libro['id_libro'] ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('¿Eliminar este libro?')" title="Eliminar">
                                        <i class="bi bi-trash"></i>
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
