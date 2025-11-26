<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Grupo.php';
require_once __DIR__ . '/../Models/Asistencia.php';

class AsistenciaController extends Controller {
    private $grupoModel;
    private $asistenciaModel;

    public function __construct() {
        $this->requireRole('ADMIN');
        $this->grupoModel = new Grupo();
        $this->asistenciaModel = new Asistencia();
    }

    public function index() {
        $grupos = $this->grupoModel->getAll();
        $this->view('layouts/header', ['title' => 'Reportes de Asistencia']);
        $this->view('admin/asistencia/index', ['grupos' => $grupos]);
        $this->view('layouts/footer');
    }

    public function reporte($id_grupo) {
        $grupo = $this->grupoModel->findById($id_grupo);
        if (!$grupo) {
            $_SESSION['error'] = "Grupo no encontrado.";
            header('Location: ' . BASE_URL . '/asistencia');
            exit;
        }

        $month = $_GET['month'] ?? date('m');
        $year = $_GET['year'] ?? date('Y');

        $reporte = $this->asistenciaModel->getReportByGroup($id_grupo, $month, $year);

        // Process report for view: Group by student
        $students = [];
        $dates = [];

        foreach ($reporte as $row) {
            // Always initialize student to ensure they appear in the list even without attendance
            if (!isset($students[$row['id_alumno']])) {
                $students[$row['id_alumno']] = [
                    'nombre' => $row['apellidos'] . ' ' . $row['nombre'],
                    'asistencias' => []
                ];
            }

            // If no attendance record (null date), skip date processing
            if ($row['fecha'] === null) {
                continue;
            }

            $date = date('d', strtotime($row['fecha']));
            if (!in_array($date, $dates)) {
                $dates[] = $date;
            }
            
            $students[$row['id_alumno']]['asistencias'][$date] = [
                'estado' => $row['estado'],
                'materia' => $row['materia']
            ];
        }
        
        sort($dates);

        $this->view('layouts/header', ['title' => 'Reporte de Asistencia']);
        $this->view('admin/asistencia/reporte', [
            'grupo' => $grupo,
            'students' => $students,
            'dates' => $dates,
            'month' => $month,
            'year' => $year
        ]);
        $this->view('layouts/footer');
    }
}
