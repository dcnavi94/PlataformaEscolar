<?php
// app/Controllers/AlumnoPortalController.php

require_once '../app/Core/Controller.php';
require_once '../app/Models/Actividad.php';
require_once '../app/Models/EntregaTarea.php';
require_once '../app/Models/RecursoClase.php';
require_once '../app/Models/Alumno.php';
require_once '../app/Helpers/FileUploadHelper.php';

class AlumnoPortalController extends Controller {
    private $actividadModel;
    private $entregaModel;
    private $recursoModel;
    private $alumnoModel;

    public function __construct() {
        $this->actividadModel = new Actividad();
        $this->entregaModel = new EntregaTarea();
        $this->recursoModel = new RecursoClase();
        $this->alumnoModel = new Alumno();
    }

    /**
     * Portal dashboard for student - ORGANIZED BY SUBJECT
     */
    public function dashboard() {
        $this->requireRole('ALUMNO');

        $alumno = $this->alumnoModel->getByUserId($_SESSION['user_id']);
        if (!$alumno) {
            $_SESSION['error'] = 'Perfil de alumno no encontrado';
            $this->redirect('/auth/login');
            return;
        }

        // Get all data
        $pendientes = $this->actividadModel->getPendingForStudent($alumno['id_alumno']);
        $recursos = $this->recursoModel->getVisibleForStudent($alumno['id_alumno']);
        $misEntregas = $this->entregaModel->getByAlumno($alumno['id_alumno']);

        // Organize by materia
        $materias = [];
        
        // Group activities by materia
        foreach ($pendientes as $act) {
            $key = $act['id_materia'];
            if (!isset($materias[$key])) {
                $materias[$key] = [
                    'id_materia' => $act['id_materia'],
                    'materia_nombre' => $act['materia_nombre'],
                    'materia_codigo' => $act['materia_codigo'] ?? '',
                    'actividades' => [],
                    'recursos' => [],
                    'entregas' => []
                ];
            }
            $materias[$key]['actividades'][] = $act;
        }
        
        // Group resources by materia
        foreach ($recursos as $rec) {
            $key = $rec['id_materia'];
            if (!isset($materias[$key])) {
                $materias[$key] = [
                    'id_materia' => $rec['id_materia'],
                    'materia_nombre' => $rec['materia_nombre'],
                    'materia_codigo' => $rec['materia_codigo'] ?? '',
                    'actividades' => [],
                    'recursos' => [],
                    'entregas' => []
                ];
            }
            $materias[$key]['recursos'][] = $rec;
        }
        
        // Group submissions by materia
        foreach ($misEntregas as $ent) {
            $key = $ent['id_materia'];
            if (!isset($materias[$key])) {
                $materias[$key] = [
                    'id_materia' => $ent['id_materia'],
                    'materia_nombre' => $ent['materia_nombre'],
                    'materia_codigo' => $ent['materia_codigo'] ?? '',
                    'actividades' => [],
                    'recursos' => [],
                    'entregas' => []
                ];
            }
            $materias[$key]['entregas'][] = $ent;
        }

        $this->view('alumno/portal/dashboard', [
            'title' => 'Portal Académico',
            'alumno' => $alumno,
            'materias' => $materias,
            'total_pendientes' => count($pendientes),
            'total_recursos' => count($recursos),
            'total_entregas' => count($misEntregas)
        ]);
    }

    /**
     * View all activities
     */
    public function actividades() {
        $this->requireRole('ALUMNO');

        $alumno = $this->alumnoModel->getByUserId($_SESSION['user_id']);
        $actividades = $this->actividadModel->getActiveForStudent($alumno['id_alumno']);

        $this->view('alumno/portal/actividades', [
            'title' => 'Mis Actividades',
            'actividades' => $actividades
        ]);
    }

    /**
     * Show activity details and submit form
     */
    public function verActividad($id_actividad) {
        $this->requireRole('ALUMNO');

        $actividad = $this->actividadModel->findById($id_actividad);
        if (!$actividad) {
            $_SESSION['error'] = 'Actividad no encontrada';
            $this->redirect('/alumnoportal/actividades');
            return;
        }

        $alumno = $this->alumnoModel->getByUserId($_SESSION['user_id']);
        
        // Get existing submission if any
        $entrega = $this->entregaModel->getByActividadAlumno($id_actividad, $alumno['id_alumno']);

        $this->view('alumno/portal/ver_actividad', [
            'title' => $actividad['titulo'],
            'actividad' => $actividad,
            'entrega' => $entrega
        ]);
    }

    /**
     * Submit assignment
     */
    public function entregar() {
        $this->requireRole('ALUMNO');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/alumnoportal/actividades');
            return;
        }

        $id_actividad = $_POST['id_actividad'] ?? null;
        if (!$id_actividad) {
            $_SESSION['error'] = 'Actividad no especificada';
            $this->redirect('/alumnoportal/actividades');
            return;
        }

        try {
            $alumno = $this->alumnoModel->getByUserId($_SESSION['user_id']);
            $actividad = $this->actividadModel->findById($id_actividad);

            // Check if activity allows submissions
            if (!$actividad['permite_entrega']) {
                $_SESSION['error'] = 'Esta actividad no permite entregas';
                $this->redirect('/alumnoportal/ver/' . $id_actividad);
                return;
            }

            // Check deadline
            if ($actividad['fecha_limite'] && strtotime($actividad['fecha_limite']) < time()) {
                $estado = 'TARDIA';
            } else {
                $estado = 'ENVIADA';
            }

            $archivo_entrega = null;
            
            // Handle file upload
            if (!empty($_FILES['archivo']['name'])) {
                $allowedTypes = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'png', 'zip', 'txt'];
                $archivo_entrega = FileUploadHelper::uploadFile(
                    $_FILES['archivo'],
                    'entregas/' . $id_actividad,
                    $allowedTypes,
                    10485760 // 10MB
                );
            }

            $data = [
                'id_actividad' => $id_actividad,
                'id_alumno' => $alumno['id_alumno'],
                'fecha_entrega' => date('Y-m-d H:i:s'),
                'archivo_entrega' => $archivo_entrega,
                'comentarios' => $_POST['comentarios'] ?? null,
                'estado' => $estado
            ];

            $this->entregaModel->submitAssignment($data);
            $_SESSION['success'] = 'Tarea entregada exitosamente';
            $this->redirect('/alumnoportal/ver/' . $id_actividad);

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al entregar tarea: ' . $e->getMessage();
            $this->redirect('/alumnoportal/ver/' . $id_actividad);
        }
    }

    /**
     * View all resources
     */
    public function recursos() {
        $this->requireRole('ALUMNO');

        $alumno = $this->alumnoModel->getByUserId($_SESSION['user_id']);
        $recursos = $this->recursoModel->getVisibleForStudent($alumno['id_alumno']);

        $this->view('alumno/portal/recursos', [
            'title' => 'Recursos de Clase',
            'recursos' => $recursos
        ]);
    }

    /**
     * View my submissions
     */
    public function misEntregas() {
        $this->requireRole('ALUMNO');

        $alumno = $this->alumnoModel->getByUserId($_SESSION['user_id']);
        $entregas = $this->entregaModel->getByAlumno($alumno['id_alumno']);

        $this->view('alumno/portal/mis_entregas', [
            'title' => 'Mis Entregas',
            'entregas' => $entregas
        ]);
    }
}
