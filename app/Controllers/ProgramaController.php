<?php
// app/Controllers/ProgramaController.php

require_once '../app/Core/Controller.php';

class ProgramaController extends Controller {

    private $programaModel;
    private $bitacora;

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('ADMIN');
        
        $this->programaModel = $this->model('Programa');
        $this->bitacora = $this->model('Bitacora');
    }

    /**
     * List all programs
     */
    public function index() {

        $programas = $this->programaModel->all();

        $this->view('layouts/header', ['title' => 'Programas Académicos']);
        $this->view('admin/programas/index', ['programas' => $programas]);
        $this->view('layouts/footer');
    }

    /**
     * Show create form
     */
    public function create() {

        $this->view('layouts/header', ['title' => 'Nuevo Programa']);
        $this->view('admin/programas/create');
        $this->view('layouts/footer');
    }

    /**
     * Store new program
     */
    public function store() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/programa/index');
        }

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'tipo' => $_POST['tipo'] ?? '',
            'modalidad' => trim($_POST['modalidad'] ?? 'Virtual'),
            'turno' => trim($_POST['turno'] ?? ''),
            'monto_colegiatura' => floatval($_POST['monto_colegiatura'] ?? 0),
            'monto_inscripcion' => floatval($_POST['monto_inscripcion'] ?? 0),
            'estado' => 'ACTIVO'
        ];

        // Validation
        if (empty($data['nombre']) || empty($data['tipo'])) {
            $_SESSION['error'] = 'Por favor complete todos los campos requeridos';
            $this->redirect('/programa/create');
        }

        if ($data['monto_colegiatura'] <= 0 || $data['monto_inscripcion'] <= 0) {
            $_SESSION['error'] = 'Los montos deben ser mayores a 0';
            $this->redirect('/programa/create');
        }

        try {
            $id = $this->programaModel->insert($data);
            
            $this->bitacora->log(
                $_SESSION['user_id'],
                'programas',
                $id,
                'INSERT',
                "Programa creado: {$data['nombre']}"
            );

            $_SESSION['success'] = 'Programa creado exitosamente';
            $this->redirect('/programa/index');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear programa: ' . $e->getMessage();
            $this->redirect('/programa/create');
        }
    }

    /**
     * Show edit form
     */
    public function edit($id) {

        $programa = $this->programaModel->find($id);

        if (!$programa) {
            $_SESSION['error'] = 'Programa no encontrado';
            $this->redirect('/programa/index');
        }

        $this->view('layouts/header', ['title' => 'Editar Programa']);
        $this->view('admin/programas/edit', ['programa' => $programa]);
        $this->view('layouts/footer');
    }

    /**
     * Update program
     */
    public function update($id) {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/programa/index');
        }

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'tipo' => $_POST['tipo'] ?? '',
            'modalidad' => trim($_POST['modalidad'] ?? 'Virtual'),
            'turno' => trim($_POST['turno'] ?? ''),
            'monto_colegiatura' => floatval($_POST['monto_colegiatura'] ?? 0),
            'monto_inscripcion' => floatval($_POST['monto_inscripcion'] ?? 0),
            'estado' => $_POST['estado'] ?? 'ACTIVO'
        ];

        try {
            $this->programaModel->update($id, $data);
            
            $this->bitacora->log(
                $_SESSION['user_id'],
                'programas',
                $id,
                'UPDATE',
                "Programa actualizado: {$data['nombre']}"
            );

            $_SESSION['success'] = 'Programa actualizado exitosamente';
            $this->redirect('/programa/index');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar programa: ' . $e->getMessage();
            $this->redirect('/programa/edit/' . $id);
        }
    }

    /**
     * Delete program
     */
    public function delete($id) {

        // Check if program has alumnos
        $sql = "SELECT COUNT(*) as total FROM alumnos WHERE id_programa = ?";
        $stmt = $this->programaModel->query($sql, [$id]);
        $result = $stmt->fetch();

        if ($result['total'] > 0) {
            $_SESSION['error'] = 'No se puede eliminar el programa porque tiene alumnos asociados';
            $this->redirect('/programa/index');
        }

        try {
            $programa = $this->programaModel->find($id);
            $this->programaModel->delete($id);
            
            $this->bitacora->log(
                $_SESSION['user_id'],
                'programas',
                $id,
                'DELETE',
                "Programa eliminado: {$programa['nombre']}"
            );

            $_SESSION['success'] = 'Programa eliminado exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar programa';
        }

        $this->redirect('/programa/index');
    }
}
