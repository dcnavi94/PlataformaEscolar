<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800"><i class="bi bi-calendar-week me-2"></i>Calendario Escolar</h1>
    </div>

    <div class="row">
        <!-- Formulario de Eventos -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 fw-bold"><i class="bi bi-plus-circle me-2"></i>Nuevo Evento</h6>
                </div>
                <div class="card-body bg-light">
                    <form id="eventForm">
                        <div class="mb-3">
                            <label for="titulo" class="form-label fw-bold text-secondary">Título del Evento</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-type"></i></span>
                                <input type="text" class="form-control" id="titulo" placeholder="Ej. Examen Final" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tipo" class="form-label fw-bold text-secondary">Tipo de Evento</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-tag"></i></span>
                                <select class="form-select" id="tipo">
                                    <option value="EVENTO">📅 Evento General</option>
                                    <option value="FERIADO">🏖️ Feriado / Suspensión</option>
                                    <option value="EXAMEN">📝 Examen / Entrega</option>
                                    <option value="ADMINISTRATIVO">🏢 Administrativo</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="inicio" class="form-label fw-bold text-secondary">Inicio</label>
                            <input type="datetime-local" class="form-control" id="inicio" required>
                        </div>

                        <div class="mb-3">
                            <label for="fin" class="form-label fw-bold text-secondary">Fin</label>
                            <input type="datetime-local" class="form-control" id="fin" required>
                        </div>

                        <div class="mb-3">
                            <label for="color" class="form-label fw-bold text-secondary">Color de Etiqueta</label>
                            <div class="d-flex align-items-center">
                                <input type="color" class="form-control form-control-color me-2" id="color" value="#3788d8" title="Elige un color">
                                <small class="text-muted">Selecciona un color distintivo</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label fw-bold text-secondary">Descripción</label>
                            <textarea class="form-control" id="descripcion" rows="3" placeholder="Detalles adicionales..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                            <i class="bi bi-save me-2"></i>Guardar Evento
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Calendario -->
        <div class="col-lg-9">
            <div class="card shadow border-0 rounded-3">
                <div class="card-body p-4">
                    <div id="calendar" style="min-height: 700px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar CSS & JS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js'></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        themeSystem: 'bootstrap5',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día',
            list: 'Lista'
        },
        events: '<?= BASE_URL ?>/calendario/getEvents',
        editable: true,
        selectable: true,
        dayMaxEvents: true, // allow "more" link when too many events
        eventClick: function(info) {
            Swal.fire({
                title: info.event.title,
                text: info.event.extendedProps.description || 'Sin descripción',
                icon: 'info',
                confirmButtonText: 'Cerrar'
            });
        }
    });
    calendar.render();

    // Handle Form Submit
    document.getElementById('eventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const data = new FormData();
        data.append('titulo', document.getElementById('titulo').value);
        data.append('tipo', document.getElementById('tipo').value);
        data.append('fecha_inicio', document.getElementById('inicio').value);
        data.append('fecha_fin', document.getElementById('fin').value);
        data.append('color', document.getElementById('color').value);
        data.append('descripcion', document.getElementById('descripcion').value);

        fetch('<?= BASE_URL ?>/calendario/save', {
            method: 'POST',
            body: data
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                calendar.refetchEvents();
                document.getElementById('eventForm').reset();
                Swal.fire({
                    icon: 'success',
                    title: '¡Guardado!',
                    text: 'El evento ha sido agregado correctamente.',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo guardar el evento.',
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'Ocurrió un problema al comunicarse con el servidor.',
            });
        });
    });
});
</script>
