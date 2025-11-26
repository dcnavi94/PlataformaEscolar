<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/EventoCalendario.php';

class CalendarioController extends Controller {
    private $eventoModel;

    public function __construct() {
        $this->requireRole('ADMIN');
        $this->eventoModel = new EventoCalendario();
    }

    public function index() {
        $this->view('layouts/header', ['title' => 'Calendario Escolar']);
        $this->view('admin/calendario/index');
        $this->view('layouts/footer');
    }

    public function getEvents() {
        $events = $this->eventoModel->getAllEvents();
        // Format for FullCalendar
        $formattedEvents = [];
        foreach ($events as $event) {
            $formattedEvents[] = [
                'id' => $event['id_evento'],
                'title' => $event['titulo'],
                'start' => $event['fecha_inicio'],
                'end' => $event['fecha_fin'],
                'color' => $event['color'],
                'description' => $event['descripcion']
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($formattedEvents);
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titulo' => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'],
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin' => $_POST['fecha_fin'],
                'tipo' => $_POST['tipo'],
                'color' => $_POST['color'],
                'created_by' => $_SESSION['id_usuario']
            ];

            try {
                $this->eventoModel->create($data);
                echo json_encode(['status' => 'success']);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }

    public function delete($id) {
        // Logic to delete event
    }
}
