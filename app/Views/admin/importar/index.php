<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-file-earmark-arrow-up"></i> Importación Masiva de Alumnos</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5><i class="bi bi-info-circle"></i> Instrucciones</h5>
                    <ol>
                        <li>Descargue la plantilla CSV.</li>
                        <li>Llene los datos respetando los IDs de Programas y Grupos.</li>
                        <li>Guarde el archivo en formato CSV (delimitado por comas).</li>
                        <li>Suba el archivo para procesar.</li>
                    </ol>
                    <a href="<?= BASE_URL ?>/import/descargarPlantilla" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-download"></i> Descargar Plantilla CSV
                    </a>
                </div>

                <form action="<?= BASE_URL ?>/import/procesar" method="POST" enctype="multipart/form-data" class="mt-4">
                    <div class="mb-4">
                        <label for="archivo_csv" class="form-label">Seleccionar Archivo CSV</label>
                        <input class="form-control form-control-lg" type="file" id="archivo_csv" name="archivo_csv" accept=".csv" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-cloud-upload"></i> Importar Alumnos
                        </button>
                        <a href="<?= BASE_URL ?>/alumnoadmin/index" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">Referencias de IDs (Para llenar la plantilla)</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Programas Activos</h6>
                        <ul class="list-group list-group-flush small">
                            <!-- This could be dynamic, but for now just a note -->
                            <li class="list-group-item text-muted">Consulte la sección de Programas para ver los IDs.</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Grupos Activos</h6>
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item text-muted">Consulte la sección de Grupos para ver los IDs.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
