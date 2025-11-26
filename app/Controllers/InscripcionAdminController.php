<?php
// app/Controllers/InscripcionAdminController.php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/PeriodoInscripcion.php';
require_once __DIR__ . '/../Models/SolicitudInscripcion.php';
require_once __DIR__ . '/../Models/DocumentoInscripcion.php';
require_once __DIR__ . '/../Models/Alumno.php';
require_once __DIR__ . '/../Models/Usuario.php';

class InscripcionAdminController extends Controller {

    private $periodoModel;
    private $solicitudModel;
    private $documentoModel;
    private $alumnoModel;
    private $usuarioModel;

    public function __construct() {
        $this->requireRole('ADMIN');
        $this->periodoModel = $this->model('PeriodoInscripcion');
        $this->solicitudModel = $this->model('SolicitudInscripcion');
        $this->documentoModel = $this->model('DocumentoInscripcion');
        $this->alumnoModel = $this->model('Alumno');
        $this->usuarioModel = $this->model('Usuario');
    }

    /**
     * Dashboard de inscripciones
     */
    public function index() {
        $stats = $this->solicitudModel->getStats();
        $solicitudes_recientes = $this->solicitudModel->getByEstado('PENDIENTE');
        
        $this->view('layouts/header', ['title' => 'Inscripciones']);
        $this->view('admin/inscripcion/index', [
            'stats' => $stats,
            'solicitudes_recientes' => $solicitudes_recientes
        ]);
        $this->view('layouts/footer');
    }

    /**
     * Gestión de periodos
     */
    public function periodos() {
        $periodos = $this->periodoModel->getAllWithRelations();
        $programasModel = $this->model('Programa');
        $programas = $programasModel->getActive();
        
        $this->view('layouts/header', ['title' => 'Periodos de Inscripción']);
        $this->view('admin/inscripcion/periodos', [
            'periodos' => $periodos,
            'programas' => $programas
        ]);
        $this->view('layouts/footer');
    }

    /**
     * Crear periodo
     */
    public function crearPeriodo() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/inscripcionadmin/periodos');
            exit;
        }

        $data = [
            'nombre' => $_POST['nombre'],
            'id_programa' => $_POST['id_programa'],
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $_POST['fecha_fin'],
            'cupo_maximo' => $_POST['cupo_maximo'] ?? 0,
            'monto_inscripcion' => $_POST['monto_inscripcion'] ?? 0,
            'requisitos_texto' => $_POST['requisitos_texto'] ?? null,
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];

        if ($this->periodoModel->insert($data)) {
            $_SESSION['success'] = 'Periodo creado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al crear el periodo';
        }

        header('Location: ' . BASE_URL . '/inscripcionadmin/periodos');
        exit;
    }

    /**
     * Lista de solicitudes
     */
    public function solicitudes() {
        $estado = $_GET['estado'] ?? null;
        
        if ($estado) {
            $solicitudes = $this->solicitudModel->getByEstado($estado);
        } else {
            $solicitudes = $this->solicitudModel->getAllWithRelations();
        }

        $this->view('layouts/header', ['title' => 'Solicitudes de Inscripción']);
        $this->view('admin/inscripcion/solicitudes', [
            'solicitudes' => $solicitudes,
            'estado_filtro' => $estado
        ]);
        $this->view('layouts/footer');
    }

    /**
     * Revisar solicitud individual
     */
    public function revisar($id) {
        $solicitud = $this->solicitudModel->find($id);
        
        if (!$solicitud) {
            $_SESSION['error'] = 'Solicitud no encontrada';
            header('Location: ' . BASE_URL . '/inscripcionadmin/solicitudes');
            exit;
        }

        $solicitud_full = $this->solicitudModel->getByFolio($solicitud['folio']);
        $documentos = $this->documentoModel->getBySolicitud($id);

        $this->view('layouts/header', ['title' => 'Revisar Solicitud']);
        $this->view('admin/inscripcion/revisar', [
            'solicitud' => $solicitud_full,
            'documentos' => $documentos
        ]);
        $this->view('layouts/footer');
    }

    /**
     * Aprobar solicitud y generar matrícula
     */
    public function aprobar($id) {
        $solicitud = $this->solicitudModel->find($id);
        
        if (!$solicitud) {
            $_SESSION['error'] = 'Solicitud no encontrada';
            header('Location: ' . BASE_URL . '/inscripcionadmin/solicitudes');
            exit;
        }

        // Generar matrícula
        $alumno_id = $this->generarMatricula($id);

        if ($alumno_id) {
            $this->solicitudModel->updateEstado($id, 'MATRICULADO', 'Solicitud aprobada y matrícula generada', $_SESSION['user_id']);
            $this->solicitudModel->update($id, ['id_alumno_generado' => $alumno_id]);
            $_SESSION['success'] = 'Solicitud aprobada y matrícula generada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al generar la matrícula';
        }

        header('Location: ' . BASE_URL . '/inscripcionadmin/revisar/' . $id);
        exit;
    }

    /**
     * Rechazar solicitud
     */
    public function rechazar($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/inscripcionadmin/solicitudes');
            exit;
        }

        $comentarios = $_POST['comentarios'] ?? 'No se especificó motivo';
        
        if ($this->solicitudModel->updateEstado($id, 'RECHAZADA', $comentarios, $_SESSION['user_id'])) {
            $_SESSION['success'] = 'Solicitud rechazada';
        } else {
            $_SESSION['error'] = 'Error al rechazar la solicitud';
        }

        header('Location: ' . BASE_URL . '/inscripcionadmin/solicitudes');
        exit;
    }

    /**
     * Generar matrícula (crear usuario + alumno)
     */
    private function generarMatricula($id_solicitud) {
        $solicitud = $this->solicitudModel->find($id_solicitud);
        
        // Crear usuario
        $password = bin2hex(random_bytes(4)); // Password temporal
        $usuario_data = [
            'correo' => $solicitud['correo'],
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'rol' => 'ALUMNO',
            'nombre' => $solicitud['nombre'] . ' ' . $solicitud['apellido_paterno']
        ];

        $usuario_id = $this->usuarioModel->insert($usuario_data);

        if (!$usuario_id) {
            return false;
        }

        // Get periodo data
        $periodo = $this->periodoModel->find($solicitud['id_periodo_inscripcion']);

        // Crear alumno
        $alumno_data = [
            'id_usuario' => $usuario_id,
            'nombre' => $solicitud['nombre'],
            'apellidos' => $solicitud['apellido_paterno'] . ' ' . ($solicitud['apellido_materno'] ?? ''),
            'correo' => $solicitud['correo'],
            'telefono' => $solicitud['telefono'],
            'estatus' => 'INSCRITO',
            'id_programa' => $periodo['id_programa']
        ];

        $alumno_id = $this->alumnoModel->insert($alumno_data);

        // TODO: Send email with credentials
        
        return $alumno_id;
    }
}
