<?php
// app/Controllers/KardexController.php

require_once '../app/Core/Controller.php';
require_once '../app/Models/Calificacion.php';
require_once '../app/Models/Alumno.php';

class KardexController extends Controller {
    private $calificacionModel;
    private $alumnoModel;

    public function __construct() {
        $this->calificacionModel = new Calificacion();
        $this->alumnoModel = new Alumno();
    }

    /**
     * Show student kardex (grade report)
     */
    public function index() {
        $this->requireRole('ALUMNO');

        // Get alumno by user ID
        $alumno = $this->alumnoModel->getByUserIdWithRelations($_SESSION['user_id']);
        
        if (!$alumno) {
            $_SESSION['error'] = 'Perfil de alumno no encontrado';
            $this->redirect('/auth/login');
            return;
        }

        // Get all grades
        $calificaciones = $this->calificacionModel->getByAlumno($alumno['id_alumno']);
        
        // Calculate average
        $promedio = $this->calificacionModel->getPromedioByAlumno($alumno['id_alumno']);
        
        $this->view('alumno/kardex/index', [
            'title' => 'Mi Kardex',
            'alumno' => $alumno,
            'calificaciones' => $calificaciones,
            'promedio' => $promedio
        ]);
    }
}
