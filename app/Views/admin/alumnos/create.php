<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-plus"></i> Inscribir Nuevo Alumno</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>/alumnoadmin/store" method="POST">
                    <h6 class="border-bottom pb-2 mb-3">Datos Personales</h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre(s) *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellidos" class="form-label">Apellidos *</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="correo" class="form-label">Correo Electrónico *</label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                            <div class="form-text">Se usará como usuario de acceso</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="matricula" class="form-label">Matrícula</label>
                            <input type="text" class="form-control" id="matricula" name="matricula">
                            <div class="form-text">Opcional. Si se deja vacío se puede asignar después.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Contraseña Inicial</label>
                            <input type="text" class="form-control" id="password" name="password" value="alumno123">
                            <div class="form-text">Por defecto: alumno123</div>
                        </div>
                    </div>

                    <h6 class="border-bottom pb-2 mb-3 mt-4">Datos Académicos</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_programa" class="form-label">Programa *</label>
                            <select class="form-select" id="id_programa" name="id_programa" required>
                                <option value="">Seleccione un programa...</option>
                                <?php foreach ($programas as $p): ?>
                                    <option value="<?= $p['id_programa'] ?>">
                                        <?= htmlspecialchars($p['nombre']) ?> - $<?= number_format($p['monto_colegiatura'], 2) ?>/mes
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="id_grupo" class="form-label">Grupo *</label>
                            <select class="form-select" id="id_grupo" name="id_grupo" required>
                                <option value="">Seleccione un grupo...</option>
                                <?php foreach ($grupos as $g): ?>
                                    <option value="<?= $g['id_grupo'] ?>">
                                        <?= htmlspecialchars($g['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="porcentaje_beca" class="form-label">Porcentaje de Beca</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" 
                                       id="porcentaje_beca" name="porcentaje_beca" value="0" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text">Si aplica, ingrese el porcentaje (0-100)</div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Nota:</strong> Al inscribir al alumno se generarán automáticamente:
                        <ul class="mb-0 mt-2">
                            <li>Cargo de Inscripción (pago único)</li>
                            <li>Primer Colegiatura (mes actual)</li>
                        </ul>
                        El sistema aplicará la beca automáticamente a estos cargos.
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= BASE_URL ?>/alumnoadmin/index" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Inscribir Alumno
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
