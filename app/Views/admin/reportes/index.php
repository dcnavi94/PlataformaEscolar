<?php
// app/Views/admin/reportes/index.php
?>
<div class="mb-4">
    <h2><i class="bi bi-file-earmark-bar-graph"></i> Generador de Reportes</h2>
    <p class="text-muted">Selecciona el tipo de reporte que deseas generar</p>
</div>

<!-- Report Type Cards -->
<div class="row g-3 mb-4" id="reportCards">
    <!-- Card 1: Alumnos con Adeudos -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 hover-shadow report-card" data-report="alumnos_pendientes">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-people-fill institutional-orange" style="font-size: 3rem;"></i>
                </div>
                <h5 class="card-title institutional-navy">Alumnos con Adeudos</h5>
                <p class="card-text text-muted small">Listado de alumnos con cargos pendientes o vencidos</p>
                <button class="btn btn-outline-navy w-100" onclick="selectReport('alumnos_pendientes')">
                    <i class="bi bi-arrow-right-circle"></i> Generar
                </button>
            </div>
        </div>
    </div>

    <!-- Card 2: Ingresos por Periodo -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 hover-shadow report-card" data-report="ingresos_periodo">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-cash-stack institutional-orange" style="font-size: 3rem;"></i>
                </div>
                <h5 class="card-title institutional-navy">Ingresos por Periodo</h5>
                <p class="card-text text-muted small">Desglose de ingresos por fecha y método de pago</p>
                <button class="btn btn-outline-orange w-100" onclick="selectReport('ingresos_periodo')">
                    <i class="bi bi-arrow-right-circle"></i> Generar
                </button>
            </div>
        </div>
    </div>

    <!-- Card 3: Estado de Cuenta por Grupo -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 hover-shadow report-card" data-report="estado_cuenta_grupo">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-receipt institutional-navy" style="font-size: 3rem;"></i>
                </div>
                <h5 class="card-title institutional-navy">Estado de Cuenta</h5>
                <p class="card-text text-muted small">Resumen de pagos por grupo académico</p>
                <button class="btn btn-outline-navy w-100" onclick="selectReport('estado_cuenta_grupo')">
                    <i class="bi bi-arrow-right-circle"></i> Generar
                </button>
            </div>
        </div>
    </div>

    <!-- Card 4: Alumnos Becados -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 hover-shadow report-card" data-report="alumnos_becados">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-award institutional-orange" style="font-size: 3rem;"></i>
                </div>
                <h5 class="card-title institutional-navy">Alumnos Becados</h5>
                <p class="card-text text-muted small">Listado de estudiantes con becas activas</p>
                <button class="btn btn-outline-orange w-100" onclick="selectReport('alumnos_becados')">
                    <i class="bi bi-arrow-right-circle"></i> Generar
                </button>
            </div>
        </div>
    </div>

    <!-- Card 5: Pagos por Método -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 hover-shadow report-card" data-report="pagos_por_metodo">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-credit-card institutional-navy" style="font-size: 3rem;"></i>
                </div>
                <h5 class="card-title institutional-navy">Pagos por Método</h5>
                <p class="card-text text-muted small">Estadísticas de métodos de pago utilizados</p>
                <button class="btn btn-outline-navy w-100" onclick="selectReport('pagos_por_metodo')">
                    <i class="bi bi-arrow-right-circle"></i> Generar
                </button>
            </div>
        </div>
    </div>

    <!-- Card 6: Reporte de Morosidad -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 hover-shadow report-card" data-report="morosidad">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle institutional-orange" style="font-size: 3rem;"></i>
                </div>
                <h5 class="card-title institutional-navy">Reporte de Morosidad</h5>
                <p class="card-text text-muted small">Alumnos con pagos vencidos y adeudos</p>
                <button class="btn btn-outline-orange w-100" onclick="selectReport('morosidad')">
                    <i class="bi bi-arrow-right-circle"></i> Generar
                </button>
            </div>
        </div>
    </div>

    <!-- Card 7: Cargos Generados -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 hover-shadow report-card" data-report="cargos_generados">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-clipboard-data institutional-navy" style="font-size: 3rem;"></i>
                </div>
                <h5 class="card-title institutional-navy">Cargos Generados</h5>
                <p class="card-text text-muted small">Resumen de cargos creados por periodo</p>
                <button class="btn btn-outline-navy w-100" onclick="selectReport('cargos_generados')">
                    <i class="bi bi-arrow-right-circle"></i> Generar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Filters Modal/Panel (Hidden by default) -->
