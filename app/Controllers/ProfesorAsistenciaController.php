<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Asignacion.php';
require_once __DIR__ . '/../Models/Asistencia.php';
require_once __DIR__ . '/../Models/Profesor.php';

class ProfesorAsistenciaController extends Controller {
    private $asignacionModel;
    private $asistenciaModel;
    private $profesorModel;

    public function __construct() {
        $this->requireRole('PROFESOR');
        $this->asignacionModel = new Asignacion();
        $this->asistenciaModel = new Asistencia();
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

        $asignaciones = $this->asignacionModel->getByProfesor($profesor['id_profesor']);
        
        $this->view('layouts/header_profesor', ['title' => 'Control de Asistencia']);
        $this->view('profesor/asistencia/index', ['asignaciones' => $asignaciones]);
        $this->view('layouts/footer');
    }

    public function tomar($id_asignacion) {
        // Verify ownership
        $id_usuario = $_SESSION['user_id'];
        $profesor = $this->profesorModel->getByUserId($id_usuario);
        $asignacion = $this->asignacionModel->getWithDetails($id_asignacion);

        if ($asignacion['id_profesor'] != $profesor['id_profesor']) {
            $_SESSION['error'] = "No tienes permiso para gestionar esta clase.";
            header('Location: ' . BASE_URL . '/profesorasistencia');
            exit;
        }

        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $alumnos = $this->asignacionModel->getStudentList($id_asignacion);
        $asistencias = $this->asistenciaModel->getByAsignacionAndDate($id_asignacion, $fecha);
        
        // Map existing attendance
        $asistenciaMap = [];
        foreach ($asistencias as $a) {
            $asistenciaMap[$a['id_alumno']] = $a;
        }

        $this->view('layouts/header_profesor', ['title' => 'Tomar Asistencia']);
        $this->view('profesor/asistencia/tomar', [
            'asignacion' => $asignacion,
            'alumnos' => $alumnos,
            'fecha' => $fecha,
            'asistenciaMap' => $asistenciaMap
        ]);
        $this->view('layouts/footer');
    }

    public function save($id_asignacion) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fecha = $_POST['fecha'];
            $asistencias = [];

            foreach ($_POST['asistencia'] as $id_alumno => $estado) {
                $asistencias[] = [
                    'id_asignacion' => $id_asignacion,
                    'id_alumno' => $id_alumno,
                    'fecha' => $fecha,
                    'estado' => $estado,
                    'observaciones' => $_POST['observaciones'][$id_alumno] ?? ''
                ];
            }

            try {
                $this->asistenciaModel->saveAttendance($asistencias);
                $_SESSION['success'] = "Asistencia guardada correctamente.";
            } catch (Exception $e) {
                $_SESSION['error'] = "Error al guardar asistencia: " . $e->getMessage();
            }

            header('Location: ' . BASE_URL . '/profesorasistencia/tomar/' . $id_asignacion . '?fecha=' . $fecha);
            exit;
        }
    }
}
