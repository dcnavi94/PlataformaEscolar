<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-credit-card"></i> Mis Pagos y Colegiaturas</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> 
                    Los pagos realizados con PayPal incluyen una comisión del <strong>4%</strong> por servicio.
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Concepto</th>
                                <th>Vencimiento</th>
                                <th>Monto Original</th>
                                <th>Saldo Pendiente</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($cargos)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No tienes cargos registrados</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($cargos as $cargo): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($cargo['concepto_nombre']) ?></strong><br>
                                            <small class="text-muted"><?= $cargo['mes'] ?>/<?= $cargo['anio'] ?></small>
                                        </td>
                                        <td>
                                            <?php 
                                            $fechaLimite = strtotime($cargo['fecha_limite']);
                                            $hoy = time();
                                            $colorFecha = $fechaLimite < $hoy && $cargo['saldo_pendiente'] > 0 ? 'text-danger fw-bold' : '';
                                            ?>
                                            <span class="<?= $colorFecha ?>">
                                                <?= date('d/m/Y', $fechaLimite) ?>
                                            </span>
                                        </td>
                                        <td>$<?= number_format($cargo['monto'], 2) ?></td>
                                        <td class="fw-bold">$<?= number_format($cargo['saldo_pendiente'], 2) ?></td>
                                        <td>
                                            <?php
                                            $badgeClass = match($cargo['estatus']) {
                                                'PAGADO' => 'success',
                                                'PENDIENTE' => 'warning',
                                                'VENCIDO' => 'danger',
                                                'PENALIZACION' => 'dark',
                                                'PARCIAL' => 'info',
                                                default => 'secondary'
                                            };
                                            ?>
                                            <span class="badge bg-<?= $badgeClass ?>"><?= $cargo['estatus'] ?></span>
                                        </td>
                                        <td>
                                            <?php if ($cargo['saldo_pendiente'] > 0 && $cargo['estatus'] !== 'CANCELADO'): ?>
                                                <button class="btn btn-primary btn-sm btn-pagar" 
                                                        data-id="<?= $cargo['id_cargo'] ?>"
                                                        data-concepto="<?= htmlspecialchars($cargo['concepto_nombre']) ?>"
                                                        data-monto="<?= $cargo['saldo_pendiente'] ?>">
                                                    <i class="bi bi-paypal"></i> Pagar
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    <i class="bi bi-check-circle"></i> Pagado
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Pago PayPal -->
<div class="modal fade" id="paypalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Realizar Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h6>Estás a punto de pagar:</h6>
                    <h4 id="modalConcepto" class="text-primary">Colegiatura</h4>
                </div>
                
                <ul class="list-group mb-4">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Subtotal (Saldo Pendiente)
                        <span id="modalSubtotal">$0.00</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Comisión PayPal (4%)
                        <span id="modalComision" class="text-danger">+$0.00</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center fw-bold bg-light">
                        Total a Pagar
                        <span id="modalTotal" class="fs-5">$0.00</span>
                    </li>
                </ul>

                <div id="paypal-button-container"></div>
            </div>
        </div>
    </div>
</div>

<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=<?= $paypal_client_id ?>&currency=MXN"></script>

<script>
    let currentCargoId = null;
    let currentTotal = 0;

    // Configuración de botones de pago
    document.querySelectorAll('.btn-pagar').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const concepto = this.dataset.concepto;
            const monto = parseFloat(this.dataset.monto);
            
            // Calcular comisión (4%)
            const comision = monto * 0.04;
            const total = monto + comision;

            currentCargoId = id;
            currentTotal = total;

            // Actualizar modal
            document.getElementById('modalConcepto').textContent = concepto;
            document.getElementById('modalSubtotal').textContent = '$' + monto.toFixed(2);
            document.getElementById('modalComision').textContent = '$' + comision.toFixed(2);
            document.getElementById('modalTotal').textContent = '$' + total.toFixed(2);

            // Limpiar contenedor anterior si existe
            document.getElementById('paypal-button-container').innerHTML = '';

            // Renderizar botón de PayPal
            renderPaypalButton(total);

            // Mostrar modal
            new bootstrap.Modal(document.getElementById('paypalModal')).show();
        });
    });

    function renderPaypalButton(amount) {
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: amount.toFixed(2)
                        },
                        description: 'Pago de Cargo ID: ' + currentCargoId
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Llamada al backend para registrar el pago
                    registrarPagoBackend(details);
                });
            },
            onError: function(err) {
                console.error(err);
                alert('Ocurrió un error con el proceso de pago.');
            }
        }).render('#paypal-button-container');
    }

    function registrarPagoBackend(details) {
        // Mostrar loading
        const modalBody = document.querySelector('.modal-body');
        modalBody.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2">Registrando pago...</p></div>';

        fetch('<?= BASE_URL ?>/pago/confirmarPaypal', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                orderID: details.id,
                cargoId: currentCargoId,
                details: details
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Error al registrar el pago en el sistema: ' + data.message);
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
            window.location.reload();
        });
    }
</script>
