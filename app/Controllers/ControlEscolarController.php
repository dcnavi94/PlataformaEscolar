<?php
// app/Controllers/ControlEscolarController.php

require_once '../app/Core/Controller.php';

class ControlEscolarController extends Controller {

    private $solicitudModel;
    private $alumnoModel;

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('ADMIN');
        
        $this->alumnoModel = $this->model('Alumno');
        $this->solicitudModel = $this->model('SolicitudServicio');
    }

    public function index() {
        // Get all requests
        $solicitudes = $this->solicitudModel->getAllWithDetails();
        
        // Get active students for the dropdown
        $alumnos = $this->alumnoModel->getAllWithRelations();

        $data = [
            'solicitudes' => $solicitudes,
            'alumnos' => $alumnos
        ];

        $this->view('layouts/header', ['title' => 'Control Escolar - Solicitudes']);
        $this->view('admin/controlescolar/index', $data);
        $this->view('layouts/footer');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/controlescolar/index');
        }

        $id_alumno = $_POST['id_alumno'] ?? '';
        $tipo = $_POST['tipo_servicio'] ?? '';
        $comentarios = $_POST['comentarios'] ?? '';

        if (empty($id_alumno) || empty($tipo)) {
            $_SESSION['error'] = 'Debe seleccionar un alumno y un tipo de servicio';
            $this->redirect('/controlescolar/index');
        }

        try {
            $this->solicitudModel->create([
                'id_alumno' => $id_alumno,
                'tipo_servicio' => $tipo,
                'comentarios' => $comentarios
            ]);
            $_SESSION['success'] = 'Solicitud creada exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear solicitud: ' . $e->getMessage();
        }

        $this->redirect('/controlescolar/index');
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/controlescolar/index');
        }

        $id_solicitud = $_POST['id_solicitud'];
        $estatus = $_POST['estatus'];

        try {
            $this->solicitudModel->updateStatus($id_solicitud, $estatus);
            $_SESSION['success'] = 'Estatus actualizado correctamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar estatus';
        }

        $this->redirect('/controlescolar/index');
    }
}
