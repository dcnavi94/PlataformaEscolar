<?php

class ProfesorNominaController extends Controller {
    private $nominaModel;
    private $registroHoraModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'PROFESOR') {
            $this->redirect('/auth/login');
        }
        $this->nominaModel = $this->model('Nomina');
        $this->registroHoraModel = $this->model('RegistroHora');
    }

    public function index() {
        // Get professor ID from user ID
        $profesorModel = $this->model('Profesor');
        $profesor = $profesorModel->getByUserId($_SESSION['user_id']);
        
        if (!$profesor) {
            // Handle error
            $this->redirect('/profesor/dashboard');
        }

        $nominas = $this->nominaModel->getAll(['id_profesor' => $profesor['id_profesor']]);
        $horas = $this->registroHoraModel->getByProfesor($profesor['id_profesor']);

        $this->view('layouts/header_profesor', ['title' => 'Mis Pagos y Nómina']);
        $this->view('profesor/nomina/index', [
            'nominas' => $nominas,
            'horas' => $horas,
            'profesor' => $profesor
        ]);
        $this->view('layouts/footer');
    }
}
