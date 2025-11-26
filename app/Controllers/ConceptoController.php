<?php
// app/Controllers/ConceptoController.php

require_once '../app/Core/Controller.php';

class ConceptoController extends Controller {

    private $conceptoModel;
    private $bitacora;

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('ADMIN');
        
        $this->conceptoModel = $this->model('ConceptoPago'); // Assuming model name
        $this->bitacora = $this->model('Bitacora');
    }

    public function index() {
        $conceptos = $this->conceptoModel->all();
        $this->view('layouts/header', ['title' => 'Conceptos de Pago']);
        $this->view('admin/conceptos/index', ['conceptos' => $conceptos]);
        $this->view('layouts/footer');
    }

    public function create() {
        $this->view('layouts/header', ['title' => 'Nuevo Concepto']);
        $this->view('admin/conceptos/create');
        $this->view('layouts/footer');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/concepto/index');
        }

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'monto_default' => floatval($_POST['monto_default'] ?? 0),
            'dias_tolerancia' => (int)($_POST['dias_tolerancia'] ?? 0),
            'aplica_beca' => isset($_POST['aplica_beca']) ? 1 : 0,
            'recargo_fijo' => floatval($_POST['recargo_fijo'] ?? 0),
            'recargo_porcentaje' => floatval($_POST['recargo_porcentaje'] ?? 0)
        ];

        if (empty($data['nombre'])) {
            $_SESSION['error'] = 'El nombre es obligatorio';
            $this->redirect('/concepto/create');
        }

        try {
            $id = $this->conceptoModel->insert($data);
            $this->bitacora->log($_SESSION['user_id'], 'conceptos_pago', $id, 'INSERT', "Concepto creado: {$data['nombre']}");
            $_SESSION['success'] = 'Concepto creado exitosamente';
            $this->redirect('/concepto/index');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear concepto';
            $this->redirect('/concepto/create');
        }
    }

    public function edit($id) {
        $concepto = $this->conceptoModel->find($id);
        if (!$concepto) {
            $_SESSION['error'] = 'Concepto no encontrado';
            $this->redirect('/concepto/index');
        }

        $this->view('layouts/header', ['title' => 'Editar Concepto']);
        $this->view('admin/conceptos/edit', ['concepto' => $concepto]);
        $this->view('layouts/footer');
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/concepto/index');
        }

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'monto_default' => floatval($_POST['monto_default'] ?? 0),
            'dias_tolerancia' => (int)($_POST['dias_tolerancia'] ?? 0),
            'aplica_beca' => isset($_POST['aplica_beca']) ? 1 : 0,
            'recargo_fijo' => floatval($_POST['recargo_fijo'] ?? 0),
            'recargo_porcentaje' => floatval($_POST['recargo_porcentaje'] ?? 0)
        ];

        try {
            $this->conceptoModel->update($id, $data);
            $this->bitacora->log($_SESSION['user_id'], 'conceptos_pago', $id, 'UPDATE', "Concepto actualizado");
            $_SESSION['success'] = 'Concepto actualizado exitosamente';
            $this->redirect('/concepto/index');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar concepto';
            $this->redirect('/concepto/edit/' . $id);
        }
    }

    public function delete($id) {
        // Check if used in cargos
        $sql = "SELECT COUNT(*) as total FROM cargos WHERE id_concepto = ?";
        $stmt = $this->conceptoModel->query($sql, [$id]);
        $result = $stmt->fetch();

        if ($result['total'] > 0) {
            $_SESSION['error'] = 'No se puede eliminar porque hay cargos asociados';
            $this->redirect('/concepto/index');
        }

        try {
            $this->conceptoModel->delete($id);
            $this->bitacora->log($_SESSION['user_id'], 'conceptos_pago', $id, 'DELETE', "Concepto eliminado");
            $_SESSION['success'] = 'Concepto eliminado exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar concepto';
        }
        $this->redirect('/concepto/index');
    }
}
