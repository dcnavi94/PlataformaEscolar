<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="mb-4">
    <h2>Editar Profesor</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/profesor">Profesores</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>/profesor/update/<?= $profesor['id_profesor'] ?>" autocomplete="off">
            <ul class="nav nav-tabs mb-3" id="profesorTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">Información General</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="hr-tab" data-bs-toggle="tab" data-bs-target="#hr" type="button" role="tab">Recursos Humanos</button>
                </li>
            </ul>

            <div class="tab-content" id="profesorTabsContent">
                <!-- General Info Tab -->
                <div class="tab-pane fade show active" id="general" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?= htmlspecialchars($profesor['nombre']) ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" 
                                   value="<?= htmlspecialchars($profesor['apellidos']) ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($profesor['email']) ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" 
                                   value="<?= htmlspecialchars($profesor['telefono'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="especialidad" class="form-label">Especialidad</label>
                            <input type="text" class="form-control" id="especialidad" name="especialidad" 
                                   value="<?= htmlspecialchars($profesor['especialidad'] ?? '') ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="ACTIVO" <?= $profesor['estado'] === 'ACTIVO' ? 'selected' : '' ?>>ACTIVO</option>
                                <option value="INACTIVO" <?= $profesor['estado'] === 'INACTIVO' ? 'selected' : '' ?>>INACTIVO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- HR Info Tab -->
                <div class="tab-pane fade" id="hr" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo_contrato" class="form-label">Tipo de Contrato</label>
                            <select class="form-select" id="tipo_contrato" name="tipo_contrato">
                                <option value="HONORARIOS" <?= ($profesor['tipo_contrato'] ?? '') === 'HONORARIOS' ? 'selected' : '' ?>>Honorarios</option>
                                <option value="NOMINA" <?= ($profesor['tipo_contrato'] ?? '') === 'NOMINA' ? 'selected' : '' ?>>Nómina</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tarifa_hora" class="form-label">Tarifa por Hora</label>
                            <input type="number" step="0.01" class="form-control" id="tarifa_hora" name="tarifa_hora" 
                                   value="<?= htmlspecialchars($profesor['tarifa_hora'] ?? '0.00') ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="rfc" class="form-label">RFC</label>
                            <input type="text" class="form-control" id="rfc" name="rfc" 
                                   value="<?= htmlspecialchars($profesor['rfc'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="curp" class="form-label">CURP</label>
                            <input type="text" class="form-control" id="curp" name="curp" 
                                   value="<?= htmlspecialchars($profesor['curp'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nss" class="form-label">NSS</label>
                            <input type="text" class="form-control" id="nss" name="nss" 
                                   value="<?= htmlspecialchars($profesor['nss'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="banco" class="form-label">Banco</label>
                            <input type="text" class="form-control" id="banco" name="banco" 
                                   value="<?= htmlspecialchars($profesor['banco'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="clabe" class="form-label">CLABE</label>
                            <input type="text" class="form-control" id="clabe" name="clabe" 
                                   value="<?= htmlspecialchars($profesor['clabe'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Change Section -->
            <div class="card bg-light mb-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-key"></i> Cambiar Contraseña
                    </h6>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="change_password" name="change_password">
                        <label class="form-check-label" for="change_password">
                            Cambiar contraseña del profesor
                        </label>
                    </div>
                    <div id="password_field" style="display: none;">
                        <label>Nueva Contraseña</label>
                        <input type="password" class="form-control" name="new_password" 
                               placeholder="Ingrese la nueva contraseña" minlength="6" autocomplete="new-password">
                        <div class="form-text">
                            Mínimo 6 caracteres. Deja en blanco si no deseas cambiarla.
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('change_password').addEventListener('change', function() {
                    document.getElementById('password_field').style.display = this.checked ? 'block' : 'none';
                });
            </script>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= BASE_URL ?>/profesor" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Actualizar Profesor
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
