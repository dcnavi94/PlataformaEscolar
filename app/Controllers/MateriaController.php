<?php
// app/Controllers/MateriaController.php

require_once '../app/Core/Controller.php';
require_once '../app/Models/Materia.php';
require_once '../app/Models/Programa.php';

class MateriaController extends Controller {
    private $materiaModel;
    private $programaModel;

    public function __construct() {
        $this->materiaModel = new Materia();
        $this->programaModel = new Programa();
    }

    /**
     * List all materias
     */
    public function index() {
        $this->requireRole('ADMIN');

        $materias = $this->materiaModel->getAllWithPrograms();
        
        $this->view('admin/materias/index', [
            'title' => 'Gestión de Materias',
            'materias' => $materias
        ]);
    }

    /**
     * Show create form
     */
    public function create() {
        $this->requireRole('ADMIN');

        $programas = $this->programaModel->getActive();
        
        $this->view('admin/materias/create', [
            'title' => 'Nueva Materia',
            'programas' => $programas
        ]);
    }

    /**
     * Store new materia
     */
    public function store() {
        $this->requireRole('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/materia');
            return;
        }

        // Validate
        $errors = [];
        
        if (empty($_POST['nombre'])) {
            $errors[] = 'El nombre es requerido';
        }
        
        if (empty($_POST['codigo'])) {
            $errors[] = 'El código es requerido';
        } elseif (!$this->materiaModel->isCodigoUnique($_POST['codigo'])) {
            $errors[] = 'El código ya existe';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            $this->redirect('/materia/create');
            return;
        }

        try {
            $data = [
                'nombre' => $_POST['nombre'],
                'codigo' => $_POST['codigo'],
                'creditos' => $_POST['creditos'] ?? 0,
                'id_programa' => $_POST['id_programa'] ?? null,
                'estado' => $_POST['estado'] ?? 'ACTIVO'
            ];

            $this->materiaModel->insert($data);
            $_SESSION['success'] = 'Materia creada exitosamente';
            $this->redirect('/materia');

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear materia: ' . $e->getMessage();
            $this->redirect('/materia/create');
        }
    }

    /**
     * Show edit form
     */
    public function edit($id) {
        $this->requireRole('ADMIN');

        $materia = $this->materiaModel->find($id);
        if (!$materia) {
            $_SESSION['error'] = 'Materia no encontrada';
            $this->redirect('/materia');
            return;
        }

        $programas = $this->programaModel->getActive();
        
        $this->view('admin/materias/edit', [
            'title' => 'Editar Materia',
            'materia' => $materia,
            'programas' => $programas
        ]);
    }

    /**
     * Update materia
     */
    public function update($id) {
        $this->requireRole('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/materia');
            return;
        }

        // Validate
        $errors = [];
        
        if (empty($_POST['nombre'])) {
            $errors[] = 'El nombre es requerido';
        }
        
        if (empty($_POST['codigo'])) {
            $errors[] = 'El código es requerido';
        } elseif (!$this->materiaModel->isCodigoUnique($_POST['codigo'], $id)) {
            $errors[] = 'El código ya existe';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            $this->redirect('/materia/edit/' . $id);
            return;
        }

        try {
            $data = [
                'nombre' => $_POST['nombre'],
                'codigo' => $_POST['codigo'],
                'creditos' => $_POST['creditos'] ?? 0,
                'id_programa' => $_POST['id_programa'] ?? null,
                'estado' => $_POST['estado'] ?? 'ACTIVO'
            ];

            $this->materiaModel->update($id, $data);
            $_SESSION['success'] = 'Materia actualizada exitosamente';
            $this->redirect('/materia');

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar materia: ' . $e->getMessage();
            $this->redirect('/materia/edit/' . $id);
        }
    }

    /**
     * Delete materia
     */
    public function delete($id) {
        $this->requireRole('ADMIN');

        try {
            // Soft delete - mark as INACTIVO
            $this->materiaModel->update($id, ['estado' => 'INACTIVO']);
            $_SESSION['success'] = 'Materia eliminada exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar materia: ' . $e->getMessage();
        }

        $this->redirect('/materia');
    }
}
