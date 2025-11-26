<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="mb-4">
    <h2>Nuevo Profesor</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/profesor">Profesores</a></li>
            <li class="breadcrumb-item active">Nuevo</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>/profesor/store">
            <h5 class="mb-3">Información Personal</h5>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <small class="text-muted">Se usará para acceder al sistema</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono">
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="especialidad" class="form-label">Especialidad</label>
                    <input type="text" class="form-control" id="especialidad" name="especialidad" 
                           placeholder="Ej: Matemáticas, Física, etc.">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="ACTIVO" selected>ACTIVO</option>
                        <option value="INACTIVO">INACTIVO</option>
                    </select>
                </div>
            </div>

            <hr class="my-4">
            
            <h5 class="mb-3">Cuenta de Usuario</h5>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Se creará automáticamente una cuenta de usuario con rol PROFESOR. 
                La contraseña por defecto será: <strong>profesor123</strong>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña (opcional)</label>
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="Dejar vacío para usar contraseña por defecto">
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= BASE_URL ?>/profesor" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar Profesor
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
