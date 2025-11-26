<?php
// app/Controllers/TitulacionAlumnoController.php

require_once '../app/Core/Controller.php';
require_once '../app/Models/ProcesoTitulacion.php';
require_once '../app/Models/RequisitoTitulacion.php';
require_once '../app/Models/CumplimientoRequisito.php';
require_once '../app/Models/Certificado.php';
require_once '../app/Models/Alumno.php';
require_once '../app/Helpers/FileUploadHelper.php';

class TitulacionAlumnoController extends Controller {
    private $procesoModel;
    private $requisitoModel;
    private $cumplimientoModel;
    private $certificadoModel;
    private $alumnoModel;

    public function __construct() {
        $this->requireRole('ALUMNO');
        $this->procesoModel = new ProcesoTitulacion();
        $this->requisitoModel = new RequisitoTitulacion();
        $this->cumplimientoModel = new CumplimientoRequisito();
        $this->certificadoModel = new Certificado();
        $this->alumnoModel = new Alumno();
    }

    /**
     * Dashboard de titulación del alumno
     */
    public function index() {
        $alumno = $this->alumnoModel->getByUserId($_SESSION['user_id']);
        
        if (!$alumno) {
            $_SESSION['error'] = 'Perfil de alumno no encontrado';
            $this->redirect('/alumno/dashboard');
            return;
        }

        $proceso = $this->procesoModel->getByAlumno($alumno['id_alumno']);
        $cumplimientos = null;
        $progreso = 0;

        if ($proceso) {
            $cumplimientos = $this->cumplimientoModel->getByProceso($proceso['id_proceso']);
            $progreso = $this->cumplimientoModel->getProgreso($proceso['id_proceso']);
        }

        $requisitos_programa = $this->requisitoModel->getByPrograma($alumno['id_grupo']);

        $this->view('layouts/header_alumno', ['title' => 'Mi Titulación']);
        $this->view('alumno/titulacion/index', [
            'alumno' => $alumno,
            'proceso' => $proceso,
            'cumplimientos' => $cumplimientos,
            'progreso' => $progreso,
            'requisitos_programa' => $requisitos_programa
        ]);
        $this->view('layouts/footer');
    }

    /**
     * Solicitar proceso de titulación
     */
    public function solicitar() {
        $alumno = $this->alumnoModel->getByUserId($_SESSION['user_id']);

        // Check if already has proceso activo
        if ($this->procesoModel->hasProcesoActivo($alumno['id_alumno'])) {
            $_SESSION['error'] = 'Ya tienes un proceso de titulación activo';
            $this->redirect('/titulacionalumno');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view('layouts/header_alumno', ['title' => 'Solicitar Titulación']);
            $this->view('alumno/titulacion/solicitar', [
                'alumno' => $alumno
            ]);
            $this->view('layouts/footer');
            return;
        }

        // POST - Process solicitud
        $data = [
            'id_alumno' => $alumno['id_alumno'],
            'id_programa' => $alumno['id_grupo'], // Assuming grupo contains programa info
            'fecha_solicitud' => date('Y-m-d'),
            'modalidad' => $_POST['modalidad'],
            'numero_folio' => $this->procesoModel->generateFolio(),
            'observaciones' => $_POST['observaciones'] ?? null
        ];

        $id_proceso = $this->procesoModel->insert($data);

        if ($id_proceso) {
            // Initialize requisitos
            $this->cumplimientoModel->inicializarRequisitos($id_proceso, $alumno['id_grupo']);
            
            $_SESSION['success'] = 'Solicitud de titulación enviada. Folio: ' . $data['numero_folio'];
            $this->redirect('/titulacionalumno');
        } else {
            $_SESSION['error'] = 'Error al crear solicitud';
            $this->redirect('/titulacionalumno/solicitar');
        }
    }

    /**
     * Subir documento para requisito
     */
    public function subirDocumento($id_cumplimiento) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/titulacionalumno');
            return;
        }

        if (empty($_FILES['documento']['name'])) {
            $_SESSION['error'] = 'No se seleccionó ningún archivo';
            $this->redirect('/titulacionalumno');
            return;
        }

        try {
            $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png'];
            $filePath = FileUploadHelper::uploadFile(
                $_FILES['documento'],
                'titulacion',
                $allowedTypes,
                5242880 // 5MB
            );

            if ($this->cumplimientoModel->cargarDocumento($id_cumplimiento, $filePath)) {
                $_SESSION['success'] = 'Documento cargado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al guardar documento';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al subir archivo: ' . $e->getMessage();
        }

        $this->redirect('/titulacionalumno');
    }

    /**
     * Descargar certificado
     */
    public function certificado() {
        $alumno = $this->alumnoModel->getByUserId($_SESSION['user_id']);
        $certificados = $this->certificadoModel->getByAlumno($alumno['id_alumno']);

        $this->view('layouts/header_alumno', ['title' => 'Mi Certificado']);
        $this->view('alumno/titulacion/certificado', [
            'certificados' => $certificados
        ]);
        $this->view('layouts/footer');
    }
}
