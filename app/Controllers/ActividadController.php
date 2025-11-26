<?php
// app/Controllers/ActividadController.php

require_once '../app/Core/Controller.php';
require_once '../app/Models/Actividad.php';
require_once '../app/Models/EntregaTarea.php';
require_once '../app/Models/Profesor.php';
require_once '../app/Helpers/FileUploadHelper.php';

class ActividadController extends Controller {
    private $actividadModel;
    private $entregaModel;
    private $profesorModel;

    public function __construct() {
        $this->actividadModel = new Actividad();
        $this->entregaModel = new EntregaTarea();
        $this->profesorModel = new Profesor();
    }

    /**
     * List all activities for a professor
     */
    public function index() {
        // Allow both ADMIN and PROFESOR roles
        if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['ADMIN', 'PROFESOR'])) {
            $_SESSION['error'] = 'Acceso no autorizado';
            $this->redirect('/auth/login');
            return;
        }

        $actividades = [];
        $asignaciones = [];

        if ($_SESSION['rol'] == 'PROFESOR') {
            // For professors, show only their activities
            $profesor = $this->profesorModel->getByUserId($_SESSION['user_id']);
            if (!$profesor) {
                $_SESSION['error'] = 'Perfil de profesor no encontrado';
                $this->redirect('/auth/login');
                return;
            }

            // Get professor's assignments
            $asignaciones = $this->profesorModel->getAssignedGroups($profesor['id_profesor']);
            
            // Get all activities for this professor
            foreach ($asignaciones as $asig) {
                $acts = $this->actividadModel->getByAsignacion($asig['id_asignacion']);
                $actividades = array_merge($actividades, $acts);
            }
        } else {
            // For admins, show all activities
            $actividades = $this->actividadModel->getAllWithRelations();
        }

        $this->view('profesor/actividades/index', [
            'title' => 'Gestión de Actividades',
            'actividades' => $actividades,
            'asignaciones' => $asignaciones
        ]);
    }

    /**
     * Show create form
     */
    public function create() {
        $this->requireRole('PROFESOR');

        $profesor = $this->profesorModel->getByUserId($_SESSION['user_id']);
        $asignaciones = $this->profesorModel->getAssignedGroups($profesor['id_profesor']);

        $this->view('profesor/actividades/create', [
            'title' => 'Nueva Actividad',
            'asignaciones' => $asignaciones
        ]);
    }

    /**
     * Store new activity
     */
    public function store() {
        $this->requireRole('PROFESOR');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/actividad');
            return;
        }

        // Validate
        $errors = [];
        if (empty($_POST['titulo'])) {
            $errors[] = 'El título es requerido';
        }
        if (empty($_POST['id_asignacion'])) {
            $errors[] = 'Debe seleccionar una asignación';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            $this->redirect('/actividad/create');
            return;
        }

        try {
            $archivo_adjunto = null;
            
            // Handle file upload
            if (!empty($_FILES['archivo']['name'])) {
                $allowedTypes = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'png', 'zip'];
                $archivo_adjunto = FileUploadHelper::uploadFile(
                    $_FILES['archivo'],
                    'actividades',
                    $allowedTypes,
                    10485760 // 10MB
                );
            }

            $data = [
                'id_asignacion' => $_POST['id_asignacion'],
                'titulo' => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'] ?? null,
                'tipo' => $_POST['tipo'] ?? 'TAREA',
                'fecha_publicacion' => $_POST['fecha_publicacion'] ?? date('Y-m-d H:i:s'),
                'fecha_limite' => $_POST['fecha_limite'] ?? null,
                'puntos_max' => $_POST['puntos_max'] ?? 100,
                'permite_entrega' => isset($_POST['permite_entrega']) ? 1 : 0,
                'archivo_adjunto' => $archivo_adjunto,
                'estado' => $_POST['estado'] ?? 'ACTIVA'
            ];

            $this->actividadModel->create($data);
            $_SESSION['success'] = 'Actividad creada exitosamente';
            $this->redirect('/actividad');

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear actividad: ' . $e->getMessage();
            $this->redirect('/actividad/create');
        }
    }

    /**
     * View submissions for an activity
     */
    public function entregas($id_actividad) {
        $this->requireRole('PROFESOR');

        $actividad = $this->actividadModel->findById($id_actividad);
        if (!$actividad) {
            $_SESSION['error'] = 'Actividad no encontrada';
            $this->redirect('/actividad');
            return;
        }

        // Verify ownership
        $profesor = $this->profesorModel->getByUserId($_SESSION['user_id']);
        if ($actividad['id_profesor'] != $profesor['id_profesor']) {
            $_SESSION['error'] = 'No tiene permiso para ver esta actividad';
            $this->redirect('/actividad');
            return;
        }

        $entregas = $this->entregaModel->getByActividad($id_actividad);

        $this->view('profesor/actividades/entregas', [
            'title' => 'Entregas - ' . $actividad['titulo'],
            'actividad' => $actividad,
            'entregas' => $entregas
        ]);
    }

    /**
     * Grade a submission
     */
    public function calificar($id_entrega) {
        $this->requireRole('PROFESOR');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/actividad');
            return;
        }

        try {
            $calificacion = $_POST['calificacion'] ?? null;
            $retroalimentacion = $_POST['retroalimentacion'] ?? null;

            if ($calificacion === null || $calificacion === '') {
                $_SESSION['error'] = 'La calificación es requerida';
                $this->redirect($_SERVER['HTTP_REFERER'] ?? '/actividad');
                return;
            }

            $this->entregaModel->gradeSubmission($id_entrega, $calificacion, $retroalimentacion);
            $_SESSION['success'] = 'Entrega calificada exitosamente';

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al calificar: ' . $e->getMessage();
        }

        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/actividad');
    }

    /**
     * Delete activity
     */
    public function delete($id) {
        $this->requireRole('PROFESOR');

        try {
            $actividad = $this->actividadModel->findById($id);
            
            // Verify ownership
            $profesor = $this->profesorModel->getByUserId($_SESSION['user_id']);
            if ($actividad['id_profesor'] != $profesor['id_profesor']) {
                $_SESSION['error'] = 'No tiene permiso para eliminar esta actividad';
            } else {
                // Delete file if exists
                if (!empty($actividad['archivo_adjunto'])) {
                    FileUploadHelper::deleteFile($actividad['archivo_adjunto']);
                }
                
                $this->actividadModel->delete($id);
                $_SESSION['success'] = 'Actividad eliminada exitosamente';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar actividad: ' . $e->getMessage();
        }

        $this->redirect('/actividad');
    }
}
