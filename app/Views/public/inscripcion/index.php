<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripciones en Línea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --navy-blue: #003366;
            --orange: #FF6600;
        }
        .hero {
            background: linear-gradient(135deg, var(--navy-blue) 0%, #002244 100%);
            color: white;
            padding: 4rem 0;
        }
        .card-periodo {
            transition: transform 0.2s;
            border-left: 4px solid var(--orange);
        }
        .card-periodo:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1class="display-4 fw-bold">¡Inscríbete hoy!</h1>
                    <p class="lead">Proceso de inscripción 100% en línea. Rápido, fácil y seguro.</p>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="<?= BASE_URL ?>/inscripcion/seguimiento" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-search"></i> Seguimiento de Solicitud
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Periodos Activos -->
    <div class="container my-5">
        <h2 class="mb-4">Periodos de Inscripción Disponibles</h2>

        <?php if (empty($periodos)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No hay periodos de inscripción activos en este momento.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($periodos as $periodo): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card card-periodo h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($periodo['programa_nombre']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($periodo['nombre']) ?></p>
                                <hr>
                                <p class="mb-1">
                                    <i class="bi bi-calendar"></i> 
                                    <strong>Periodo:</strong><br>
                                    <?= date('d/m/Y', strtotime($periodo['fecha_inicio'])) ?> - 
                                    <?= date('d/m/Y', strtotime($periodo['fecha_fin'])) ?>
                                </p>
                                <?php if ($periodo['cupo_maximo'] > 0): ?>
                                    <p class="mb-1">
                                        <i class="bi bi-people"></i> 
                                        <strong>Cupo:</strong> <?= $periodo['cupo_maximo'] ?> lugares
                                    </p>
                                <?php endif; ?>
                                <?php if ($periodo['monto_inscripcion'] > 0): ?>
                                    <p class="mb-3">
                                        <i class="bi bi-cash"></i> 
                                        <strong>Costo:</strong> $<?= number_format($periodo['monto_inscripcion'], 2) ?>
                                    </p>
                                <?php endif; ?>
                                <a href="<?= BASE_URL ?>/inscripcion/formulario/<?= $periodo['id_periodo_inscripcion'] ?>" 
                                   class="btn btn-primary w-100">
                                    <i class="bi bi-pencil-square"></i> Inscribirme Ahora
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Info Section -->
    <div class="bg-light py-5">
        <div class="container">
            <h3 class="mb-4">¿Cómo funciona el proceso?</h3>
            <div class="row">
                <div class="col-md-3 text-center mb-3">
                    <div class="display-6 text-primary mb-3"><i class="bi bi-1-circle-fill"></i></div>
                    <h5>Llena el formulario</h5>
                    <p>Proporciona tus datos personales y de contacto</p>
                </div>
                <div class="col-md-3 text-center mb-3">
                    <div class="display-6 text-primary mb-3"><i class="bi bi-2-circle-fill"></i></div>
                    <h5>Recibe tu folio</h5>
                    <p>Obtén un folio único para dar seguimiento</p>
                </div>
                <div class="col-md-3 text-center mb-3">
                    <div class="display-6 text-primary mb-3"><i class="bi bi-3-circle-fill"></i></div>
                    <h5>Sube documentos</h5>
                    <p>Carga tus documentos probatorios</p>
                </div>
                <div class="col-md-3 text-center mb-3">
                    <div class="display-6 text-primary mb-3"><i class="bi bi-4-circle-fill"></i></div>
                    <h5>Espera aprobación</h5>
                    <p>Recibirás un correo con tus credenciales</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
