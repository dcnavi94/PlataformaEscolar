<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/LibroDigital.php';
require_once __DIR__ . '/../Models/CategoriaLibro.php';

class BibliotecaController extends Controller {
    private $libroModel;
    private $categoriaModel;

    public function __construct() {
        // Allow both ALUMNO and PROFESOR
        if (!$this->isLoggedIn()) {
            $this->redirect('/auth/login');
        }
        
        $this->libroModel = new LibroDigital();
        $this->categoriaModel = new CategoriaLibro();
    }

    public function index() {
        $categorias = $this->categoriaModel->getAll();
        $librosPorCategoria = [];

        foreach ($categorias as $categoria) {
            $libros = $this->libroModel->getByCategory($categoria['id_categoria']);
            if (!empty($libros)) {
                $librosPorCategoria[] = [
                    'categoria' => $categoria['nombre'],
                    'libros' => $libros
                ];
            }
        }

        // Determine header based on role
        $header = 'layouts/header_alumno'; // Default
        if ($this->hasRole('PROFESOR')) {
            $header = 'layouts/header_profesor';
        } elseif ($this->hasRole('ADMIN')) {
            $header = 'layouts/header';
        }

        $this->view($header, ['title' => 'Biblioteca Digital']);
        $this->view('biblioteca/index', ['librosPorCategoria' => $librosPorCategoria]);
        $this->view('layouts/footer');
    }
    public function detalle($id) {
        $libro = $this->libroModel->find($id);
        if (!$libro) {
            $_SESSION['error'] = "Libro no encontrado.";
            $this->redirect('/biblioteca');
        }

        // Determine header based on role
        $header = 'layouts/header_alumno'; // Default
        if ($this->hasRole('PROFESOR')) {
            $header = 'layouts/header_profesor';
        } elseif ($this->hasRole('ADMIN')) {
            $header = 'layouts/header';
        }

        $this->view($header, ['title' => $libro['titulo']]);
        $this->view('biblioteca/detalle', ['libro' => $libro]);
        $this->view('layouts/footer');
    }
}
