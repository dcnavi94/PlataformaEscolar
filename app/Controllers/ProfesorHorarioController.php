<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Horario.php';
require_once __DIR__ . '/../Models/Profesor.php';

class ProfesorHorarioController extends Controller {
    private $horarioModel;
    private $profesorModel;

    public function __construct() {
        $this->requireRole('PROFESOR');
        $this->horarioModel = new Horario();
        $this->profesorModel = new Profesor();
    }

    public function index() {
        $id_usuario = $_SESSION['user_id'];
        $profesor = $this->profesorModel->getByUserId($id_usuario);
        
        if (!$profesor) {
            $_SESSION['error'] = "Perfil de profesor no encontrado.";
            header('Location: ' . BASE_URL . '/profesor/dashboard');
            exit;
        }

        $horarios = $this->horarioModel->getByProfesor($profesor['id_profesor']);
        $this->view('layouts/header_profesor', ['title' => 'Mi Horario']);
        $this->view('profesor/horario/index', ['horarios' => $horarios]);
        $this->view('layouts/footer');
    }
}
