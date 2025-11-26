<div class="mb-4">
    <a href="<?= BASE_URL ?>/alumnoadmin/show/<?= $alumno['id_alumno'] ?>" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Volver al Alumno
    </a>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-cash-coin"></i> Registrar Pago</h5>
            </div>
            <div class="card-body">
                <!-- Student Info -->
                <div class="alert alert-info">
                    <strong>Alumno:</strong> <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellidos']) ?><br>
                    <strong>Concepto:</strong> <?= htmlspecialchars($concepto['nombre']) ?><br>
                    <strong>Monto Total:</strong> $<?= number_format($cargo['monto'], 2) ?><br>
                    <strong>Saldo Pendiente:</strong> <span class="text-danger fw-bold">$<?= number_format($cargo['saldo_pendiente'], 2) ?></span>
                </div>

                <form action="<?= BASE_URL ?>/pago/procesar" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_cargo" value="<?= $cargo['id_cargo'] ?>">

                    <div class="mb-3">
                        <label for="monto" class="form-label">Monto a Pagar *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control" id="monto" name="monto" 
                                   value="<?= $cargo['saldo_pendiente'] ?>" 
                                   max="<?= $cargo['saldo_pendiente'] ?>" 
                                   min="0.01" required>
                        </div>
                        <div class="form-text">
                            Puede ingresar un monto parcial. Máximo: $<?= number_format($cargo['saldo_pendiente'], 2) ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="metodo_pago" class="form-label">Método de Pago *</label>
                        <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                            <option value="EFECTIVO">Efectivo</option>
                            <option value="TRANSFERENCIA">Transferencia Bancaria</option>
                            <option value="DEPOSITO">Depósito Bancario</option>
                            <option value="TARJETA">Tarjeta de Crédito/Débito</option>
                            <option value="PAYPAL">PayPal</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="referencia" class="form-label">Referencia / Número de Operación</label>
                        <input type="text" class="form-control" id="referencia" name="referencia" 
                               placeholder="Número de transferencia, ticket, etc.">
                    </div>

                    <div class="mb-3">
                        <label for="comprobante" class="form-label">Comprobante de Pago (Opcional)</label>
                        <input type="file" class="form-control" id="comprobante" name="comprobante" 
                               accept="image/jpeg,image/jpg,image/png,application/pdf">
                        <div class="form-text">
                            Formatos permitidos: JPG, PNG, PDF. Tamaño máximo: 5MB
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notas" class="form-label">Notas / Observaciones</label>
                        <textarea class="form-control" id="notas" name="notas" rows="3" 
                                  placeholder="Observaciones adicionales sobre este pago"></textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= BASE_URL ?>/alumnoadmin/show/<?= $alumno['id_alumno'] ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Registrar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
