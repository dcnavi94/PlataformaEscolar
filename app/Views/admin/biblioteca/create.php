<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Subir Nuevo Libro</h1>
        <a href="<?= BASE_URL ?>/bibliotecaadmin" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detalles del Libro</h6>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>/bibliotecaadmin/store" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="titulo" class="form-label">Título del Libro</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="autor" class="form-label">Autor</label>
                                <input type="text" class="form-control" id="autor" name="autor" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="id_categoria" class="form-label">Categoría</label>
                            <select class="form-select" id="id_categoria" name="id_categoria" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="portada" class="form-label">Imagen de Portada (JPG, PNG)</label>
                                <input type="file" class="form-control" id="portada" name="portada" accept="image/*">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="archivo" class="form-label">Archivo del Libro (PDF, EPUB)</label>
                                <input type="file" class="form-control" id="archivo" name="archivo" accept=".pdf,.epub" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-cloud-upload"></i> Subir Libro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
