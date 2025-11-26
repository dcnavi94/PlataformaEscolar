<?php
// app/Controllers/ServiciosController.php

require_once '../app/Core/Controller.php';

class ServiciosController extends Controller {

    private $solicitudModel;
    private $alumnoModel;

    public function __construct() {
        $this->requireAuth();
        if (!$this->hasRole('ALUMNO')) {
             $this->redirect('/admin/dashboard');
        }
        $this->solicitudModel = $this->model('SolicitudServicio');
        $this->alumnoModel = $this->model('Alumno');
    }

    public function index() {
        // Get current alumno ID
        $user_id = $_SESSION['user_id']; // This is id_usuario
        // We need id_alumno. Let's assume we can get it or it's in session. 
        // Usually we need to query it.
        // Let's check if we have it in session or query it.
        // AuthController stores 'id_usuario', 'rol', 'nombre', 'email'.
        
        // Let's fetch alumno profile
        $alumno = $this->alumnoModel->getWithUser($user_id); // Wait, getWithUser takes id_alumno? No, let's check Alumno model.
        // Alumno model has getWithUser($id_alumno). We need getByUserId.
        // Let's add getByUserId to Alumno model or query directly.
        // Actually, let's check if we can get it easily.
        // For now, let's query using a simple query or add method.
        
        // Quick fix: Query directly here or add helper.
        // Let's assume we can get it.
        // Get alumno profile
        $alumnoData = $this->alumnoModel->getByUserId($user_id);
        
        if (!$alumnoData) {
            $_SESSION['error'] = 'Perfil de alumno no encontrado';
            $this->redirect('/logout');
        }
        
        $id_alumno = $alumnoData['id_alumno'];
        $solicitudes = $this->solicitudModel->getByAlumno($id_alumno);

        $this->view('layouts/header_alumno', ['title' => 'Servicios Escolares']);
        $this->view('alumno/servicios/index', ['solicitudes' => $solicitudes]);
        $this->view('layouts/footer');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/servicios/index');
        }

        $user_id = $_SESSION['user_id'];
        $alumnoData = $this->alumnoModel->getByUserId($user_id);
        $id_alumno = $alumnoData['id_alumno'];

        $tipo = $_POST['tipo_servicio'] ?? '';
        $comentarios = $_POST['comentarios'] ?? '';

        if (empty($tipo)) {
            $_SESSION['error'] = 'Debe seleccionar un tipo de servicio';
            $this->redirect('/servicios/index');
        }

        try {
            $this->solicitudModel->create([
                'id_alumno' => $id_alumno,
                'tipo_servicio' => $tipo,
                'comentarios' => $comentarios
            ]);
            $_SESSION['success'] = 'Solicitud enviada correctamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al enviar solicitud';
        }

        $this->redirect('/servicios/index');
    }
}
