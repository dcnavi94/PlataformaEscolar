<?php
// app/Controllers/WizardController.php

require_once '../app/Core/Controller.php';

class WizardController extends Controller {

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('ADMIN');
    }

    /**
     * Check if wizard should be displayed
     */
    public function index() {
        // Check if wizard was already completed
        $config = file_get_contents(__DIR__ . '/../../wizard_completed.lock');
        if (file_exists(__DIR__ . '/../../wizard_completed.lock')) {
            $this->redirect('/admin/dashboard');
            return;
        }

        // Redirect to step 1
        $this->redirect('/wizard/step1');
    }

    /**
     * Step 1: Institution Data
     */
    public function step1() {
        if ($this->isWizardCompleted()) {
            $this->redirect('/admin/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Save institution data to session
            $_SESSION['wizard_step1'] = [
                'nombre_institucion' => $_POST['nombre_institucion'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
                'direccion' => $_POST['direccion'] ?? ''
            ];
            $this->redirect('/wizard/step2');
            return;
        }

        $data = [
            'title' => 'Configuración Inicial - Paso 1/5',
            'saved_data' => $_SESSION['wizard_step1'] ?? []
        ];

        $this->view('wizard/step1', $data);
    }

    /**
     * Step 2: First Period
     */
    public function step2() {
        if ($this->isWizardCompleted()) {
            $this->redirect('/admin/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['wizard_step2'] = [
                'nombre_periodo' => $_POST['nombre_periodo'] ?? '',
                'fecha_inicio' => $_POST['fecha_inicio'] ?? '',
                'fecha_fin' => $_POST['fecha_fin'] ?? '',
                'anio' => $_POST['anio'] ?? date('Y'),
                'numero_periodo' => $_POST['numero_periodo'] ?? 1
            ];
            $this->redirect('/wizard/step3');
            return;
        }

        $data = [
            'title' => 'Configuración Inicial - Paso 2/5',
            'saved_data' => $_SESSION['wizard_step2'] ?? []
        ];

        $this->view('wizard/step2', $data);
    }

    /**
     * Step 3: Create First Program
     */
    public function step3() {
        if ($this->isWizardCompleted()) {
            $this->redirect('/admin/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['wizard_step3'] = [
                'nombre_programa' => $_POST['nombre_programa'] ?? '',
                'tipo' => $_POST['tipo'] ?? 'LICENCIATURA',
                'modalidad' => $_POST['modalidad'] ?? 'Virtual',
                'monto_colegiatura' => $_POST['monto_colegiatura'] ?? 0,
                'monto_inscripcion' => $_POST['monto_inscripcion'] ?? 0
            ];
            $this->redirect('/wizard/step4');
            return;
        }

        $data = [
            'title' => 'Configuración Inicial - Paso 3/5',
            'saved_data' => $_SESSION['wizard_step3'] ?? []
        ];

        $this->view('wizard/step3', $data);
    }

    /**
     * Step 4: Payment Concepts Configuration
     */
    public function step4() {
        if ($this->isWizardCompleted()) {
            $this->redirect('/admin/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['wizard_step4'] = [
                'dia_limite_pago' => $_POST['dia_limite_pago'] ?? 10,
                'tipo_penalizacion' => $_POST['tipo_penalizacion'] ?? 'MONTO',
                'valor_penalizacion' => $_POST['valor_penalizacion'] ?? 0
            ];
            $this->redirect('/wizard/step5');
            return;
        }

        $data = [
            'title' => 'Configuración Inicial - Paso 4/5',
            'saved_data' => $_SESSION['wizard_step4'] ?? []
        ];

        $this->view('wizard/step4', $data);
    }

    /**
     * Step 5: Summary and Complete
     */
    public function step5() {
        if ($this->isWizardCompleted()) {
            $this->redirect('/admin/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Save all wizard data to database
            $this->saveWizardData();
            
            // Mark wizard as completed
            file_put_contents(__DIR__ . '/../../wizard_completed.lock', date('Y-m-d H:i:s'));
            
            // Clear wizard session data
            unset($_SESSION['wizard_step1']);
            unset($_SESSION['wizard_step2']);
            unset($_SESSION['wizard_step3']);
            unset($_SESSION['wizard_step4']);
            
            $_SESSION['success'] = '¡Configuración inicial completada exitosamente!';
            $this->redirect('/admin/dashboard');
            return;
        }

        $data = [
            'title' => 'Configuración Inicial - Paso 5/5',
            'step1' => $_SESSION['wizard_step1'] ?? [],
            'step2' => $_SESSION['wizard_step2'] ?? [],
            'step3' => $_SESSION['wizard_step3'] ?? [],
            'step4' => $_SESSION['wizard_step4'] ?? []
        ];

        $this->view('wizard/step5', $data);
    }

    /**
     * Save all wizard data to database
     */
    private function saveWizardData() {
        try {
            // Step 2: Create Period
            if (isset($_SESSION['wizard_step2'])) {
                $periodoModel = $this->model('Periodo');
                $periodoId = $periodoModel->insert($_SESSION['wizard_step2']);
            }

            // Step 3: Create Program
            if (isset($_SESSION['wizard_step3']) && isset($periodoId)) {
                $programaModel = $this->model('Programa');
                $programaId = $programaModel->insert($_SESSION['wizard_step3']);

                // Create first group for this program
                $grupoModel = $this->model('Grupo');
                $grupoModel->insert([
                    'nombre' => 'Grupo A',
                    'id_programa' => $programaId,
                    'id_periodo' => $periodoId,
                    'estado' => 'ACTIVO'
                ]);
            }

            // Step 4: Update financial configuration
            if (isset($_SESSION['wizard_step4'])) {
                $configModel = $this->model('ConfiguracionFinanciera');
                $config = $configModel->find(1);
                
                if ($config) {
                    $configModel->update(1, $_SESSION['wizard_step4']);
                } else {
                    $configModel->insert($_SESSION['wizard_step4']);
                }
            }

            return true;
        } catch (Exception $e) {
            error_log("Wizard save error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if wizard was already completed
     */
    private function isWizardCompleted() {
        return file_exists(__DIR__ . '/../../wizard_completed.lock');
    }
}
