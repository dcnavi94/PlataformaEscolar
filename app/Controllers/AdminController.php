<?php
// app/Controllers/AdminController.php

require_once '../app/Core/Controller.php';

class AdminController extends Controller {

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('ADMIN');
    }

    /**
     * Index method
     */
    public function index() {
        $this->redirect('/admin/dashboard');
    }

    /**
     * Admin dashboard
     */
    public function dashboard() {
        // Get statistics
        $alumnoModel = $this->model('Alumno');
        $cargoModel = $this->model('Cargo');
        $pagoModel = $this->model('Pago');

        $stats = [
            'total_alumnos' => $alumnoModel->count(),
            'alumnos_inscritos' => $alumnoModel->countByStatus('INSCRITO'),
            'cargos_pendientes' => $cargoModel->countByStatus('PENDIENTE'),
            'ingresos_mes' => $pagoModel->getTotalIngresosMes(date('Y-m'))
        ];

        $this->view('layouts/header', ['title' => 'Dashboard']);
        $this->view('admin/dashboard', $stats);
        $this->view('layouts/footer');
    }
}
