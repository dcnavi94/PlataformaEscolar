<?php
// app/Controllers/ProfesorController.php

require_once '../app/Core/Controller.php';
require_once '../app/Models/Profesor.php';
require_once '../app/Models/Usuario.php';
require_once '../app/Models/Asignacion.php';
require_once '../app/Models/Calificacion.php';

class ProfesorController extends Controller {
    private $profesorModel;
    private $usuarioModel;
    private $asignacionModel;
    private $calificacionModel;

    public function __construct() {
        $this->profesorModel = new Profesor();
        $this->usuarioModel = new Usuario();
        $this->asignacionModel = new Asignacion();
        $this->calificacionModel = new Calificacion();
    }

    // ==================== ADMIN METHODS ====================

    /**
     * List all profesores (Admin)
     */
    public function index() {
        $this->requireRole('ADMIN');

        $profesores = $this->profesorModel->getAllWithRelations();
        
        $this->view('admin/profesores/index', [
            'title' => 'Gestión de Profesores',
            'profesores' => $profesores
        ]);
    }

    /**
     * Show create form (Admin)
     */
    public function create() {
        $this->requireRole('ADMIN');

        // Get users without profesor profile
        $usuarios = $this->usuarioModel->all();
        
        $this->view('admin/profesores/create', [
            'title' => 'Nuevo Profesor',
            'usuarios' => $usuarios
        ]);
    }

    /**
     * Store new profesor (Admin)
     */
    public function store() {
        $this->requireRole('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profesor');
            return;
        }

        // Validate
        $errors = [];
        
        if (empty($_POST['nombre'])) {
            $errors[] = 'El nombre es requerido';
        }
        
        if (empty($_POST['apellidos'])) {
            $errors[] = 'Los apellidos son requeridos';
        }
        
        if (empty($_POST['email'])) {
            $errors[] = 'El email es requerido';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            $this->redirect('/profesor/create');
            return;
        }

