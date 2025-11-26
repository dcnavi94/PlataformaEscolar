

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestión de Préstamos</h1>
        <a href="/adminprestamos/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Préstamo
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Historial de Préstamos</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="/adminprestamos" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Buscar usuario o material..." value="<?= htmlspecialchars($filters['search']) ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="estado" class="form-control">
                            <option value="">Todos los Estados</option>
                            <option value="ACTIVO" <?= $filters['estado'] == 'ACTIVO' ? 'selected' : '' ?>>Activo</option>
                            <option value="DEVUELTO" <?= $filters['estado'] == 'DEVUELTO' ? 'selected' : '' ?>>Devuelto</option>
                            <option value="VENCIDO" <?= $filters['estado'] == 'VENCIDO' ? 'selected' : '' ?>>Vencido</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary btn-block">Filtrar</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Material</th>
                            <th>Fecha Préstamo</th>
                            <th>Devolución Esperada</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prestamos as $prestamo): ?>
                            <tr>
                                <td><?= htmlspecialchars($prestamo['usuario_nombre']) ?></td>
                                <td><span class="badge badge-info"><?= $prestamo['rol'] ?></span></td>
                                <td><?= htmlspecialchars($prestamo['material']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($prestamo['fecha_prestamo'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($prestamo['fecha_devolucion_esperada'])) ?></td>
                                <td>
                                    <?php
                                    $badgeClass = 'secondary';
                                    if ($prestamo['estado'] == 'ACTIVO') $badgeClass = 'primary';
                                    elseif ($prestamo['estado'] == 'DEVUELTO') $badgeClass = 'success';
                                    elseif ($prestamo['estado'] == 'VENCIDO') $badgeClass = 'danger';
                                    ?>
                                    <span class="badge badge-<?= $badgeClass ?>"><?= $prestamo['estado'] ?></span>
                                </td>
                                <td>
                                    <?php if ($prestamo['estado'] == 'ACTIVO' || $prestamo['estado'] == 'VENCIDO'): ?>
                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#returnModal<?= $prestamo['id_prestamo'] ?>">
                                            <i class="fas fa-check"></i> Devolver
                                        </button>

                                        <!-- Return Modal -->
                                        <div class="modal fade" id="returnModal<?= $prestamo['id_prestamo'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form action="/adminprestamos/returnLoan/<?= $prestamo['id_prestamo'] ?>" method="POST">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Registrar Devolución</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Estado del Material</label>
                                                                <select name="estado" class="form-control">
                                                                    <option value="DEVUELTO">En Buen Estado</option>
                                                                    <option value="DANADO">Dañado</option>
                                                                    <option value="PERDIDO">Perdido</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Observaciones</label>
                                                                <textarea name="observaciones" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-primary">Confirmar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


