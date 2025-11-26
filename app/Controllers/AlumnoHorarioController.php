<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Horario.php';
require_once __DIR__ . '/../Models/Alumno.php';

class AlumnoHorarioController extends Controller {
    private $horarioModel;
    private $alumnoModel;

    public function __construct() {
        $this->requireRole('ALUMNO');
        $this->horarioModel = new Horario();
        $this->alumnoModel = new Alumno();
    }

    public function index() {
        $id_usuario = $_SESSION['user_id'];
        $alumno = $this->alumnoModel->getByUserId($id_usuario);
        
        if (!$alumno) {
            $_SESSION['error'] = "Perfil de alumno no encontrado.";
            header('Location: ' . BASE_URL . '/alumno/dashboard');
            exit;
        }

        $horarios = $this->horarioModel->getByGrupo($alumno['id_grupo']);
        $this->view('layouts/header_alumno', ['title' => 'Mi Horario']);
        $this->view('alumno/horario/index', ['horarios' => $horarios]);
        $this->view('layouts/footer');
    }
}
