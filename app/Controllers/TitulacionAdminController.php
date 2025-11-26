<?php
// app/Controllers/TitulacionAdminController.php

require_once '../app/Core/Controller.php';
require_once '../app/Models/ProcesoTitulacion.php';
require_once '../app/Models/RequisitoTitulacion.php';
require_once '../app/Models/CumplimientoRequisito.php';
require_once '../app/Models/Certificado.php';
require_once '../app/Models/Egresado.php';
require_once '../app/Models/Programa.php';

class TitulacionAdminController extends Controller {
    private $procesoModel;
    private $requisitoModel;
    private $cumplimientoModel;
    private $certificadoModel;
    private $egresadoModel;
    private $programaModel;

    public function __construct() {
        $this->requireRole('ADMIN');
        $this->procesoModel = new ProcesoTitulacion();
        $this->requisitoModel = new RequisitoTitulacion();
        $this->cumplimientoModel = new CumplimientoRequisito();
        $this->certificadoModel = new Certificado();
        $this->egressadoModel = new Egresado();
        $this->programaModel = new Programa();
    }

    /**
     * Dashboard de titulación
     */
    public function index() {
        $stats = $this->procesoModel->getStats();
        $procesosRecientes = $this->procesoModel->getAllWithRelations();
        
        $this->view('layouts/header', ['title' => 'Titulación']);
        $this->view('admin/titulacion/index', [
            'stats' => $stats,
            'procesos' => array_slice($procesosRecientes, 0, 10)
        ]);
        $this->view('layouts/footer');
    }

    /**
     * Gestión de requisitos
     */
    public function requisitos() {
        $programas = $this->programaModel->getAll();
        $requisitos = $this->requisitoModel->getAllWithPrograma();
        
        $this->view('layouts/header', ['title' => 'Requisitos de Titulación']);
        $this->view('admin/titulacion/requisitos', [
            'programas' => $programas,
            'requisitos' => $requisitos
        ]);
        $this->view('layouts/footer');
    }

