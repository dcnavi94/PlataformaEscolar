<?php
// app/Controllers/GrupoController.php

require_once '../app/Core/Controller.php';

class GrupoController extends Controller {

    private $grupoModel;
    private $programaModel;
    private $periodoModel;
    private $bitacora;

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('ADMIN');
        
        $this->grupoModel = $this->model('Grupo');
        $this->programaModel = $this->model('Programa');
        $this->periodoModel = $this->model('Periodo');
        $this->bitacora = $this->model('Bitacora');
    }

    public function index() {

        $grupos = $this->grupoModel->getAllWithRelations();

        $this->view('layouts/header', ['title' => 'Grupos']);
        $this->view('admin/grupos/index', ['grupos' => $grupos]);
        $this->view('layouts/footer');
    }

    public function create() {

        $programas = $this->programaModel->getActive();
        
        $sql = "SELECT * FROM periodos ORDER BY anio DESC, numero_periodo DESC";
        $stmt = $this->periodoModel->query($sql);
        $periodos = $stmt->fetchAll();

        $data = [
            'programas' => $programas,
            'periodos' => $periodos
        ];

        $this->view('layouts/header', ['title' => 'Nuevo Grupo']);
        $this->view('admin/grupos/create', $data);
        $this->view('layouts/footer');
    }

    public function store() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/grupo/index');
        }

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'id_programa' => (int)$_POST['id_programa'] ?? 0,
            'id_periodo' => (int)$_POST['id_periodo'] ?? 0,
            'estado' => 'ACTIVO'
        ];

        if (empty($data['nombre']) || $data['id_programa'] === 0 || $data['id_periodo'] === 0) {
            $_SESSION['error'] = 'Por favor complete todos los campos';
            $this->redirect('/grupo/create');
        }

        try {
            $id = $this->grupoModel->insert($data);
            $this->bitacora->log($_SESSION['user_id'], 'grupos', $id, 'INSERT', "Grupo creado: {$data['nombre']}");
            $_SESSION['success'] = 'Grupo creado exitosamente';
            $this->redirect('/grupo/index');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear grupo';
            $this->redirect('/grupo/create');
        }
    }

    public function edit($id) {

        $grupo = $this->grupoModel->find($id);
        if (!$grupo) {
            $_SESSION['error'] = 'Grupo no encontrado';
            $this->redirect('/grupo/index');
        }

        $programas = $this->programaModel->getActive();
        $sql = "SELECT * FROM periodos ORDER BY anio DESC, numero_periodo DESC";
        $stmt = $this->periodoModel->query($sql);
        $periodos = $stmt->fetchAll();

        $data = [
            'grupo' => $grupo,
            'programas' => $programas,
            'periodos' => $periodos
        ];

        $this->view('layouts/header', ['title' => 'Editar Grupo']);
        $this->view('admin/grupos/edit', $data);
        $this->view('layouts/footer');
    }

    public function update($id) {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/grupo/index');
        }

        // Get old group data to compare period
        $oldGrupo = $this->grupoModel->find($id);

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'id_programa' => (int)$_POST['id_programa'] ?? 0,
            'id_periodo' => (int)$_POST['id_periodo'] ?? 0,
            'estado' => $_POST['estado'] ?? 'ACTIVO'
        ];

        try {
            $this->grupoModel->update($id, $data);
            $this->bitacora->log($_SESSION['user_id'], 'grupos', $id, 'UPDATE', "Grupo actualizado");
            
            // Check if period changed
            if ($oldGrupo && $oldGrupo['id_periodo'] != $data['id_periodo']) {
                // Generate charges for all students in the group for the new period
                $this->generateChargesForGroup($id, $data['id_periodo']);
                $_SESSION['success'] = 'Grupo actualizado y cargos generados exitosamente';
            } else {
                $_SESSION['success'] = 'Grupo actualizado exitosamente';
            }
            
            $this->redirect('/grupo/index');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar grupo: ' . $e->getMessage();
            $this->redirect('/grupo/edit/' . $id);
        }
    }

    public function delete($id) {

        // Check for associated alumnos
        $sql = "SELECT COUNT(*) as total FROM alumnos WHERE id_grupo = ?";
        $stmt = $this->grupoModel->query($sql, [$id]);
        $result = $stmt->fetch();

        if ($result['total'] > 0) {
            $_SESSION['error'] = 'No se puede eliminar el grupo porque tiene alumnos asociados';
            $this->redirect('/grupo/index');
        }

        try {
            $this->grupoModel->delete($id);
            $this->bitacora->log($_SESSION['user_id'], 'grupos', $id, 'DELETE', "Grupo eliminado");
            $_SESSION['success'] = 'Grupo eliminado exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar grupo';
        }
        $this->redirect('/grupo/index');
    }

    /**
     * Generate charges for all students in a group for a specific period
     */
    private function generateChargesForGroup($id_grupo, $id_periodo) {
        // Get periodo details
        $periodo = $this->periodoModel->find($id_periodo);
        if (!$periodo) {
            return;
        }

        // Get financial configuration
        $sql = "SELECT * FROM configuracion_financiera LIMIT 1";
        $stmt = $this->grupoModel->query($sql);
        $config = $stmt->fetch();
        $dia_limite = $config['dia_limite_pago'] ?? 10;

        // Get all active students in the group with their program data
        $sql = "SELECT a.id_alumno, a.porcentaje_beca, p.monto_colegiatura, p.id_programa
                FROM alumnos a
                INNER JOIN programas p ON a.id_programa = p.id_programa
                WHERE a.id_grupo = ? AND a.estatus = 'INSCRITO'";
        $stmt = $this->grupoModel->query($sql, [$id_grupo]);
        $alumnos = $stmt->fetchAll();

        if (empty($alumnos)) {
            return;
        }

        // Get concepto_id for Colegiatura (assuming id = 2)
        $id_concepto_colegiatura = 2;

        // Calculate all months between periodo start and end
        $fecha_inicio = new DateTime($periodo['fecha_inicio']);
        $fecha_fin = new DateTime($periodo['fecha_fin']);
        
        $meses = [];
        $current = clone $fecha_inicio;
        while ($current <= $fecha_fin) {
            $meses[] = [
                'mes' => (int)$current->format('n'),
                'anio' => (int)$current->format('Y'),
                'fecha_limite' => $current->format('Y-m-') . str_pad($dia_limite, 2, '0', STR_PAD_LEFT)
            ];
            $current->modify('+1 month');
        }

        // Insert charges for each student for each month
        foreach ($alumnos as $alumno) {
            $monto_base = $alumno['monto_colegiatura'];
            $descuento = $alumno['porcentaje_beca'] / 100;
            $monto_final = $monto_base * (1 - $descuento);

            foreach ($meses as $mes_data) {
                // Check if charge already exists
                $check_sql = "SELECT COUNT(*) as total FROM cargos 
                             WHERE id_alumno = ? AND id_concepto = ? 
                             AND mes = ? AND anio = ?";
                $check_stmt = $this->grupoModel->query($check_sql, [
                    $alumno['id_alumno'],
                    $id_concepto_colegiatura,
                    $mes_data['mes'],
                    $mes_data['anio']
                ]);
                $exists = $check_stmt->fetch();

                if ($exists['total'] == 0) {
                    // Insert charge
                    $insert_sql = "INSERT INTO cargos 
                                  (id_alumno, id_grupo, id_concepto, id_periodo, mes, anio, 
                                   monto, saldo_pendiente, fecha_limite, estatus, fecha_generacion)
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'PENDIENTE', CURDATE())";
                    
                    $this->grupoModel->query($insert_sql, [
                        $alumno['id_alumno'],
                        $id_grupo,
                        $id_concepto_colegiatura,
                        $id_periodo,
                        $mes_data['mes'],
                        $mes_data['anio'],
                        $monto_final,
                        $monto_final,
                        $mes_data['fecha_limite']
                    ]);
                }
            }
        }
    }

    /**
     * View alumnos in a group
     */
    public function alumnos($id) {

        $grupo = $this->grupoModel->find($id);
        if (!$grupo) {
            $_SESSION['error'] = 'Grupo no encontrado';
            $this->redirect('/grupo/index');
        }

        $alumnoModel = $this->model('Alumno');
        $alumnos = $alumnoModel->getByGrupo($id);
        
        // Get available students for this program
        $disponibles = $alumnoModel->getAvailableForGroup($grupo['id_programa']);

        $data = [
            'grupo' => $grupo,
            'alumnos' => $alumnos,
            'disponibles' => $disponibles
        ];

        $this->view('layouts/header', ['title' => 'Alumnos del Grupo']);
        $this->view('admin/grupos/alumnos', $data);
        $this->view('layouts/footer');
    }

    /**
     * Add alumno to group
     */
    public function addAlumno() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/grupo/index');
        }

        $id_grupo = (int)$_POST['id_grupo'];
        $id_alumno = (int)$_POST['id_alumno'];

        if ($id_grupo <= 0 || $id_alumno <= 0) {
            $_SESSION['error'] = 'Datos inválidos';
            $this->redirect('/grupo/index');
        }

        try {
            // Verify group exists
            $grupo = $this->grupoModel->find($id_grupo);
            if (!$grupo) {
                throw new Exception('Grupo no encontrado');
            }

            // Update alumno
            $alumnoModel = $this->model('Alumno');
            $alumnoModel->update($id_alumno, ['id_grupo' => $id_grupo]);
            
            // Generate charges for the student for the current period of the group
            // We can reuse logic or create a specific method. 
            // For now, let's call generateChargesForGroup but restrict to this student if possible, 
            // or just let it run (it checks for existing charges so it's safe but maybe slow if many students).
            // Better: Create a method to generate charges for a single student.
            // For now, to keep it simple and consistent with existing logic:
            $this->generateChargesForStudent($id_alumno, $id_grupo, $grupo['id_periodo']);

            $this->bitacora->log($_SESSION['user_id'], 'grupos', $id_grupo, 'UPDATE', "Alumno $id_alumno agregado al grupo");
            
            $_SESSION['success'] = 'Alumno agregado al grupo exitosamente';
            $this->redirect('/grupo/alumnos/' . $id_grupo);

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al agregar alumno: ' . $e->getMessage();
            $this->redirect('/grupo/alumnos/' . $id_grupo);
        }
    }

    /**
     * Remove alumno from group
     */
    public function removeAlumno($id_grupo, $id_alumno) {
        try {
            $alumnoModel = $this->model('Alumno');
            
            // Verify student belongs to group
            $alumno = $alumnoModel->find($id_alumno);
            if ($alumno['id_grupo'] != $id_grupo) {
                throw new Exception('El alumno no pertenece a este grupo');
            }

            // Update alumno (set group to NULL or 0)
            // Assuming database allows NULL or we use 0. Let's try NULL first if schema allows, or check Model.
            // Model update uses array.
            $alumnoModel->update($id_alumno, ['id_grupo' => null]);

            $this->bitacora->log($_SESSION['user_id'], 'grupos', $id_grupo, 'UPDATE', "Alumno $id_alumno removido del grupo");
            
            $_SESSION['success'] = 'Alumno removido del grupo';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al remover alumno: ' . $e->getMessage();
        }
        
        $this->redirect('/grupo/alumnos/' . $id_grupo);
    }

    /**
     * Generate charges for a single student
     */
    private function generateChargesForStudent($id_alumno, $id_grupo, $id_periodo) {
        // Reuse logic from generateChargesForGroup but for one student
        // This is a helper to avoid code duplication if we refactor, but for now I'll implement it here.
        
        $periodo = $this->periodoModel->find($id_periodo);
        if (!$periodo) return;

        $alumnoModel = $this->model('Alumno');
        $alumno = $alumnoModel->find($id_alumno);
        $programa = $this->programaModel->find($alumno['id_programa']);

        $config = $this->grupoModel->query("SELECT * FROM configuracion_financiera LIMIT 1")->fetch();
        $dia_limite = $config['dia_limite_pago'] ?? 10;
        $id_concepto_colegiatura = 2;

        $fecha_inicio = new DateTime($periodo['fecha_inicio']);
        $fecha_fin = new DateTime($periodo['fecha_fin']);
        
        $monto_base = $programa['monto_colegiatura'];
        $descuento = $alumno['porcentaje_beca'] / 100;
        $monto_final = $monto_base * (1 - $descuento);

        $current = clone $fecha_inicio;
        while ($current <= $fecha_fin) {
            $mes = (int)$current->format('n');
            $anio = (int)$current->format('Y');
            $fecha_limite = $current->format('Y-m-') . str_pad($dia_limite, 2, '0', STR_PAD_LEFT);

            // Check existence
            $check_sql = "SELECT COUNT(*) as total FROM cargos 
                         WHERE id_alumno = ? AND id_concepto = ? 
                         AND mes = ? AND anio = ?";
            $check_stmt = $this->grupoModel->query($check_sql, [
                $id_alumno, $id_concepto_colegiatura, $mes, $anio
            ]);
            
            if ($check_stmt->fetch()['total'] == 0) {
                $insert_sql = "INSERT INTO cargos 
                              (id_alumno, id_grupo, id_concepto, id_periodo, mes, anio, 
                               monto, saldo_pendiente, fecha_limite, estatus, fecha_generacion)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'PENDIENTE', CURDATE())";
                
                $this->grupoModel->query($insert_sql, [
                    $id_alumno, $id_grupo, $id_concepto_colegiatura, $id_periodo,
                    $mes, $anio, $monto_final, $monto_final, $fecha_limite
                ]);
            }
            
            $current->modify('+1 month');
        }
    }
}