<div id="filterPanel" class="card" style="display: none;">
    <div class="card-header institutional-navy-bg text-white">
        <h5 class="mb-0">
            <i class="bi bi-sliders"></i> Parámetros del Reporte: <span id="reportTitle"></span>
        </h5>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>/reporte/generar" method="GET" id="reportForm">
            <input type="hidden" name="tipo_reporte" id="tipoReporteInput">
            
            <!-- Dynamic Filters Container -->
            <div class="row" id="filtrosContainer">
                <!-- Date Range Filter -->
                <div class="col-md-6 mb-3 filter-section" id="filtroFechas" style="display: none;">
                    <label class="form-label"><i class="bi bi-calendar-range institutional-orange"></i> Fecha Inicio</label>
                    <input type="date" class="form-control" name="fecha_inicio" value="<?= date('Y-m-01') ?>">
                </div>
                <div class="col-md-6 mb-3 filter-section" id="filtroFechasFin" style="display: none;">
                    <label class="form-label"><i class="bi bi-calendar-range institutional-orange"></i> Fecha Fin</label>
                    <input type="date" class="form-control" name="fecha_fin" value="<?= date('Y-m-t') ?>">
                </div>

                <!-- Program Filter -->
                <div class="col-md-6 mb-3 filter-section" id="filtroPrograma" style="display: none;">
                    <label class="form-label"><i class="bi bi-book institutional-orange"></i> Programa Académico</label>
                    <select class="form-select" name="id_programa">
                        <option value="">Todos los programas</option>
                    </select>
                </div>

                <!-- Group Filter -->
                <div class="col-md-6 mb-3 filter-section" id="filtroGrupo" style="display: none;">
                    <label class="form-label"><i class="bi bi-people institutional-orange"></i> Grupo</label>
                    <select class="form-select" name="id_grupo">
                        <option value="">Todos los grupos</option>
                    </select>
                </div>

                <!-- Period Filter -->
                <div class="col-md-6 mb-3 filter-section" id="filtroPeriodo" style="display: none;">
                    <label class="form-label"><i class="bi bi-calendar3 institutional-orange"></i> Periodo</label>
                    <select class="form-select" name="id_periodo">
                        <option value="">Todos los periodos</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="col-md-6 mb-3 filter-section" id="filtroEstatus" style="display: none;">
                    <label class="form-label"><i class="bi bi-check-circle institutional-orange"></i> Estatus de Pago</label>
                    <select class="form-select" name="estatus">
                        <option value="">Todos</option>
                        <option value="PENDIENTE">Pendiente</option>
                        <option value="VENCIDO">Vencido</option>
                        <option value="PARCIAL">Parcial</option>
                        <option value="PAGADO">Pagado</option>
                    </select>
                </div>

                <!-- Payment Method Filter -->
                <div class="col-md-6 mb-3 filter-section" id="filtroMetodo" style="display: none;">
                    <label class="form-label"><i class="bi bi-credit-card institutional-orange"></i> Método de Pago</label>
                    <select class="form-select" name="metodo_pago">
                        <option value="">Todos</option>
                        <option value="EFECTIVO">Efectivo</option>
                        <option value="TRANSFERENCIA">Transferencia</option>
                        <option value="PAYPAL">PayPal</option>
                    </select>
                </div>

                <!-- Scholarship Filter -->
                <div class="col-md-6 mb-3 filter-section" id="filtroBeca" style="display: none;">
                    <label class="form-label"><i class="bi bi-award institutional-orange"></i> Porcentaje de Beca Mínimo (%)</label>
                    <input type="number" class="form-control" name="beca_minima" min="0" max="100" value="0">
                </div>
            </div>

            <!-- Export Format -->
            <hr>
            <h6 class="mb-3 institutional-navy"><i class="bi bi-download"></i> Formato de Exportación</h6>
            <div class="row mb-4">
                <div class="col-md-4">
                    <input type="radio" class="btn-check" name="formato" id="formatoVista" value="vista" checked>
                    <label class="btn btn-outline-navy w-100" for="formatoVista">
                        <i class="bi bi-eye"></i><br>Vista Previa
                    </label>
                </div>
                <div class="col-md-4">
                    <input type="radio" class="btn-check" name="formato" id="formatoPDF" value="pdf">
                    <label class="btn btn-outline-orange w-100" for="formatoPDF">
                        <i class="bi bi-file-pdf"></i><br>PDF
                    </label>
                </div>
                <div class="col-md-4">
                    <input type="radio" class="btn-check" name="formato" id="formatoExcel" value="excel">
                    <label class="btn btn-outline-navy w-100" for="formatoExcel">
                        <i class="bi bi-file-excel"></i><br>Excel
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" onclick="cancelReport()">
                    <i class="bi bi-x-circle"></i> Cancelar
                </button>
                <button type="submit" class="btn institutional-orange-btn btn-lg">
                    <i class="bi bi-play-circle-fill"></i> Generar Reporte
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Institutional Colors */
:root {
    --navy-blue: #003366;
    --orange: #FF6600;
    --light-orange: #FF8C42;
}

