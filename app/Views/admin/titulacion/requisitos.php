<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="bi bi-list-check"></i> Requisitos de Titulación</h2>
        </div>
        <div class="col-md-6 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoRequisito">
                <i class="bi bi-plus-circle"></i> Nuevo Requisito
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Programa</th>
                                    <th>Requisito</th>
                                    <th>Tipo Documento</th>
                                    <th>Obligatorio</th>
                                    <th>Orden</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($requisitos as $req): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($req['programa_nombre']) ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($req['nombre_requisito']) ?></strong><br>
                                            <small class="text-muted"><?= htmlspecialchars($req['descripcion']) ?></small>
                                        </td>
                                        <td><?= $req['tipo_documento'] ?></td>
                                        <td>
                                            <?php if ($req['es_obligatorio']): ?>
                                                <span class="badge bg-danger">Obligatorio</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Opcional</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $req['orden'] ?></td>
                                        <td>
                                            <?php if ($req['activo']): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" 
                                                    onclick="editarRequisito(<?= htmlspecialchars(json_encode($req)) ?>)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <a href="<?= BASE_URL ?>/titulacionadmin/eliminarRequisito/<?= $req['id_requisito'] ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('¿Eliminar este requisito?')">
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
    </div>
</div>

<!-- Modal Nuevo Requisito -->
<div class="modal fade" id="modalNuevoRequisito" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= BASE_URL ?>/titulacionadmin/crearRequisito">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Requisito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Programa *</label>
                        <select name="id_programa" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($programas as $prog): ?>
                                <option value="<?= $prog['id_programa'] ?>"><?= htmlspecialchars($prog['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre del Requisito *</label>
                        <input type="text" name="nombre_requisito" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de Documento</label>
                        <select name="tipo_documento" class="form-select">
                            <option value="PDF">PDF</option>
                            <option value="IMAGEN">Imagen</option>
                            <option value="AMBOS">Ambos</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Orden</label>
                        <input type="number" name="orden" class="form-control" value="0">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="es_obligatorio" class="form-check-input" id="esObligatorio" checked>
                        <label class="form-check-label" for="esObligatorio">Es obligatorio</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Requisito -->
<div class="modal fade" id="modalEditarRequisito" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="formEditarRequisito">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Requisito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre del Requisito *</label>
                        <input type="text" name="nombre_requisito" id="edit_nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de Documento</label>
                        <select name="tipo_documento" id="edit_tipo" class="form-select">
                            <option value="PDF">PDF</option>
                            <option value="IMAGEN">Imagen</option>
                            <option value="AMBOS">Ambos</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Orden</label>
                        <input type="number" name="orden" id="edit_orden" class="form-control">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="es_obligatorio" class="form-check-input" id="edit_obligatorio">
                        <label class="form-check-label" for="edit_obligatorio">Es obligatorio</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editarRequisito(req) {
    document.getElementById('formEditarRequisito').action = '<?= BASE_URL ?>/titulacionadmin/editarRequisito/' + req.id_requisito;
    document.getElementById('edit_nombre').value = req.nombre_requisito;
    document.getElementById('edit_descripcion').value = req.descripcion || '';
    document.getElementById('edit_tipo').value = req.tipo_documento;
    document.getElementById('edit_orden').value = req.orden;
    document.getElementById('edit_obligatorio').checked = req.es_obligatorio == 1;
    new bootstrap.Modal(document.getElementById('modalEditarRequisito')).show();
}
</script>

<?php require_once '../app/Views/layouts/footer.php'; ?>
