<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/LibroDigital.php';
require_once __DIR__ . '/../Models/CategoriaLibro.php';

class BibliotecaAdminController extends Controller {
    private $libroModel;
    private $categoriaModel;

    public function __construct() {
        $this->requireRole('ADMIN');
        $this->libroModel = new LibroDigital();
        $this->categoriaModel = new CategoriaLibro();
    }

    public function index() {
        $libros = $this->libroModel->getAllWithCategory();
        $this->view('layouts/header', ['title' => 'Biblioteca Digital']);
        $this->view('admin/biblioteca/index', ['libros' => $libros]);
        $this->view('layouts/footer');
    }

    public function create() {
        $categorias = $this->categoriaModel->getAll();
        $this->view('layouts/header', ['title' => 'Subir Libro']);
        $this->view('admin/biblioteca/create', ['categorias' => $categorias]);
        $this->view('layouts/footer');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'];
            $autor = $_POST['autor'];
            $id_categoria = $_POST['id_categoria'];
            $descripcion = $_POST['descripcion'];

            // Handle File Uploads
            $uploadDir = __DIR__ . '/../../public/uploads/biblioteca/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $portadaUrl = '';
            if (isset($_FILES['portada']) && $_FILES['portada']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['portada']['name'], PATHINFO_EXTENSION);
                $filename = 'cover_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['portada']['tmp_name'], $uploadDir . $filename);
                $portadaUrl = '/uploads/biblioteca/' . $filename;
            }

            $archivoUrl = '';
            if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
                $filename = 'book_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['archivo']['tmp_name'], $uploadDir . $filename)) {
                    $archivoUrl = '/uploads/biblioteca/' . $filename;
                } else {
                    $_SESSION['error'] = "Error al mover el archivo subido.";
                    header('Location: ' . BASE_URL . '/bibliotecaadmin/create');
                    exit;
                }
            } else {
                $_SESSION['error'] = "Debes subir un archivo de libro válido (PDF/EPUB).";
                header('Location: ' . BASE_URL . '/bibliotecaadmin/create');
                exit;
            }

            try {
                $this->libroModel->insert([
                    'titulo' => $titulo,
                    'autor' => $autor,
                    'id_categoria' => $id_categoria,
                    'descripcion' => $descripcion,
                    'portada_url' => $portadaUrl,
                    'archivo_url' => $archivoUrl
                ]);
                $_SESSION['success'] = "Libro subido correctamente.";
            } catch (Exception $e) {
                $_SESSION['error'] = "Error al subir libro: " . $e->getMessage();
            }

            header('Location: ' . BASE_URL . '/bibliotecaadmin');
            exit;
        }
    }

    public function delete($id) {
        $libro = $this->libroModel->find($id);
        if ($libro) {
            // Delete files
            if ($libro['portada_url']) {
                $path = __DIR__ . '/../../public' . $libro['portada_url'];
                if (file_exists($path)) unlink($path);
            }
            if ($libro['archivo_url']) {
                $path = __DIR__ . '/../../public' . $libro['archivo_url'];
                if (file_exists($path)) unlink($path);
            }
            
            $this->libroModel->delete($id);
            $_SESSION['success'] = "Libro eliminado.";
        }
        header('Location: ' . BASE_URL . '/bibliotecaadmin');
        exit;
    }
}
