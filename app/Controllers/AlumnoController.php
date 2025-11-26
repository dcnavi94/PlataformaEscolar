<?php
// app/Controllers/AlumnoController.php

require_once '../app/Core/Controller.php';

class AlumnoController extends Controller {

    private $alumnoModel;
    private $cargoModel;
    private $asignacionModel;

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('ALUMNO');
        
        $this->alumnoModel = $this->model('Alumno');
        $this->cargoModel = $this->model('Cargo');
        $this->asignacionModel = $this->model('Asignacion');
    }

    /**
     * Index method
     */
    public function index() {
        $this->redirect('/alumno/dashboard');
    }

    /**
     * Alumno dashboard
     */
    public function dashboard() {
        // Get alumno data by user_id with relations
        $alumno = $this->alumnoModel->getByUserIdWithRelations($_SESSION['user_id']);

        if (!$alumno) {
            $_SESSION['error'] = 'No se encontró información del alumno';
            $this->redirect('/auth/logout');
        }

        // Get cargos
        $cargos = $this->cargoModel->getByAlumno($alumno['id_alumno']);

        // Calculate statistics
        $stats = [
            'total_pendiente' => 0,
            'total_vencido' => 0,
            'total_pagado' => 0,
            'num_pendientes' => 0,
            'num_vencidos' => 0
        ];

        foreach ($cargos as $cargo) {
            if ($cargo['estatus'] === 'PENDIENTE' || $cargo['estatus'] === 'PARCIAL') {
                $stats['total_pendiente'] += $cargo['saldo_pendiente'];
                $stats['num_pendientes']++;
            } elseif ($cargo['estatus'] === 'VENCIDO' || $cargo['estatus'] === 'PENALIZACION') {
                $stats['total_vencido'] += $cargo['saldo_pendiente'];
                $stats['num_vencidos']++;
            } elseif ($cargo['estatus'] === 'PAGADO') {
                $stats['total_pagado'] += $cargo['monto'];
            }
        }

        // Get enrolled subjects
        $asignaciones = $this->asignacionModel->getByAlumno($alumno['id_alumno']);

        $data = [
            'title' => 'Mi Portal',
            'alumno' => $alumno,
            'stats' => $stats,
            'asignaciones' => $asignaciones,
            'is_moroso' => $_SESSION['is_moroso'] ?? false
        ];

        $this->view('layouts/header_alumno', $data);
        $this->view('alumno/dashboard', $data);
        $this->view('layouts/footer');
    }

    /**
     * View all charges/payments
     */
    public function pagos() {
        // Get alumno data
        $sql = "SELECT * FROM alumnos WHERE id_usuario = ?";
        $stmt = $this->alumnoModel->query($sql, [$_SESSION['user_id']]);
        $alumno = $stmt->fetch();

        // Get cargos
        $cargos = $this->cargoModel->getByAlumno($alumno['id_alumno']);

        $data = [
            'title' => 'Mis Pagos',
            'cargos' => $cargos,
            'alumno' => $alumno,
            'paypal_client_id' => $_ENV['PAYPAL_CLIENT_ID'] ?? ''
        ];

        $this->view('layouts/header_alumno', $data);
        $this->view('alumno/pagos', $data);
        $this->view('layouts/footer');
    }


    /**
     * Student Course View
     */
    public function curso($id_asignacion) {
        // Get alumno data
        $sql = "SELECT * FROM alumnos WHERE id_usuario = ?";
        $stmt = $this->alumnoModel->query($sql, [$_SESSION['user_id']]);
        $alumno = $stmt->fetch();

        if (!$alumno) {
            $this->redirect('/auth/logout');
        }

        // Verify enrollment (optional but good for security)
        // For now, we trust the ID if it exists, but ideally check if student is in the group

        // Get course details
        $asignacion = $this->asignacionModel->getWithDetails($id_asignacion);
        
        if (!$asignacion) {
            $_SESSION['error'] = 'Curso no encontrado';
            $this->redirect('/alumno/dashboard');
        }

        // Get modules and topics
        require_once '../app/Models/Modulo.php';
        $moduloModel = new Modulo();
        $modulos = $moduloModel->getCourseStructure($id_asignacion);

        // Get orphaned content (same logic as professor view)
        require_once '../app/Models/Actividad.php';
        require_once '../app/Models/RecursoClase.php';
        $actividadModel = new Actividad();
        $recursoModel = new RecursoClase();

        $actividades = $actividadModel->getByAsignacion($id_asignacion);
        $recursos = $recursoModel->getByAsignacion($id_asignacion);

        $orphanedContent = [];
        foreach ($actividades as $act) {
            if (empty($act['id_tema'])) {
                $act['item_type'] = 'ACTIVIDAD';
                $orphanedContent[] = $act;
            }
        }
        foreach ($recursos as $rec) {
            if (empty($rec['id_tema'])) {
                $rec['item_type'] = 'RECURSO';
                $orphanedContent[] = $rec;
            }
        }

        // Sort orphaned content by date
        usort($orphanedContent, function($a, $b) {
            return strtotime($b['fecha_publicacion']) - strtotime($a['fecha_publicacion']);
        });

        $data = [
            'title' => $asignacion['materia_nombre'],
            'asignacion' => $asignacion,
            'modulos' => $modulos,
            'orphanedContent' => $orphanedContent,
            'alumno' => $alumno
        ];

        // Use a specific view for student course
        $this->view('alumno/curso/index', $data);
    }
}
