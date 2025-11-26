<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Biblioteca Digital</h1>
        <a href="<?= BASE_URL ?>/bibliotecaadmin/create" class="btn btn-primary">
            <i class="bi bi-upload"></i> Subir Libro
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th width="80">Portada</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Categoría</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($libros as $libro): ?>
                            <tr>
                                <td class="text-center">
                                    <?php if ($libro['portada_url']): ?>
                                        <img src="<?= BASE_URL . $libro['portada_url'] ?>" alt="Portada" style="height: 60px; width: auto; border-radius: 4px;">
                                    <?php else: ?>
                                        <i class="bi bi-book fa-2x text-gray-300"></i>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle fw-bold"><?= htmlspecialchars($libro['titulo']) ?></td>
                                <td class="align-middle"><?= htmlspecialchars($libro['autor']) ?></td>
                                <td class="align-middle"><span class="badge bg-secondary"><?= htmlspecialchars($libro['categoria_nombre']) ?></span></td>
                                <td class="align-middle">
                                    <a href="<?= BASE_URL . $libro['archivo_url'] ?>" target="_blank" class="btn btn-info btn-sm" title="Ver Archivo">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/bibliotecaadmin/delete/<?= $libro['id_libro'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este libro?')" title="Eliminar">
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
