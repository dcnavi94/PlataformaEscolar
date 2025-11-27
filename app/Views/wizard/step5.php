<?php $current_step = 5; require 'header.php'; ?>

<h3 class="mb-4"><i class="bi bi-check-circle"></i> Resumen y Finalización</h3>
<p class="text-muted">Revisa la configuración antes de finalizar.</p>

<div class="accordion mb-4" id="summaryAccordion">
    <!-- Step 1 Summary -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                <i class="bi bi-building me-2"></i> Datos de la Institución
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#summaryAccordion">
            <div class="accordion-body">
                <table class="table table-sm">
                    <tr><th width="30%">Nombre:</th><td><?= htmlspecialchars($step1['nombre_institucion'] ?? 'No configurado') ?></td></tr>
                    <tr><th>Teléfono:</th><td><?= htmlspecialchars($step1['telefono'] ?? 'N/A') ?></td></tr>
                    <tr><th>Email:</th><td><?= htmlspecialchars($step1['email'] ?? 'N/A') ?></td></tr>
                    <tr><th>Dirección:</th><td><?= htmlspecialchars($step1['direccion'] ?? 'N/A') ?></td></tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Step 2 Summary -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                <i class="bi bi-calendar3 me-2"></i> Primer Periodo
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#summaryAccordion">
            <div class="accordion-body">
                <table class="table table-sm">
                    <tr><th width="30%">Nombre:</th><td><?= htmlspecialchars($step2['nombre_periodo'] ?? 'No configurado') ?></td></tr>
                    <tr><th>Fecha Inicio:</th><td><?= $step2['fecha_inicio'] ?? 'N/A' ?></td></tr>
                    <tr><th>Fecha Fin:</th><td><?= $step2['fecha_fin'] ?? 'N/A' ?></td></tr>
                    <tr><th>Año:</th><td><?= $step2['anio'] ?? 'N/A' ?></td></tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Step 3 Summary -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                <i class="bi bi-mortarboard me-2"></i> Primer Programa
            </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#summaryAccordion">
            <div class="accordion-body">
                <table class="table table-sm">
                    <tr><th width="30%">Nombre:</th><td><?= htmlspecialchars($step3['nombre_programa'] ?? 'No configurado') ?></td></tr>
                    <tr><th>Tipo:</th><td><?= $step3['tipo'] ?? 'N/A' ?></td></tr>
                    <tr><th>Modalidad:</th><td><?= $step3['modalidad'] ?? 'N/A' ?></td></tr>
                    <tr><th>Colegiatura:</th><td>$<?= number_format($step3['monto_colegiatura'] ?? 0, 2) ?></td></tr>
                    <tr><th>Inscripción:</th><td>$<?= number_format($step3['monto_inscripcion'] ?? 0, 2) ?></td></tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Step 4 Summary -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                <i class="bi bi-gear me-2"></i> Configuración de Pagos
            </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#summaryAccordion">
            <div class="accordion-body">
                <table class="table table-sm">
                    <tr><th width="30%">Día Límite:</th><td>Día <?= $step4['dia_limite_pago'] ?? 'N/A' ?> de cada mes</td></tr>
                    <tr><th>Tipo Penalización:</th><td><?= $step4['tipo_penalizacion'] ?? 'N/A' ?></td></tr>
                    <tr><th>Valor:</th><td><?= $step4['tipo_penalizacion'] == 'PORCENTAJE' ? '' : '$' ?><?= $step4['valor_penalizacion'] ?? '0' ?><?= $step4['tipo_penalizacion'] == 'PORCENTAJE' ? '%' : '' ?></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-info">
    <i class="bi bi-info-circle"></i>
    <strong>¿Todo correcto?</strong> Al hacer clic en "Completar Configuración" se guardarán estos datos y podrás comenzar a usar el sistema.
</div>

<form method="POST" action="<?= BASE_URL ?>/wizard/step5">
    <div class="wizard-footer">
        <a href="<?= BASE_URL ?>/wizard/step4" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Anterior
        </a>
        <button type="submit" class="btn btn-success btn-lg">
            <i class="bi bi-check-circle"></i> Completar Configuración
        </button>
    </div>
</form>

<?php require 'footer.php'; ?>
