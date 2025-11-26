<div class="mb-4">
    <h2><i class="bi bi-person-plus"></i> Crear Usuario Administrativo</h2>
</div>

<form action="<?= BASE_URL ?>/usuarioadmin/store" method="POST" id="formUsuario">
    <div class="row">
        <!-- Datos Personales -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header institutional-navy-bg text-white">
                    <h5 class="mb-0"><i class="bi bi-person-badge"></i> Datos del Usuario</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre *</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Apellidos *</label>
                        <input type="text" class="form-control" name="apellidos" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña *</label>
                        <input type="password" class="form-control" name="password" required minlength="6">
                        <small class="text-muted">Mínimo 6 caracteres</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="telefono">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="activo" id="activo" checked>
                        <label class="form-check-label" for="activo">Usuario Activo</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permisos -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header institutional-orange-bg text-white">
                    <h5 class="mb-0"><i class="bi bi-shield-check"></i> Permisos por Módulo</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <small><i class="bi bi-info-circle"></i> <strong>Lectura</strong>: Ver información del módulo. <strong>Escritura</strong>: Crear/Editar/Eliminar.</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50%;">Módulo</th>
                                    <th class="text-center" style="width: 25%;">
                                        <i class="bi bi-eye"></i> Lectura
                                    </th>
                                    <th class="text-center" style="width: 25%;">
                                        <i class="bi bi-pencil"></i> Escritura
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($modulos as $key => $nombre): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($nombre) ?></strong></td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input permiso-leer" 
                                                   name="permiso_leer_<?= $key ?>" 
                                                   id="leer_<?= $key ?>"
                                                   data-modulo="<?= $key ?>">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input permiso-escribir" 
                                                   name="permiso_escribir_<?= $key ?>" 
                                                   id="escribir_<?= $key ?>"
                                                   data-modulo="<?= $key ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="<?= BASE_URL ?>/usuarioadmin/index" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
        <button type="submit" class="btn institutional-navy-btn btn-lg">
            <i class="bi bi-save"></i> Guardar Usuario
        </button>
    </div>
</form>

<style>
.institutional-navy-btn {
    background-color: #003366;
    border-color: #003366;
    color: white;
}
.institutional-navy-btn:hover {
    background-color: #002244;
    border-color: #002244;
    color: white;
}
</style>

<script>
// Auto-check lectura cuando se marca escritura
document.querySelectorAll('.permiso-escribir').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            const modulo = this.dataset.modulo;
            document.getElementById('leer_' + modulo).checked = true;
        }
    });
});

// Prevent uncheck lectura si escritura está marcado
document.querySelectorAll('.permiso-leer').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        if (!this.checked) {
            const modulo = this.dataset.modulo;
            const escribir = document.getElementById('escribir_' + modulo);
            if (escribir.checked) {
                this.checked = true;
                alert('No puede desmarcar Lectura si Escritura está activa');
            }
        }
    });
});
</script>
