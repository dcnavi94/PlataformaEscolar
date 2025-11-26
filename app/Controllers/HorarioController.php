<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Horario.php';
require_once __DIR__ . '/../Models/Grupo.php';
require_once __DIR__ . '/../Models/Asignacion.php';

class HorarioController extends Controller {
    private $horarioModel;
    private $grupoModel;
    private $asignacionModel;

    public function __construct() {
        $this->requireRole('ADMIN');
        $this->horarioModel = new Horario();
        $this->grupoModel = new Grupo();
        $this->asignacionModel = new Asignacion();
    }

    public function index() {
        $grupos = $this->grupoModel->getAll();
        $this->view('layouts/header', ['title' => 'Gestión de Horarios']);
        $this->view('admin/horarios/index', ['grupos' => $grupos]);
        $this->view('layouts/footer');
    }

    public function edit($id_grupo) {
        $grupo = $this->grupoModel->findById($id_grupo);
        if (!$grupo) {
            $_SESSION['error'] = "Grupo no encontrado.";
            header('Location: ' . BASE_URL . '/horario');
            exit;
        }

        $asignaciones = $this->asignacionModel->getByGrupo($id_grupo);
        $horarios = $this->horarioModel->getByGrupo($id_grupo);

        $this->view('layouts/header', ['title' => 'Editar Horario']);
        $this->view('admin/horarios/edit', [
            'grupo' => $grupo,
            'asignaciones' => $asignaciones,
            'horarios' => $horarios
        ]);
        $this->view('layouts/footer');
    }

    public function save($id_grupo) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_asignacion = $_POST['id_asignacion'];
            $dia = $_POST['dia'];
            $inicio = $_POST['inicio'];
            $fin = $_POST['fin'];
            $aula = $_POST['aula'];

            try {
                $this->horarioModel->create([
                    'id_asignacion' => $id_asignacion,
                    'dia_semana' => $dia,
                    'hora_inicio' => $inicio,
                    'hora_fin' => $fin,
                    'aula' => $aula
                ]);
                $_SESSION['success'] = "Horario agregado correctamente.";
            } catch (Exception $e) {
                $_SESSION['error'] = "Error al agregar horario: " . $e->getMessage();
            }
            
            header('Location: ' . BASE_URL . '/horario/edit/' . $id_grupo);
            exit;
        }
    }

    public function delete($id_horario) {
        // Logic to delete specific schedule entry (needs method in model)
        // For now, redirect back
        header('Location: ' . BASE_URL . '/horario');
    }
}
