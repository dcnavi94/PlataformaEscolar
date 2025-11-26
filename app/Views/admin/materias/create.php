<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="mb-4">
    <h2>Nueva Materia</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/materia">Materias</a></li>
            <li class="breadcrumb-item active">Nuevo</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>/materia/store">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="codigo" class="form-label">Código <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="codigo" name="codigo" 
                           placeholder="Ej: MAT-101" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           placeholder="Ej: Matemáticas I" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="id_programa" class="form-label">Programa</label>
                    <select class="form-select" id="id_programa" name="id_programa">
                        <option value="">-- Seleccionar Programa --</option>
                        <?php foreach ($programas as $p): ?>
                            <option value="<?= $p['id_programa'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="creditos" class="form-label">Créditos</label>
                    <input type="number" class="form-control" id="creditos" name="creditos" 
                           value="0" min="0" max="20">
                </div>

                <div class="col-md-3 mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="ACTIVO" selected>ACTIVO</option>
                        <option value="INACTIVO">INACTIVO</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= BASE_URL ?>/materia" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar Materia
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