        try {
            $data = [
                'nombre' => $_POST['nombre'],
                'apellidos' => $_POST['apellidos'],
                'email' => $_POST['email'],
                'telefono' => $_POST['telefono'] ?? null,
                'especialidad' => $_POST['especialidad'] ?? null,
                'id_usuario' => $_POST['id_usuario'] ?? null,
                'password' => $_POST['password'] ?? null,
                'estado' => $_POST['estado'] ?? 'ACTIVO'
            ];

            $this->profesorModel->createWithUser($data);
            $_SESSION['success'] = 'Profesor creado exitosamente';
            $this->redirect('/profesor');

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear profesor: ' . $e->getMessage();
            $this->redirect('/profesor/create');
        }
    }

    /**
     * Show edit form (Admin)
     */
    public function edit($id) {
        $this->requireRole('ADMIN');

        $profesor = $this->profesorModel->find($id);
        if (!$profesor) {
            $_SESSION['error'] = 'Profesor no encontrado';
            $this->redirect('/profesor');
            return;
        }
        
        $this->view('admin/profesores/edit', [
            'title' => 'Editar Profesor',
            'profesor' => $profesor
        ]);
    }

    /**
     * Update profesor (Admin)
     */
    public function update($id) {
        $this->requireRole('ADMIN');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profesor');
            return;
        }

        try {
            // WHITELIST: Only these fields are allowed in profesores table
            $allowedFields = ['nombre', 'apellidos', 'email', 'telefono', 'especialidad', 'estado'];
            $hrFields = ['tipo_contrato', 'tarifa_hora', 'rfc', 'curp', 'nss', 'banco', 'clabe'];
            
            $data = [];
            $hrData = [];
            
            // Build data array using whitelist
            foreach ($allowedFields as $field) {
                if (isset($_POST[$field])) {
                    $data[$field] = $_POST[$field];
                }
            }

            foreach ($hrFields as $field) {
                if (isset($_POST[$field])) {
                    $hrData[$field] = $_POST[$field];
                }
            }

            // Update profesor basic info
            $this->profesorModel->update($id, $data);
            
            // Update HR info
            if (!empty($hrData)) {
                $this->profesorModel->updateHRData($id, $hrData);
            }

            // Handle password change if requested
            if (isset($_POST['change_password']) && !empty($_POST['new_password'])) {
                $newPassword = $_POST['new_password'];
                
                if (strlen($newPassword) >= 6) {
                    // Get profesor to find user_id
                    $profesor = $this->profesorModel->find($id);
                    
                    if ($profesor && !empty($profesor['id_usuario'])) {
                        // Update password in usuarios table (NOT profesores)
                        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                        $sql = "UPDATE usuarios SET password_hash = ? WHERE id_usuario = ?";
                        $this->profesorModel->query($sql, [$hashedPassword, $profesor['id_usuario']]);
                        
                        $_SESSION['success'] = 'Profesor y contraseña actualizados exitosamente';
                    } else {
                        $_SESSION['warning'] = 'Profesor actualizado pero no se pudo cambiar la contraseña (usuario no vinculado)';
                    }
                } else {
                    $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
                    $this->redirect('/profesor/edit/' . $id);
                    return;
                }
            }

            if (!isset($_SESSION['success']) && !isset($_SESSION['warning'])) {
                $_SESSION['success'] = 'Profesor actualizado exitosamente';
            }
            
            $this->redirect('/profesor');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar profesor: ' . $e->getMessage();
            $this->redirect('/profesor/edit/' . $id);
        }
    }

    /**
     * Delete profesor (Admin)
     */
    public function delete($id) {
        $this->requireRole('ADMIN');

        try {
            // Soft delete - mark as INACTIVO
            $this->profesorModel->update($id, ['estado' => 'INACTIVO']);
            $_SESSION['success'] = 'Profesor eliminado exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar profesor: ' . $e->getMessage();
        }

        $this->redirect('/profesor');
    }

    // ==================== TEACHER METHODS ====================

    /**
     * Teacher dashboard
     */
    public function dashboard() {
        $this->requireRole('PROFESOR');

        // Get profesor by user ID
        $profesor = $this->profesorModel->getByUserId($_SESSION['user_id']);
        
        if (!$profesor) {
            $_SESSION['error'] = 'Perfil de profesor no encontrado';
            $this->redirect('/auth/login');
            return;
        }

        // Get assigned groups
        $asignaciones = $this->profesorModel->getAssignedGroups($profesor['id_profesor']);
        
        $this->view('profesor/dashboard', [
            'title' => 'Panel de Profesor',
            'profesor' => $profesor,
            'asignaciones' => $asignaciones
        ]);
    }

    /**
     * Show grading form
     */
    public function calificar($id_asignacion) {
        $this->requireRole('PROFESOR');

        // Get asignacion with details
        $asignacion = $this->asignacionModel->getWithDetails($id_asignacion);
        
        if (!$asignacion) {
            $_SESSION['error'] = 'Asignación no encontrada';
            $this->redirect('/profesor/dashboard');
            return;
        }

        // Verify this asignacion belongs to logged-in profesor
        $profesor = $this->profesorModel->getByUserId($_SESSION['user_id']);
        if ($asignacion['id_profesor'] != $profesor['id_profesor']) {
            $_SESSION['error'] = 'No tiene permiso para calificar esta asignación';
            $this->redirect('/profesor/dashboard');
            return;
        }

        // Get student list
        $estudiantes = $this->asignacionModel->getStudentList($id_asignacion);

        // Check if already closed
        $isClosed = $this->asignacionModel->isAssignmentClosed($id_asignacion);
        
        $this->view('profesor/calificar', [
            'title' => 'Calificar Estudiantes',
            'asignacion' => $asignacion,
            'estudiantes' => $estudiantes,
            'isClosed' => $isClosed
        ]);
    }

    /**
     * Save grades
     */
    public function guardarCalificaciones() {
        $this->requireRole('PROFESOR');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profesor/dashboard');
            return;
        }

        $id_asignacion = $_POST['id_asignacion'] ?? null;
        
        if (!$id_asignacion) {
            $_SESSION['error'] = 'Asignación no especificada';
            $this->redirect('/profesor/dashboard');
            return;
        }

        // Verify asignacion is not closed
        if ($this->asignacionModel->isAssignmentClosed($id_asignacion)) {
            $_SESSION['error'] = 'Esta asignación ya está cerrada y no se puede modificar';
            $this->redirect('/profesor/dashboard');
            return;
        }

        // Verify ownership
        $asignacion = $this->asignacionModel->getWithDetails($id_asignacion);
        $profesor = $this->profesorModel->getByUserId($_SESSION['user_id']);
        
        if ($asignacion['id_profesor'] != $profesor['id_profesor']) {
            $_SESSION['error'] = 'No tiene permiso para calificar esta asignación';
            $this->redirect('/profesor/dashboard');
            return;
        }

        try {
            // Prepare grades array
            $grades = [];
            $calificaciones = $_POST['calificacion'] ?? [];
            $observaciones = $_POST['observaciones'] ?? [];

            foreach ($calificaciones as $id_alumno => $calificacion) {
                if ($calificacion !== '' && $calificacion !== null) {
                    $grades[] = [
                        'id_alumno' => $id_alumno,
                        'calificacion' => $calificacion,
                        'observaciones' => $observaciones[$id_alumno] ?? null
                    ];
                }
            }

            if (empty($grades)) {
                $_SESSION['error'] = 'Debe ingresar al menos una calificación';
                $this->redirect('/profesor/calificar/' . $id_asignacion);
                return;
            }

            // Save grades
            $this->calificacionModel->saveGrades($id_asignacion, $grades);

            // Close assignment
            $this->asignacionModel->closeAssignment($id_asignacion);

            $_SESSION['success'] = 'Calificaciones guardadas exitosamente. La asignación ha sido cerrada.';
            $this->redirect('/profesor/dashboard');

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al guardar calificaciones: ' . $e->getMessage();
            $this->redirect('/profesor/calificar/' . $id_asignacion);
        }
    }
}
