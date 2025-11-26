<?php
// app/Controllers/PeriodoController.php

require_once '../app/Core/Controller.php';

class PeriodoController extends Controller {

    private $periodoModel;
    private $bitacora;

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('ADMIN');
        
        $this->periodoModel = $this->model('Periodo');
        $this->bitacora = $this->model('Bitacora');
    }

    public function index() {

        $sql = "SELECT * FROM periodos ORDER BY anio DESC, numero_periodo DESC";
        $stmt = $this->periodoModel->query($sql);
        $periodos = $stmt->fetchAll();

        $this->view('layouts/header', ['title' => 'Periodos']);
        $this->view('admin/periodos/index', ['periodos' => $periodos]);
        $this->view('layouts/footer');
    }

    public function create() {

        $this->view('layouts/header', ['title' => 'Nuevo Periodo']);
        $this->view('admin/periodos/create');
        $this->view('layouts/footer');
    }

    public function store() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/periodo/index');
        }

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'fecha_inicio' => $_POST['fecha_inicio'] ?? '',
            'fecha_fin' => $_POST['fecha_fin'] ?? '',
            'anio' => (int)$_POST['anio'] ?? date('Y'),
            'numero_periodo' => (int)$_POST['numero_periodo'] ?? 1
        ];

        if (empty($data['nombre']) || empty($data['fecha_inicio']) || empty($data['fecha_fin'])) {
            $_SESSION['error'] = 'Por favor complete todos los campos';
            $this->redirect('/periodo/create');
        }

        try {
            $id = $this->periodoModel->insert($data);
            $this->bitacora->log($_SESSION['user_id'], 'periodos', $id, 'INSERT', "Periodo creado: {$data['nombre']}");
            $_SESSION['success'] = 'Periodo creado exitosamente';
            $this->redirect('/periodo/index');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear periodo';
            $this->redirect('/periodo/create');
        }
    }

    public function edit($id) {

        $periodo = $this->periodoModel->find($id);
        if (!$periodo) {
            $_SESSION['error'] = 'Periodo no encontrado';
            $this->redirect('/periodo/index');
        }

        $this->view('layouts/header', ['title' => 'Editar Periodo']);
        $this->view('admin/periodos/edit', ['periodo' => $periodo]);
        $this->view('layouts/footer');
    }

    public function update($id) {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/periodo/index');
        }

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'fecha_inicio' => $_POST['fecha_inicio'] ?? '',
            'fecha_fin' => $_POST['fecha_fin'] ?? '',
            'anio' => (int)$_POST['anio'] ?? date('Y'),
            'numero_periodo' => (int)$_POST['numero_periodo'] ?? 1
        ];

        try {
            $this->periodoModel->update($id, $data);
            $this->bitacora->log($_SESSION['user_id'], 'periodos', $id, 'UPDATE', "Periodo actualizado");
            $_SESSION['success'] = 'Periodo actualizado exitosamente';
            $this->redirect('/periodo/index');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar periodo';
            $this->redirect('/periodo/edit/' . $id);
        }
    }

    public function delete($id) {

        try {
            $this->periodoModel->delete($id);
            $this->bitacora->log($_SESSION['user_id'], 'periodos', $id, 'DELETE', "Periodo eliminado");
            $_SESSION['success'] = 'Periodo eliminado exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar periodo. Puede tener grupos asociados.';
        }
        $this->redirect('/periodo/index');
    }
}