.institutional-navy {
    color: var(--navy-blue) !important;
}

.institutional-orange {
    color: var(--orange) !important;
}

.institutional-navy-bg {
    background-color: var(--navy-blue) !important;
}

.institutional-orange-bg {
    background-color: var(--orange) !important;
}

.btn-outline-navy {
    color: var(--navy-blue);
    border-color: var(--navy-blue);
}

.btn-outline-navy:hover {
    background-color: var(--navy-blue);
    border-color: var(--navy-blue);
    color: white;
}

.btn-outline-orange {
    color: var(--orange);
    border-color: var(--orange);
}

.btn-outline-orange:hover {
    background-color: var(--orange);
    border-color: var(--orange);
    color: white;
}

.institutional-orange-btn {
    background-color: var(--orange);
    border-color: var(--orange);
    color: white;
}

.institutional-orange-btn:hover {
    background-color: var(--light-orange);
    border-color: var(--light-orange);
    color: white;
}

.hover-shadow {
    transition: all 0.3s ease;
    cursor: pointer;
    border: 2px solid transparent;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(255, 102, 0, 0.3);
    border-color: var(--orange);
}

.report-card.selected {
    border: 2px solid var(--navy-blue);
    background-color: #f8f9fa;
}
</style>

<script>
const reportNames = {
    'alumnos_pendientes': 'Alumnos con Adeudos',
    'ingresos_periodo': 'Ingresos por Periodo',
    'estado_cuenta_grupo': 'Estado de Cuenta por Grupo',
    'alumnos_becados': 'Alumnos Becados',
    'pagos_por_metodo': 'Pagos por Método',
    'morosidad': 'Reporte de Morosidad',
    'cargos_generados': 'Cargos Generados'
};

function selectReport(tipo) {
    document.getElementById('reportCards').style.display = 'none';
    document.getElementById('filterPanel').style.display = 'block';
    document.getElementById('tipoReporteInput').value = tipo;
    document.getElementById('reportTitle').textContent = reportNames[tipo];
    
    document.querySelectorAll('.filter-section').forEach(el => el.style.display = 'none');
    
    switch(tipo) {
        case 'alumnos_pendientes':
            document.getElementById('filtroPrograma').style.display = 'block';
            document.getElementById('filtroGrupo').style.display = 'block';
            document.getElementById('filtroEstatus').style.display = 'block';
            break;
        case 'ingresos_periodo':
            document.getElementById('filtroFechas').style.display = 'block';
            document.getElementById('filtroFechasFin').style.display = 'block';
            document.getElementById('filtroMetodo').style.display = 'block';
            break;
        case 'estado_cuenta_grupo':
            document.getElementById('filtroGrupo').style.display = 'block';
            document.getElementById('filtroPeriodo').style.display = 'block';
            break;
        case 'alumnos_becados':
            document.getElementById('filtroPrograma').style.display = 'block';
            document.getElementById('filtroBeca').style.display = 'block';
            break;
        case 'pagos_por_metodo':
            document.getElementById('filtroFechas').style.display = 'block';
            document.getElementById('filtroFechasFin').style.display = 'block';
            document.getElementById('filtroMetodo').style.display = 'block';
            break;
        case 'morosidad':
            document.getElementById('filtroPrograma').style.display = 'block';
            document.getElementById('filtroGrupo').style.display = 'block';
            break;
        case 'cargos_generados':
            document.getElementById('filtroPeriodo').style.display = 'block';
            document.getElementById('filtroPrograma').style.display = 'block';
            break;
    }
    
    document.getElementById('filterPanel').scrollIntoView({ behavior: 'smooth' });
}

function cancelReport() {
    document.getElementById('filterPanel').style.display = 'none';
    document.getElementById('reportCards').style.display = 'flex';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
