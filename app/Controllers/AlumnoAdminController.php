<?php
// app/Controllers/AlumnoAdminController.php

require_once '../app/Core/Controller.php';

class AlumnoAdminController extends Controller {

    private $alumnoModel;
    private $programaModel;
    private $grupoModel;
    private $cargoModel;
    private $bitacora;

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('ADMIN');
        
        $this->alumnoModel = $this->model('Alumno');
        $this->programaModel = $this->model('Programa');
        $this->grupoModel = $this->model('Grupo');
        $this->cargoModel = $this->model('Cargo');
        $this->bitacora = $this->model('Bitacora');
    }

    public function index() {

        $alumnos = $this->alumnoModel->getAllWithRelations();

        $this->view('layouts/header', ['title' => 'Alumnos']);
        $this->view('admin/alumnos/index', ['alumnos' => $alumnos]);
        $this->view('layouts/footer');
    }

    public function create() {

        $programas = $this->programaModel->getActive();
        $grupos = $this->grupoModel->getActive();

        $this->view('layouts/header', ['title' => 'Nuevo Alumno']);
        $this->view('admin/alumnos/create', ['programas' => $programas, 'grupos' => $grupos]);
        $this->view('layouts/footer');
    }

    public function store() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/alumnoadmin/index');
        }

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellidos' => trim($_POST['apellidos'] ?? ''),
            'matricula' => trim($_POST['matricula'] ?? ''),
            'correo' => trim($_POST['correo'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'id_programa' => (int)($_POST['id_programa'] ?? 0),
            'id_grupo' => (int)($_POST['id_grupo'] ?? 0),
            'porcentaje_beca' => floatval($_POST['porcentaje_beca'] ?? 0),
            'password' => $_POST['password'] ?? 'alumno123'
        ];

        // Validation
        if (empty($data['nombre']) || empty($data['apellidos']) || empty($data['correo'])) {
            $_SESSION['error'] = 'Por favor complete todos los campos requeridos';
            $this->redirect('/alumnoadmin/create');
        }

        if ($data['id_programa'] === 0 || $data['id_grupo'] === 0) {
            $_SESSION['error'] = 'Debe seleccionar programa y grupo';
            $this->redirect('/alumnoadmin/create');
        }

        // Validate email format
        if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Correo electrónico inválido';
            $this->redirect('/alumnoadmin/create');
        }

        try {
            // Create alumno with user and generate charges
            $alumnoId = $this->alumnoModel->createWithUser($data);
            
            $this->bitacora->log(
                $_SESSION['user_id'],
                'alumnos',
                $alumnoId,
                'INSERT',
                "Alumno inscrito: {$data['nombre']} {$data['apellidos']}"
            );

            $_SESSION['success'] = 'Alumno creado exitosamente. Se generaron los cargos iniciales.';
            $this->redirect('/alumnoadmin/show/' . $alumnoId);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear alumno: ' . $e->getMessage();
            $this->redirect('/alumnoadmin/create');
        }
    }

    public function show($id) {

        $alumno = $this->alumnoModel->getWithUser($id);
        if (!$alumno) {
            $_SESSION['error'] = 'Alumno no encontrado';
            $this->redirect('/alumnoadmin/index');
        }

        // Get cargos
        $cargos = $this->cargoModel->getByAlumno($id);

        // Calculate totales
        $totales = [
            'pendiente' => 0,
            'vencido' => 0,
            'pagado' => 0
        ];

        foreach ($cargos as $cargo) {
            if ($cargo['estatus'] === 'PAGADO') {
                $totales['pagado'] += $cargo['monto'];
            } elseif ($cargo['estatus'] === 'VENCIDO' || $cargo['estatus'] === 'PENALIZACION') {
                $totales['vencido'] += $cargo['saldo_pendiente'];
            } else {
                $totales['pendiente'] += $cargo['saldo_pendiente'];
            }
        }

        // Get calificaciones
        $sql = "SELECT c.*, a.titulo as actividad_titulo, a.puntos_max, a.tipo,
                m.nombre as materia_nombre, c.retroalimentacion, c.fecha_entrega
                FROM entregas_tareas et
                INNER JOIN calificaciones c ON et.id_entrega = c.id_entrega  
                INNER JOIN actividades a ON et.id_actividad = a.id_actividad
                INNER JOIN asignaciones asig ON a.id_asignacion = asig.id_asignacion
                INNER JOIN materias m ON asig.id_materia = m.id_materia
                WHERE et.id_alumno = ? AND c.calificacion IS NOT NULL
                ORDER BY c.fecha_registro DESC";
        
        // Try to get grades, if table doesn't exist, set empty array
        try {
            $stmt = $this->alumnoModel->query($sql, [$id]);
            $calificaciones = $stmt->fetchAll();
        } catch (Exception $e) {
            $calificaciones = [];
        }

        // Get progreso académico - entregas por materia
        $sql2 = "SELECT m.nombre as materia_nombre,
                COUNT(DISTINCT et.id_actividad) as total_actividades,
                SUM(CASE WHEN et.estado = 'CALIFICADA' THEN 1 ELSE 0 END) as calificadas,
                AVG(CASE WHEN et.calificacion IS NOT NULL THEN et.calificacion ELSE NULL END) as promedio
                FROM asignaciones asig
                INNER JOIN materias m ON asig.id_materia = m.id_materia
                INNER JOIN actividades a ON asig.id_asignacion = a.id_asignacion
                LEFT JOIN entregas_tareas et ON a.id_actividad = et.id_actividad AND et.id_alumno = ?
                WHERE asig.id_grupo = (SELECT id_grupo FROM alumnos WHERE id_alumno = ?)
                GROUP BY m.id_materia, m.nombre
                ORDER BY m.nombre";
        
        try {
            $stmt2 = $this->alumnoModel->query($sql2, [$id, $id]);
            $progreso = $stmt2->fetchAll();
        } catch (Exception $e) {
            $progreso = [];
        }

        $data = [
            'alumno' => $alumno,
            'cargos' => $cargos,
            'totales' => $totales,
            'calificaciones' => $calificaciones,
            'progreso' => $progreso
        ];

        $this->view('layouts/header', ['title' => 'Detalle del Alumno']);
        $this->view('admin/alumnos/show', $data);
        $this->view('layouts/footer');
    }

    public function edit($id) {

        $alumno = $this->alumnoModel->find($id);
        if (!$alumno) {
            $_SESSION['error'] = 'Alumno no encontrado';
            $this->redirect('/alumnoadmin/index');
        }

        $programas = $this->programaModel->getActive();
        $grupos = $this->grupoModel->getActive();

        $data = [
            'alumno' => $alumno,
            'programas' => $programas,
            'grupos' => $grupos
        ];

        $this->view('layouts/header', ['title' => 'Editar Alumno']);
        $this->view('admin/alumnos/edit', $data);
        $this->view('layouts/footer');
    }

    public function update($id) {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/alumnoadmin/index');
        }

        $alumno = $this->alumnoModel->find($id);
        $oldEstatus = $alumno['estatus'];

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellidos' => trim($_POST['apellidos'] ?? ''),
            'matricula' => trim($_POST['matricula'] ?? ''),
            'correo' => trim($_POST['correo'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'id_programa' => (int)($_POST['id_programa'] ?? 0),
            'id_grupo' => (int)($_POST['id_grupo'] ?? 0),
            'porcentaje_beca' => floatval($_POST['porcentaje_beca'] ?? 0),
            'estatus' => $_POST['estatus'] ?? 'INSCRITO'
        ];

        try {
            $this->alumnoModel->update($id, $data);

            // Handle password change if requested
            if (isset($_POST['change_password']) && !empty($_POST['new_password'])) {
                $newPassword = $_POST['new_password'];
                
                if (strlen($newPassword) >= 6) {
                    // Update password in usuarios table
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $sql = "UPDATE usuarios SET password = ? WHERE id_usuario = ?";
                    $this->alumnoModel->query($sql, [$hashedPassword, $alumno['id_usuario']]);
                    
                    $_SESSION['success'] = 'Alumno y contraseña actualizados exitosamente';
                    
                    $this->bitacora->log(
                        $_SESSION['user_id'],
                        'usuarios',
                        $alumno['id_usuario'],
                        'UPDATE',
                        "Contraseña cambiada para alumno: {$data['nombre']} {$data['apellidos']}"
                    );
                } else {
                    $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
                    $this->redirect('/alumnoadmin/edit/' . $id);
                    return;
                }
            }

            // If status changed to BAJA, cancel future charges
            if ($oldEstatus !== 'BAJA' && $data['estatus'] === 'BAJA') {
                $this->alumnoModel->cancelFutureCharges($id);
                $this->bitacora->log(
                    $_SESSION['user_id'],
                    'alumnos',
                    $id,
                    'UPDATE',
                    "Alumno dado de BAJA - cargos futuros cancelados"
                );
            }

            $this->bitacora->log(
                $_SESSION['user_id'],
                'alumnos',
                $id,
                'UPDATE',
                "Alumno actualizado"
            );

            if (!isset($_SESSION['success'])) {
                $_SESSION['success'] = 'Alumno actualizado exitosamente';
            }
            $this->redirect('/alumnoadmin/show/' . $id);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar alumno: ' . $e->getMessage();
            $this->redirect('/alumnoadmin/edit/' . $id);
        }
    }

    public function delete($id) {

        // Check for cargos
        $sql = "SELECT COUNT(*) as total FROM cargos WHERE id_alumno = ? AND estatus != 'CANCELADO'";
        $stmt = $this->alumnoModel->query($sql, [$id]);
        $result = $stmt->fetch();

        if ($result['total'] > 0) {
            $_SESSION['error'] = 'No se puede eliminar el alumno porque tiene cargos pendientes. Cambie su estatus a BAJA en su lugar.';
            $this->redirect('/alumnoadmin/index');
        }

        try {
            $alumno = $this->alumnoModel->find($id);
            
            // Delete associated user
            if ($alumno['id_usuario']) {
                $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
                $this->alumnoModel->query($sql, [$alumno['id_usuario']]);
            }

            $this->alumnoModel->delete($id);
            
            $this->bitacora->log(
                $_SESSION['user_id'],
                'alumnos',
                $id,
                'DELETE',
                "Alumno eliminado"
            );

            $_SESSION['success'] = 'Alumno eliminado exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar alumno';
        }

        $this->redirect('/alumnoadmin/index');
    }
}
