<?php
// app/Controllers/AsignacionController.php

require_once '../app/Core/Controller.php';
require_once '../app/Models/Asignacion.php';
require_once '../app/Models/Profesor.php';
require_once '../app/Models/Materia.php';
require_once '../app/Models/Grupo.php';

class AsignacionController extends Controller {
    private $asignacionModel;
    private $profesorModel;
    private $materiaModel;
    private $grupoModel;

    public function __construct() {
        $this->asignacionModel = new Asignacion();
        $this->profesorModel = new Profesor();
        $this->materiaModel = new Materia();
        $this->grupoModel = new Grupo();
    }

    /**
     * List all asignaciones
     */
    public function index() {
        $this->requireRole('ADMIN');

        $asignaciones = $this->asignacionModel->getAllWithRelations();
        
        $this->view('admin/asignaciones/index', [
            'title' => 'Gestión de Asignaciones',
            'asignaciones' => $asignaciones
        ]);
    }

    /**
     * Show create form
     */
    public function create() {
        $this->requireRole('ADMIN');

        $profesores = $this->profesorModel->getAllActive();
        $materias = $this->materiaModel->getAllActive();
        $grupos = $this->grupoModel->all();
        
        $this->view('admin/asignaciones/create', [
            'title' => 'Nueva Asignación',
            'profesores' => $profesores,
            'materias' => $materias,
            'grupos' => $grupos
        ]);
    }

    /**
     * Store new asignacion
     */
    public function store() {
        $this->requireRole('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/asignacion');
            return;
        }

        // Validate
        $errors = [];
        
        if (empty($_POST['id_profesor'])) {
            $errors[] = 'Debe seleccionar un profesor';
        }
        
        if (empty($_POST['id_materia'])) {
            $errors[] = 'Debe seleccionar una materia';
        }
        
        if (empty($_POST['id_grupo'])) {
            $errors[] = 'Debe seleccionar un grupo';
        }

        // Check if assignment already exists
        if (!empty($_POST['id_profesor']) && !empty($_POST['id_materia']) && !empty($_POST['id_grupo'])) {
            if ($this->asignacionModel->assignmentExists($_POST['id_profesor'], $_POST['id_materia'], $_POST['id_grupo'])) {
                $errors[] = 'Esta asignación ya existe';
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            $this->redirect('/asignacion/create');
            return;
        }

        try {
            $data = [
                'id_profesor' => $_POST['id_profesor'],
                'id_materia' => $_POST['id_materia'],
                'id_grupo' => $_POST['id_grupo'],
                'fecha_asignacion' => date('Y-m-d'),
                'estado_calificacion' => 'ABIERTA'
            ];

            $this->asignacionModel->insert($data);
            $_SESSION['success'] = 'Asignación creada exitosamente';
            $this->redirect('/asignacion');

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear asignación: ' . $e->getMessage();
            $this->redirect('/asignacion/create');
        }
    }

    /**
     * Show edit form
     */
    public function edit($id) {
        $this->requireRole('ADMIN');

        $asignacion = $this->asignacionModel->find($id);
        if (!$asignacion) {
            $_SESSION['error'] = 'Asignación no encontrada';
            $this->redirect('/asignacion');
            return;
        }

        $profesores = $this->profesorModel->getAllActive();
        $materias = $this->materiaModel->getAllActive();
        $grupos = $this->grupoModel->all();
        
        $this->view('admin/asignaciones/edit', [
            'title' => 'Editar Asignación',
            'asignacion' => $asignacion,
            'profesores' => $profesores,
            'materias' => $materias,
            'grupos' => $grupos
        ]);
    }

    /**
     * Update asignacion
     */
    public function update($id) {
        $this->requireRole('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/asignacion');
            return;
        }

        // Validate
        $errors = [];
        
        if (empty($_POST['id_profesor'])) {
            $errors[] = 'Debe seleccionar un profesor';
        }
        
        if (empty($_POST['id_materia'])) {
            $errors[] = 'Debe seleccionar una materia';
        }
        
        if (empty($_POST['id_grupo'])) {
            $errors[] = 'Debe seleccionar un grupo';
        }

        // Check if assignment already exists (excluding current)
        if (!empty($_POST['id_profesor']) && !empty($_POST['id_materia']) && !empty($_POST['id_grupo'])) {
            $sql = "SELECT COUNT(*) as count FROM asignaciones 
                    WHERE id_profesor = ? AND id_materia = ? AND id_grupo = ? AND id_asignacion != ?";
            $stmt = $this->asignacionModel->query($sql, [
                $_POST['id_profesor'],
                $_POST['id_materia'],
                $_POST['id_grupo'],
                $id
            ]);
            $result = $stmt->fetch();
            if ($result['count'] > 0) {
                $errors[] = 'Ya existe otra asignación con esta combinación';
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            $this->redirect('/asignacion/edit/' . $id);
            return;
        }

        try {
            $data = [
                'id_profesor' => $_POST['id_profesor'],
                'id_materia' => $_POST['id_materia'],
                'id_grupo' => $_POST['id_grupo'],
                'estado_calificacion' => $_POST['estado_calificacion'] ?? 'ABIERTA'
            ];

            $this->asignacionModel->update($id, $data);
            $_SESSION['success'] = 'Asignación actualizada exitosamente';
            $this->redirect('/asignacion');

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar asignación: ' . $e->getMessage();
            $this->redirect('/asignacion/edit/' . $id);
        }
    }

    /**
     * Delete asignacion
     */
    public function delete($id) {
        $this->requireRole('ADMIN');

        try {
            // Check if assignment has grades
            $asignacion = $this->asignacionModel->find($id);
            
            if ($asignacion && $asignacion['estado_calificacion'] === 'CERRADA') {
                $_SESSION['error'] = 'No se puede eliminar una asignación con calificaciones registradas';
            } else {
                $this->asignacionModel->delete($id);
                $_SESSION['success'] = 'Asignación eliminada exitosamente';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar asignación: ' . $e->getMessage();
        }

        $this->redirect('/asignacion');
    }
}
