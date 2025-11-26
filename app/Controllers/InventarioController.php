<?php

class InventarioController extends Controller {
    private $materialModel;
    private $categoriaModel;

    public function __construct() {
        $this->requireRole('ADMIN');
        $this->materialModel = $this->model('Material');
        $this->categoriaModel = $this->model('CategoriaMaterial');
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $categoria = $_GET['categoria'] ?? '';
        
        $materiales = $this->materialModel->getAll(['search' => $search, 'categoria' => $categoria]);
        $categorias = $this->categoriaModel->getAll();

        $this->view('layouts/header', [
            'title' => 'Inventario de Materiales',
            'filters' => ['search' => $search, 'categoria' => $categoria]
        ]);
        $this->view('admin/inventario/index', [
            'materiales' => $materiales,
            'categorias' => $categorias,
            'filters' => ['search' => $search, 'categoria' => $categoria]
        ]);
        $this->view('layouts/footer');
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_categoria' => $_POST['id_categoria'],
                'codigo' => $_POST['codigo'],
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'marca' => $_POST['marca'],
                'modelo' => $_POST['modelo'],
                'stock_total' => $_POST['stock'],
                'stock_disponible' => $_POST['stock'],
                'ubicacion' => $_POST['ubicacion']
            ];

            if ($this->materialModel->create($data)) {
                $this->redirect('/inventario');
            } else {
                // Handle error
            }
        }

        $categorias = $this->categoriaModel->getAll();
        $this->view('layouts/header', ['title' => 'Nuevo Material']);
        $this->view('admin/inventario/create', ['categorias' => $categorias]);
        $this->view('layouts/footer');
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_categoria' => $_POST['id_categoria'],
                'codigo' => $_POST['codigo'],
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'marca' => $_POST['marca'],
                'modelo' => $_POST['modelo'],
                'ubicacion' => $_POST['ubicacion']
            ];

            if ($this->materialModel->update($id, $data)) {
                $this->redirect('/inventario');
            }
        }

        $material = $this->materialModel->getById($id);
        $categorias = $this->categoriaModel->getAll();
        $this->view('layouts/header', ['title' => 'Editar Material']);
        $this->view('admin/inventario/edit', ['material' => $material, 'categorias' => $categorias]);
        $this->view('layouts/footer');
    }

    public function delete($id) {
        $this->materialModel->delete($id);
        $this->redirect('/inventario');
    }
}
