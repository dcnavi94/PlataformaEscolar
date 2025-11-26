<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="mb-4">
    <h2>Nueva Asignación</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/asignacion">Asignaciones</a></li>
            <li class="breadcrumb-item active">Nuevo</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> <strong>Asignación de Profesor:</strong> 
            Seleccione el profesor, la materia y el grupo para crear una asignación. 
            El profesor podrá calificar los estudiantes de ese grupo en esa materia.
        </div>

        <form method="POST" action="<?= BASE_URL ?>/asignacion/store">
            <div class="mb-3">
                <label for="id_profesor" class="form-label">Profesor <span class="text-danger">*</span></label>
                <select class="form-select" id="id_profesor" name="id_profesor" required>
                    <option value="">-- Seleccionar Profesor --</option>
                    <?php foreach ($profesores as $p): ?>
                        <option value="<?= $p['id_profesor'] ?>">
                            <?= htmlspecialchars($p['apellidos'] . ', ' . $p['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_materia" class="form-label">Materia <span class="text-danger">*</span></label>
                <select class="form-select" id="id_materia" name="id_materia" required>
                    <option value="">-- Seleccionar Materia --</option>
                    <?php foreach ($materias as $m): ?>
                        <option value="<?= $m['id_materia'] ?>">
                            <?= htmlspecialchars($m['codigo'] . ' - ' . $m['nombre']) ?>
                            <?php if (isset($m['programa_nombre'])): ?>
                                (<?= htmlspecialchars($m['programa_nombre']) ?>)
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_grupo" class="form-label">Grupo <span class="text-danger">*</span></label>
                <select class="form-select" id="id_grupo" name="id_grupo" required>
                    <option value="">-- Seleccionar Grupo --</option>
                    <?php foreach ($grupos as $g): ?>
                        <option value="<?= $g['id_grupo'] ?>">
                            <?= htmlspecialchars($g['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= BASE_URL ?>/asignacion" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Crear Asignación
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
