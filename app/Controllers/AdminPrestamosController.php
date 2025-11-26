<?php

class AdminPrestamosController extends Controller {
    private $prestamoModel;
    private $materialModel;

    public function __construct() {
        $this->requireRole('ADMIN');
        $this->prestamoModel = $this->model('Prestamo');
        $this->materialModel = $this->model('Material');
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $estado = $_GET['estado'] ?? '';

        $prestamos = $this->prestamoModel->getAll(['search' => $search, 'estado' => $estado]);
        
        $this->view('layouts/header', [
            'title' => 'Gestión de Préstamos',
            'filters' => ['search' => $search, 'estado' => $estado]
        ]);
        $this->view('admin/prestamos/index', [
            'prestamos' => $prestamos,
            'filters' => ['search' => $search, 'estado' => $estado]
        ]);
        $this->view('layouts/footer');
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_usuario = $_POST['id_usuario'];
            $id_material = $_POST['id_material'];
            $cantidad = 1; 
            $fecha_devolucion = $_POST['fecha_devolucion'];
            $observaciones = $_POST['observaciones'];

            // Check availability
            $material = $this->materialModel->getById($id_material);
            if ($material['stock_disponible'] >= $cantidad) {
                $data = [
                    'id_usuario' => $id_usuario,
                    'id_material' => $id_material,
                    'cantidad' => $cantidad,
                    'fecha_devolucion_esperada' => $fecha_devolucion,
                    'observaciones' => $observaciones,
                    'id_admin' => $_SESSION['user_id']
                ];

                if ($this->prestamoModel->create($data)) {
                    $this->materialModel->decreaseAvailableStock($id_material, $cantidad);
                    $this->redirect('/adminprestamos');
                }
            } else {
                $error = "No hay stock disponible para este material.";
            }
        }

        $materiales = $this->materialModel->getAll();
        
        $this->view('layouts/header', ['title' => 'Nuevo Préstamo']);
        $this->view('admin/prestamos/create', ['materiales' => $materiales, 'error' => $error ?? null]);
        $this->view('layouts/footer');
    }

    public function returnLoan($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $estado = $_POST['estado']; 
            $observaciones = $_POST['observaciones'];

            $prestamo = $this->prestamoModel->getById($id);

            if ($this->prestamoModel->returnLoan($id, ['estado' => $estado, 'observaciones' => $observaciones])) {
                if ($estado === 'DEVUELTO') {
                    $this->materialModel->increaseAvailableStock($prestamo['id_material'], $prestamo['cantidad']);
                }
                
                $this->redirect('/adminprestamos');
            }
        }
    }
}
