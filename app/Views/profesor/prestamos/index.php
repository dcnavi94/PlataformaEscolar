

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Mis Materiales y Préstamos</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Historial de Préstamos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>Código</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Devolución</th>
                            <th>Estado</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($prestamos)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No tienes préstamos registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($prestamos as $prestamo): ?>
                                <tr>
                                    <td><?= htmlspecialchars($prestamo['material']) ?></td>
                                    <td><?= htmlspecialchars($prestamo['codigo']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($prestamo['fecha_prestamo'])) ?></td>
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
                                    <td><?= htmlspecialchars($prestamo['observaciones_prestamo']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


