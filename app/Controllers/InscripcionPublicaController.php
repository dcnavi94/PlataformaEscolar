<?php
// app/Controllers/InscripcionPublicaController.php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/PeriodoInscripcion.php';
require_once __DIR__ . '/../Models/SolicitudInscripcion.php';
require_once __DIR__ . '/../Models/DocumentoInscripcion.php';

class InscripcionPublicaController extends Controller {

    private $periodoModel;
    private $solicitudModel;
    private $documentoModel;

    public function __construct() {
        // NO require authentication - public controller
        $this->periodoModel = $this->model('PeriodoInscripcion');
        $this->solicitudModel = $this->model('SolicitudInscripcion');
        $this->documentoModel = $this->model('DocumentoInscripcion');
    }

    /**
     * Landing page - periodos activos
     */
    public function index() {
        $periodos = $this->periodoModel->getActivos();
        
        $this->view('public/inscripcion/index', [
            'periodos' => $periodos
        ]);
    }

    /**
     * Formulario de inscripción
     */
    public function formulario($id_periodo) {
        $periodo = $this->periodoModel->find($id_periodo);
        
        if (!$periodo || !$this->periodoModel->estaActivo($id_periodo)) {
            $_SESSION['error'] = 'El periodo de inscripción no está activo';
            header('Location: ' . BASE_URL . '/inscripcion');
            exit;
        }

        if (!$this->periodoModel->verificarCupo($id_periodo)) {
            $_SESSION['error'] = 'El cupo para este periodo está completo';
            header('Location: ' . BASE_URL . '/inscripcion');
            exit;
        }

        $this->view('public/inscripcion/formulario', [
            'periodo' => $periodo
        ]);
    }

    /**
     * Guardar solicitud
     */
    public function guardarSolicitud() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/inscripcion');
            exit;
        }

        // Validar duplicados
        $curp = $_POST['curp'];
        $correo = $_POST['correo'];
        
        if ($this->solicitudModel->verificarDuplicado($curp, $correo)) {
            $_SESSION['error'] = 'Ya existe una solditud activa con ese CURP o correo electrónico';
            header('Location: ' . BASE_URL . '/inscripcion/formulario/' . $_POST['id_periodo_inscripcion']);
            exit;
        }

        // Generar folio
        $folio = $this->solicitudModel->generateFolio();

        // Crear solicitud
        $data = [
            'folio' => $folio,
            'id_periodo_inscripcion' => $_POST['id_periodo_inscripcion'],
            'nombre' => $_POST['nombre'],
            'apellido_paterno' => $_POST['apellido_paterno'],
            'apellido_materno' => $_POST['apellido_materno'] ?? null,
            'fecha_nacimiento' => $_POST['fecha_nacimiento'],
            'curp' => strtoupper($curp),
            'sexo' => $_POST['sexo'],
            'correo' => $correo,
            'telefono' => $_POST['telefono'],
            'celular' => $_POST['celular'] ?? null,
            'calle' => $_POST['calle'] ?? null,
            'numero_exterior' => $_POST['numero_exterior'] ?? null,
            'numero_interior' => $_POST['numero_interior'] ?? null,
            'colonia' => $_POST['colonia'] ?? null,
            'ciudad' => $_POST['ciudad'] ?? null,
            'estado_residencia' => $_POST['estado_residencia'] ?? null,
            'codigo_postal' => $_POST['codigo_postal'] ?? null,
            'escuela_procedencia' => $_POST['escuela_procedencia'] ?? null,
            'promedio_anterior' => $_POST['promedio_anterior'] ?? null
        ];

        $id = $this->solicitudModel->insert($data);

        if ($id) {
            $_SESSION['success'] = "¡Solicitud registrada exitosamente! Tu folio es: <strong>$folio</strong>";
            header('Location: ' . BASE_URL . '/inscripcion/documentos/' . $folio);
        } else {
            $_SESSION['error'] = 'Error al registrar la solicitud';
            header('Location: ' . BASE_URL . '/inscripcion/formulario/' . $_POST['id_periodo_inscripcion']);
        }
        exit;
    }

    /**
     * Subir documentos
     */
    public function documentos($folio) {
        $solicitud = $this->solicitudModel->getByFolio($folio);
        
        if (!$solicitud) {
            $_SESSION['error'] = 'Solicitud no encontrada';
            header('Location: ' . BASE_URL . '/inscripcion');
            exit;
        }

        $documentos = $this->documentoModel->getBySolicitud($solicitud['id_solicitud']);

        $this->view('public/inscripcion/documentos', [
            'solicitud' => $solicitud,
            'documentos' => $documentos
        ]);
    }

    /**
     * Procesar upload de documento
     */
    public function subirDocumento() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/inscripcion');
            exit;
        }

        $folio = $_POST['folio'];
        $solicitud = $this->solicitudModel->getByFolio($folio);

        if (!$solicitud) {
            $_SESSION['error'] = 'Solicitud no encontrada';
            header('Location: ' . BASE_URL . '/inscripcion');
            exit;
        }

        // Handle file upload
        if (isset($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/inscripciones/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = $folio . '_' . $_POST['tipo_documento'] . '_' . time() . '_' . $_FILES['documento']['name'];
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['documento']['tmp_name'], $filePath)) {
                $this->documentoModel->upload(
                    $solicitud['id_solicitud'],
                    $_POST['tipo_documento'],
                    '/uploads/inscripciones/' . $fileName,
                    $_FILES['documento']['name']
                );
                $_SESSION['success'] = 'Documento subido exitosamente';
            } else {
                $_SESSION['error'] = 'Error al subir el documento';
            }
        }

        header('Location: ' . BASE_URL . '/inscripcion/documentos/' . $folio);
        exit;
    }

    /**
     * Seguimiento de solicitud
     */
    public function seguimiento() {
        $solicitud = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['folio'])) {
            $solicitud = $this->solicitudModel->getByFolio($_POST['folio']);
            
            if ($solicitud) {
                $documentos = $this->documentoModel->getBySolicitud($solicitud['id_solicitud']);
                $solicitud['documentos'] = $documentos;
            } else {
                $_SESSION['error'] = 'No se encontró ninguna solicitud con ese folio';
            }
        }

        $this->view('public/inscripcion/seguimiento', [
            'solicitud' => $solicitud
        ]);
    }
}
