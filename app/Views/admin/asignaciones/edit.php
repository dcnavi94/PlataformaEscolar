<?php require_once '../app/Views/layouts/header.php'; ?>

<style>
.assignment-card {
    transition: all 0.3s ease;
}
.selector-card {
    border: 2px solid #f0f0f0;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}
.selector-card:hover {
    border-color: #ffc107;
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.2);
}
.info-badge {
    font-size: 0.85rem;
    padding: 0.4rem 0.8rem;
}
</style>

<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Inicio</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/asignacion">Asignaciones</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <!-- Header Card -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body bg-gradient text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1"><i class="bi bi-pencil-square"></i> Editar Asignación</h3>
                            <p class="mb-0 opacity-75">ID: #<?= $asignacion['id_asignacion'] ?> | Creada: <?= date('d/m/Y', strtotime($asignacion['fecha_asignacion'] ?? $asignacion['created_at'])) ?></p>
                        </div>
                        <a href="<?= BASE_URL ?>/asignacion" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <form action="<?= BASE_URL ?>/asignacion/update/<?= $asignacion['id_asignacion'] ?>" method="POST">
                <div class="card assignment-card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 text-primary"><i class="bi bi-diagram-3"></i> Configuración de la Asignación</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Profesor -->
                            <div class="col-md-4">
                                <div class="selector-card rounded p-3 h-100">
                                    <div class="mb-3 text-center">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 60px; height: 60px;">
                                            <i class="bi bi-person-video3 text-primary" style="font-size: 1.8rem;"></i>
                                        </div>
                                    </div>
                                    <label class="form-label fw-bold text-center d-block">
                                        Profesor <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg text-center" name="id_profesor" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($profesores as $prof): ?>
                                            <option value="<?= $prof['id_profesor'] ?>" 
                                                    <?= $prof['id_profesor'] == $asignacion['id_profesor'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($prof['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-muted d-block text-center mt-2">
                                        <i class="bi bi-info-circle"></i> Docente responsable
                                    </small>
                                </div>
                            </div>

                            <!-- Materia -->
                            <div class="col-md-4">
                                <div class="selector-card rounded p-3 h-100">
                                    <div class="mb-3 text-center">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10" style="width: 60px; height: 60px;">
                                            <i class="bi bi-book text-success" style="font-size: 1.8rem;"></i>
                                        </div>
                                    </div>
                                    <label class="form-label fw-bold text-center d-block">
                                        Materia <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg text-center" name="id_materia" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($materias as $mat): ?>
                                            <option value="<?= $mat['id_materia'] ?>" 
                                                    <?= $mat['id_materia'] == $asignacion['id_materia'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($mat['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-muted d-block text-center mt-2">
                                        <i class="bi bi-info-circle"></i> Asignatura a impartir
                                    </small>
                                </div>
                            </div>

                            <!-- Grupo -->
                            <div class="col-md-4">
                                <div class="selector-card rounded p-3 h-100">
                                    <div class="mb-3 text-center">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-info bg-opacity-10" style="width: 60px; height: 60px;">
                                            <i class="bi bi-people-fill text-info" style="font-size: 1.8rem;"></i>
                                        </div>
                                    </div>
                                    <label class="form-label fw-bold text-center d-block">
                                        Grupo <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg text-center" name="id_grupo" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($grupos as $grupo): ?>
                                            <option value="<?= $grupo['id_grupo'] ?>" 
                                                    <?= $grupo['id_grupo'] == $asignacion['id_grupo'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($grupo['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-muted d-block text-center mt-2">
                                        <i class="bi bi-info-circle"></i> Grupo de estudiantes
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estado Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 text-warning"><i class="bi bi-flag-fill"></i> Estado de la Asignación</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mx-auto">
                                <label class="form-label fw-bold">Estado de Calificación</label>
                                <div class="d-grid gap-2">
                                    <div class="form-check p-3 border rounded <?= $asignacion['estado_calificacion'] === 'ABIERTA' ? 'border-warning bg-warning bg-opacity-10' : '' ?>">
                                        <input class="form-check-input" type="radio" name="estado_calificacion" 
                                               id="estadoAbierta" value="ABIERTA" 
                                               <?= $asignacion['estado_calificacion'] === 'ABIERTA' ? 'checked' : '' ?>>
                                        <label class="form-check-label w-100" for="estadoAbierta">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-unlock-fill text-warning me-2" style="font-size: 1.5rem;"></i>
                                                <div>
                                                    <strong>Abierta</strong>
                                                    <small class="d-block text-muted">Las actividades están activas</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check p-3 border rounded <?= $asignacion['estado_calificacion'] === 'CERRADA' ? 'border-secondary bg-secondary bg-opacity-10' : '' ?>">
                                        <input class="form-check-input" type="radio" name="estado_calificacion" 
                                               id="estadoCerrada" value="CERRADA" 
                                               <?= $asignacion['estado_calificacion'] === 'CERRADA' ? 'checked' : '' ?>>
                                        <label class="form-check-label w-100" for="estadoCerrada">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-lock-fill text-secondary me-2" style="font-size: 1.5rem;"></i>
                                                <div>
                                                    <strong>Cerrada</strong>
                                                    <small class="d-block text-muted">Período finalizado</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alert Info -->
                <div class="alert alert-info border-0 shadow-sm mb-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="bi bi-lightbulb-fill" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="alert-heading">Nota importante</h6>
                            <ul class="mb-0 small">
                                <li>Las actividades existentes se mantendrán vinculadas a la asignación</li>
                                <li>Los alumnos del grupo conservarán acceso a las actividades anteriores</li>
                                <li>Verifica que la combinación sea correcta antes de guardar</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= BASE_URL ?>/asignacion" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning btn-lg px-5">
                                <i class="bi bi-check-circle"></i> Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