    /**
     * Crear requisito
     */
    public function crearRequisito() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_programa' => $_POST['id_programa'],
                'nombre_requisito' => $_POST['nombre_requisito'],
                'descripcion' => $_POST['descripcion'] ?? null,
                'es_obligatorio' => isset($_POST['es_obligatorio']) ? 1 : 0,
                'tipo_documento' => $_POST['tipo_documento'] ?? 'PDF',
                'orden' => $_POST['orden'] ?? 0
            ];

            if ($this->requisitoModel->insert($data)) {
                $_SESSION['success'] = 'Requisito creado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al crear requisito';
            }

            $this->redirect('/titulacionadmin/requisitos');
        }
    }

    /**
     * Editar requisito
     */
    public function editarRequisito($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre_requisito' => $_POST['nombre_requisito'],
                'descripcion' => $_POST['descripcion'] ?? null,
                'es_obligatorio' => isset($_POST['es_obligatorio']) ? 1 : 0,
                'tipo_documento' => $_POST['tipo_documento'] ?? 'PDF',
                'orden' => $_POST['orden'] ?? 0
            ];

            if ($this->requisitoModel->update($id, $data)) {
                $_SESSION['success'] = 'Requisito actualizado';
            } else {
                $_SESSION['error'] = 'Error al actualizar';
            }

            $this->redirect('/titulacionadmin/requisitos');
        }
    }

    /**
     * Eliminar requisito
     */
    public function eliminarRequisito($id) {
        if ($this->requisitoModel->delete($id)) {
            $_SESSION['success'] = 'Requisito eliminado';
        } else {
            $_SESSION['error'] = 'Error al eliminar';
        }
        $this->redirect('/titulacionadmin/requisitos');
    }

    /**
     * Lista de procesos
     */
    public function procesos() {
        $filtro_estado = $_GET['estado'] ?? null;
        
        if ($filtro_estado) {
            $procesos = $this->procesoModel->getByEstado($filtro_estado);
        } else {
            $procesos = $this->procesoModel->getAllWithRelations();
        }
        
        $this->view('layouts/header', ['title' => 'Procesos de Titulación']);
        $this->view('admin/titulacion/procesos', [
            'procesos' => $procesos,
            'filtro_estado' => $filtro_estado
        ]);
        $this->view('layouts/footer');
    }

    /**
     * Revisar proceso
     */
    public function revisar($id_proceso) {
        $proceso = $this->procesoModel->find($id_proceso);
        if (!$proceso) {
            $_SESSION['error'] = 'Proceso no encontrado';
            $this->redirect('/titulacionadmin/procesos');
            return;
        }

        $cumplimientos = $this->cumplimientoModel->getByProceso($id_proceso);
        $progreso = $this->cumplimientoModel->getProgreso($id_proceso);
        
        $this->view('layouts/header', ['title' => 'Revisar Proceso']);
        $this->view('admin/titulacion/revisar', [
            'proceso' => $proceso,
            'cumplimientos' => $cumplimientos,
            'progreso' => $progreso
        ]);
        $this->view('layouts/footer');
    }

    /**
     * Aprobar requisito
     */
    public function aprobarRequisito($id) {
        $comentarios = $_POST['comentarios'] ?? null;
        
        if ($this->cumplimientoModel->aprobar($id, $_SESSION['user_id'], $comentarios)) {
            $_SESSION['success'] = 'Requisito aprobado';
        } else {
            $_SESSION['error'] = 'Error al aprobar';
        }

        // Redirect back
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . '/titulacionadmin/procesos'));
        exit;
    }

    /**
     * Rechazar requisito
     */
    public function rechazarRequisito($id) {
        $comentarios = $_POST['comentarios'] ?? 'Documento rechazado';
        
        if ($this->cumplimientoModel->rechazar($id, $_SESSION['user_id'], $comentarios)) {
            $_SESSION['success'] = 'Requisito rechazado';
        } else {
            $_SESSION['error'] = 'Error al rechazar';
        }

        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . '/titulacionadmin/procesos'));
        exit;
    }

    /**
     * Aprobar proceso completo
     */
    public function aprobarProceso($id) {
        // Verify all requisitos are approved
        if ($this->cumplimientoModel->todosAprobados($id)) {
            $this->procesoModel->updateEstado($id, 'APROBADO');
            $_SESSION['success'] = 'Proceso aprobado. Puede generar el certificado.';
        } else {
            $_SESSION['error'] = 'No se pueden aprobar. Faltan requisitos obligatorios.';
        }

        $this->redirect('/titulacionadmin/revisar/' . $id);
    }

    /**
     * Generar certificado
     */
    public function generarCertificado($id_proceso) {
        $proceso = $this->procesoModel->find($id_proceso);
        
        if ($proceso['estado'] !== 'APROBADO') {
            $_SESSION['error'] = 'El proceso debe estar aprobado';
            $this->redirect('/titulacionadmin/procesos');
            return;
        }

        $this->view('layouts/header', ['title' => 'Generar Certificado']);
        $this->view('admin/titulacion/generar_certificado', [
            'proceso' => $proceso
        ]);
        $this->view('layouts/footer');
    }

    /**
     * Guardar certificado generado
     */
    public function guardarCertificado($id_proceso) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tipo' => $_POST['tipo'] ?? 'TITULO',
                'nombre_completo' => $_POST['nombre_completo'],
                'programa' => $_POST['programa'],
                'fecha_expedicion' => $_POST['fecha_expedicion'] ?? date('Y-m-d'),
                'fecha_ceremonia' => $_POST['fecha_ceremonia'] ?? null,
                'promedio_final' => $_POST['promedio_final'] ?? null,
                'mencion_honorifica' => $_POST['mencion_honorifica'] ?? null,
                'firmado_por' => $_POST['firmado_por'] ?? 'Dirección General'
            ];

            $id_certificado = $this->certificadoModel->generar($id_proceso, $data);

            if ($id_certificado) {
                // Update proceso estado
                $this->procesoModel->updateEstado($id_proceso, 'TITULADO');
                
                $_SESSION['success'] = 'Certificado generado exitosamente';
                $this->redirect('/titulacionadmin/certificado/' . $id_certificado);
            } else {
                $_SESSION['error'] = 'Error al generar certificado';
                $this->redirect('/titulacionadmin/procesos');
            }
        }
    }

    /**
     * Ver certificado
     */
    public function certificado($id) {
        $certificado = $this->certificadoModel->find($id);
        
        $this->view('layouts/header', ['title' => 'Certificado']);
        $this->view('admin/titulac ion/certificado', [
            'certificado' => $certificado
        ]);
        $this->view('layouts/footer');
    }

    /**
     * Egresados
     */
    public function egresados() {
        $egresados = $this->egresadoModel->getAllWithRelations();
        $stats = $this->egresadoModel->getStats();
        
        $this->view('layouts/header', ['title' => 'Registro de Egresados']);
        $this->view('admin/titulacion/egresados', [
            'egresados' => $egresados,
            'stats' => $stats
        ]);
        $this->view('layouts/footer');
    }
}
