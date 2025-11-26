<?php

class NominaController extends Controller {
    private $nominaModel;
    private $registroHoraModel;
    private $profesorModel;

    public function __construct() {
        $this->requireRole('ADMIN');
        $this->nominaModel = $this->model('Nomina');
        $this->registroHoraModel = $this->model('RegistroHora');
        $this->profesorModel = $this->model('Profesor');
    }

    public function index() {
        $nominas = $this->nominaModel->getAll();
        
        $this->view('layouts/header', ['title' => 'Gestión de Nómina']);
        $this->view('admin/nomina/index', ['nominas' => $nominas]);
        $this->view('layouts/footer');
    }

    public function horas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Approve hours
            if (isset($_POST['approve_id'])) {
                $this->registroHoraModel->updateStatus($_POST['approve_id'], 'APROBADO');
            }
            // Register hours
            elseif (isset($_POST['id_profesor'])) {
                $data = [
                    'id_profesor' => $_POST['id_profesor'],
                    'fecha' => $_POST['fecha'],
                    'horas' => $_POST['horas'],
                    'tipo_actividad' => $_POST['tipo_actividad'],
                    'descripcion' => $_POST['descripcion'],
                    'estado' => 'APROBADO' // Admin registers as approved directly
                ];
                $this->registroHoraModel->create($data);
            }
            $this->redirect('/nomina/horas');
        }

        $pending = $this->registroHoraModel->getPending();
        $profesores = $this->profesorModel->getAllActive();

        $this->view('layouts/header', ['title' => 'Registro de Horas']);
        $this->view('admin/nomina/horas', ['pending' => $pending, 'profesores' => $profesores]);
        $this->view('layouts/footer');
    }

    public function generar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_profesor = $_POST['id_profesor'];
            $inicio = $_POST['inicio'];
            $fin = $_POST['fin'];

            // Calculate total hours in period
            $horas = $this->registroHoraModel->getByProfesor($id_profesor, $inicio, $fin);
            $totalHoras = 0;
            foreach ($horas as $h) {
                if ($h['estado'] === 'APROBADO') {
                    $totalHoras += $h['horas'];
                }
            }

            // Get professor rate
            $profesor = $this->profesorModel->getById($id_profesor);
            $tarifa = $profesor['tarifa_hora'];
            $bruto = $totalHoras * $tarifa;
            $deducciones = 0; // Can be calculated or input
            $neto = $bruto - $deducciones;

            $data = [
                'id_profesor' => $id_profesor,
                'periodo_inicio' => $inicio,
                'periodo_fin' => $fin,
                'total_horas' => $totalHoras,
                'monto_bruto' => $bruto,
                'deducciones' => $deducciones,
                'monto_neto' => $neto,
                'estado' => 'PENDIENTE'
            ];

            $this->nominaModel->create($data);
            
            // Mark hours as paid? Or keep as approved until payroll is paid?
            // Usually mark as processed. For simplicity, we leave them as APPROVED but linked by date.
            
            $this->redirect('/nomina');
        }

        $profesores = $this->profesorModel->getAllActive();
        
        $this->view('layouts/header', ['title' => 'Generar Nómina']);
        $this->view('admin/nomina/generar', ['profesores' => $profesores]);
        $this->view('layouts/footer');
    }

    public function pagar($id) {
        $this->nominaModel->markAsPaid($id);
        $this->redirect('/nomina');
    }
}
