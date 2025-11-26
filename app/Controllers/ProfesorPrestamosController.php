<?php

class ProfesorPrestamosController extends Controller {
    private $prestamoModel;

    public function __construct() {
        // Ensure user is logged in as professor
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'PROFESOR') {
            $this->redirect('/auth/login');
        }
        $this->prestamoModel = $this->model('Prestamo');
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $prestamos = $this->prestamoModel->getByUserId($userId);
        
        $this->view('layouts/header_profesor', ['title' => 'Mis Materiales']);
        $this->view('profesor/prestamos/index', [
            'prestamos' => $prestamos
        ]);
        $this->view('layouts/footer');
    }
}
