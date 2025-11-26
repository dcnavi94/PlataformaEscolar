<?php

class AlumnoPrestamosController extends Controller {
    private $prestamoModel;

    public function __construct() {
        // Ensure user is logged in as student
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'ALUMNO') {
            $this->redirect('/auth/login');
        }
        $this->prestamoModel = $this->model('Prestamo');
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $prestamos = $this->prestamoModel->getByUserId($userId);
        
        $this->view('layouts/header_alumno', ['title' => 'Mis Materiales']);
        $this->view('alumno/prestamos/index', [
            'prestamos' => $prestamos
        ]);
        $this->view('layouts/footer');
    }
}
