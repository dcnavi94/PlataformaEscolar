<?php
// app/Controllers/ImportController.php

require_once '../app/Core/Controller.php';

class ImportController extends Controller {

    private $alumnoModel;
    private $programaModel;
    private $grupoModel;
    private $bitacora;

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('ADMIN');
        
        $this->alumnoModel = $this->model('Alumno');
        $this->programaModel = $this->model('Programa');
        $this->grupoModel = $this->model('Grupo');
        $this->bitacora = $this->model('Bitacora');
    }

    public function index() {
        $this->view('layouts/header', ['title' => 'Importación Masiva']);
        $this->view('admin/importar/index');
        $this->view('layouts/footer');
    }

    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/import/index');
        }

        if (!isset($_FILES['archivo_csv']) || $_FILES['archivo_csv']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Por favor seleccione un archivo válido';
            $this->redirect('/import/index');
        }

        $file = $_FILES['archivo_csv']['tmp_name'];
        $handle = fopen($file, "r");

        if ($handle === FALSE) {
            $_SESSION['error'] = 'No se pudo abrir el archivo';
            $this->redirect('/import/index');
        }

        $row = 0;
        $success = 0;
        $errors = [];

        // Get catalogs for validation
        $programas = $this->programaModel->getActive();
        $grupos = $this->grupoModel->getActive();
        
        // Map IDs for faster lookup
        $programaMap = [];
        foreach ($programas as $p) $programaMap[$p['id_programa']] = true;
        
        $grupoMap = [];
        foreach ($grupos as $g) $grupoMap[$g['id_grupo']] = true;

        $this->alumnoModel->beginTransaction();

        try {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;
                
                // Skip header if exists (check first row content or just assume first row is header if user says so)
                // Let's assume first row is header if it contains "Nombre"
                if ($row === 1 && (strtolower($data[0]) === 'nombre' || strtolower($data[0]) === 'name')) {
                    continue;
                }

                // Expected format: Nombre, Apellidos, Correo, Telefono, ID_Programa, ID_Grupo, Beca%
                if (count($data) < 6) {
                    $errors[] = "Fila $row: Formato incorrecto (faltan columnas)";
                    continue;
                }

                $nombre = trim($data[0]);
                $apellidos = trim($data[1]);
                $correo = trim($data[2]);
                $telefono = trim($data[3]);
                $idPrograma = (int)$data[4];
                $idGrupo = (int)$data[5];
                $beca = isset($data[6]) ? floatval($data[6]) : 0;

                // Validations
                if (empty($nombre) || empty($apellidos) || empty($correo)) {
                    $errors[] = "Fila $row: Datos obligatorios faltantes";
                    continue;
                }

                if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Fila $row: Correo inválido ($correo)";
                    continue;
                }

                if (!isset($programaMap[$idPrograma])) {
                    $errors[] = "Fila $row: ID Programa no existe ($idPrograma)";
                    continue;
                }

                if (!isset($grupoMap[$idGrupo])) {
                    $errors[] = "Fila $row: ID Grupo no existe ($idGrupo)";
                    continue;
                }

                // Create student
                try {
                    $alumnoData = [
                        'nombre' => $nombre,
                        'apellidos' => $apellidos,
                        'correo' => $correo,
                        'telefono' => $telefono,
                        'id_programa' => $idPrograma,
                        'id_grupo' => $idGrupo,
                        'porcentaje_beca' => $beca,
                        'password' => 'alumno123' // Default password
                    ];

                    $this->alumnoModel->createWithUser($alumnoData);
                    $success++;

                } catch (Exception $e) {
                    $errors[] = "Fila $row: Error al crear alumno - " . $e->getMessage();
                }
            }

            $this->alumnoModel->commit();
            
            $this->bitacora->log(
                $_SESSION['user_id'],
                'importaciones',
                0,
                'INSERT',
                "Importación masiva: $success alumnos creados"
            );

            fclose($handle);

            $_SESSION['import_result'] = [
                'success_count' => $success,
                'errors' => $errors
            ];

            $this->redirect('/import/resultado');

        } catch (Exception $e) {
            $this->alumnoModel->rollBack();
            fclose($handle);
            $_SESSION['error'] = 'Error crítico en la importación: ' . $e->getMessage();
            $this->redirect('/import/index');
        }
    }

    public function resultado() {
        if (!isset($_SESSION['import_result'])) {
            $this->redirect('/import/index');
        }

        $result = $_SESSION['import_result'];
        unset($_SESSION['import_result']);

        $this->view('layouts/header', ['title' => 'Resultado de Importación']);
        $this->view('admin/importar/resultado', ['result' => $result]);
        $this->view('layouts/footer');
    }

    public function descargarPlantilla() {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="plantilla_alumnos.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM
        
        // Headers
        fputcsv($output, ['Nombre', 'Apellidos', 'Correo', 'Telefono', 'ID_Programa', 'ID_Grupo', 'Porcentaje_Beca']);
        
        // Example row
        fputcsv($output, ['Juan', 'Perez', 'juan@ejemplo.com', '5551234567', '1', '1', '0']);
        
        fclose($output);
        exit;
    }
}
