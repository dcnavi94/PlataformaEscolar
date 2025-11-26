<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Editar Alumno</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>/alumnoadmin/update/<?= $alumno['id_alumno'] ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nombre(s)</label>
                            <input type="text" class="form-control" name="nombre" 
                                   value="<?= htmlspecialchars($alumno['nombre']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Apellidos</label>
                            <input type="text" class="form-control" name="apellidos" 
                                   value="<?= htmlspecialchars($alumno['apellidos']) ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Matrícula</label>
                            <input type="text" class="form-control" name="matricula" 
                                   value="<?= htmlspecialchars($alumno['matricula'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Correo</label>
                            <input type="email" class="form-control" name="correo" 
                                   value="<?= htmlspecialchars($alumno['correo']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Teléfono</label>
                            <input type="tel" class="form-control" name="telefono" 
                                   value="<?= htmlspecialchars($alumno['telefono']) ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Programa</label>
                            <select class="form-select" name="id_programa" required>
                                <?php foreach ($programas as $p): ?>
                                    <option value="<?= $p['id_programa'] ?>" 
                                            <?= $p['id_programa'] == $alumno['id_programa'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p['nombre']) ?> - <?= htmlspecialchars($p['modalidad']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Grupo</label>
                            <select class="form-select" name="id_grupo" required>
                                <?php foreach ($grupos as $g): ?>
                                    <option value="<?= $g['id_grupo'] ?>" 
                                            <?= $g['id_grupo'] == $alumno['id_grupo'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($g['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Porcentaje de Beca</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" name="porcentaje_beca" 
                                       value="<?= $alumno['porcentaje_beca'] ?>" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Estatus</label>
                            <select class="form-select" name="estatus">
                                <option value="INSCRITO" <?= $alumno['estatus'] === 'INSCRITO' ? 'selected' : '' ?>>Inscrito</option>
                                <option value="BAJA" <?= $alumno['estatus'] === 'BAJA' ? 'selected' : '' ?>>Baja</option>
                                <option value="EGRESADO" <?= $alumno['estatus'] === 'EGRESADO' ? 'selected' : '' ?>>Egresado</option>
                            </select>
                            <div class="form-text text-danger">
                                <i class="bi bi-exclamation-triangle"></i> 
                                Cambiar a BAJA cancelará automáticamente los cargos futuros
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
                                    Cambiar contraseña del alumno
                                </label>
                            </div>
                            <div id="password_field" style="display: none;">
                                <label>Nueva Contraseña</label>
                                <input type="password" class="form-control" name="new_password" 
                                       placeholder="Ingrese la nueva contraseña" minlength="6">
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

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= BASE_URL ?>/alumnoadmin/show/<?= $alumno['id_alumno'] ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
