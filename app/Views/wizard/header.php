<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Configuración Inicial' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --navy-blue: #003366;
            --orange: #FF6600;
        }
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .wizard-container { max-width: 800px; margin: 50px auto; }
        .wizard-card { background: white; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        .wizard-header { background: var(--navy-blue); color: white; padding: 30px; border-radius: 15px 15px 0 0; }
        .wizard-steps { display: flex; justify-content: space-between; margin: 30px 0; padding: 0 20px; }
        .step { flex: 1; text-align: center; position: relative; }
        .step-number { width: 40px; height: 40px; border-radius: 50%; background: #e0e0e0; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; margin-bottom: 10px; }
        .step.active .step-number { background: var(--orange); color: white; }
        .step.completed .step-number { background: #28a745; color: white; }
        .step-line { position: absolute; top: 20px; left: 50%; width: 100%; height: 2px; background: #e0e0e0; z-index: -1; }
        .step:first-child .step-line { display: none; }
        .wizard-content { padding: 40px; }
        .wizard-footer { padding: 20px 40px; border-top: 1px solid #dee2e6; display: flex; justify-content: space-between; }
    </style>
</head>
<body>
    <div class="wizard-container">
        <div class="wizard-card">
            <div class="wizard-header text-center">
                <h2><i class="bi bi-magic"></i> Configuración Inicial del Sistema</h2>
                <p class="mb-0">Vamos a configurar los datos básicos de tu institución</p>
            </div>
            
            <div class="wizard-steps">
                <div class="step <?= $current_step >= 1 ? 'active' : '' ?> <?= $current_step > 1 ? 'completed' : '' ?>">
                    <div class="step-line"></div>
                    <div class="step-number">1</div>
                    <small>Institución</small>
                </div>
                <div class="step <?= $current_step >= 2 ? 'active' : '' ?> <?= $current_step > 2 ? 'completed' : '' ?>">
                    <div class="step-line"></div>
                    <div class="step-number">2</div>
                    <small>Periodo</small>
                </div>
                <div class="step <?= $current_step >= 3 ? 'active' : '' ?> <?= $current_step > 3 ? 'completed' : '' ?>">
                    <div class="step-line"></div>
                    <div class="step-number">3</div>
                    <small>Programa</small>
                </div>
                <div class="step <?= $current_step >= 4 ? 'active' : '' ?> <?= $current_step > 4 ? 'completed' : '' ?>">
                    <div class="step-line"></div>
                    <div class="step-number">4</div>
                    <small>Configuración</small>
                </div>
                <div class="step <?= $current_step >= 5 ? 'active' : '' ?>">
                    <div class="step-line"></div>
                    <div class="step-number">5</div>
                    <small>Finalizar</small>
                </div>
            </div>
            
            <div class="wizard-content">
