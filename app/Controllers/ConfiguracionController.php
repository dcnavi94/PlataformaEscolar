<?php
// app/Controllers/ConfiguracionController.php

require_once '../app/Core/Controller.php';

class ConfiguracionController extends Controller {

    private $configModel;
    private $bitacora;

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('ADMIN');
        
        $this->configModel = $this->model('ConfiguracionFinanciera'); // Assuming model name
        $this->bitacora = $this->model('Bitacora');
    }

    public function index() {

        // Assuming single row config
        $config = $this->configModel->get();
        
        $this->view('layouts/header', ['title' => 'Configuración Financiera']);
        $this->view('admin/configuracion/index', ['config' => $config]);
        $this->view('layouts/footer');
    }

    public function update() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/configuracion/index');
        }

        $data = [
            'monto_inscripcion' => floatval($_POST['monto_inscripcion'] ?? 0),
            'monto_colegiatura' => floatval($_POST['monto_colegiatura'] ?? 0),
            'dia_limite_pago' => (int)($_POST['dia_limite_pago'] ?? 10),
            'porcentaje_recargo' => floatval($_POST['porcentaje_recargo'] ?? 0),
            'monto_recargo_diario' => floatval($_POST['monto_recargo_diario'] ?? 0)
        ];

        try {
            $this->configModel->updateConfig($data);
            $this->bitacora->log($_SESSION['user_id'], 'configuracion_financiera', 1, 'UPDATE', "Configuración financiera actualizada");
            $_SESSION['success'] = 'Configuración actualizada exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar configuración';
        }
        
        $this->redirect('/configuracion/index');
    }
}
