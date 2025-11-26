<?php
// app/Controllers/UsuarioAdminController.php

require_once '../app/Core/Controller.php';

class UsuarioAdminController extends Controller {

    private $usuarioAdminModel;
    private $permisoModel;

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('ADMIN');
        
        $this->usuarioAdminModel = $this->model('UsuarioAdmin');
        $this->permisoModel = $this->model('PermisoUsuario');
    }

    /**
     * List all admin users
     */
    public function index() {

        $usuarios = $this->usuarioAdminModel->all();

        // Get permissions count for each user
        foreach ($usuarios as &$usuario) {
            $permisos = $this->permisoModel->getByUser($usuario['id_usuario_admin']);
            $usuario['modulos_con_acceso'] = count($permisos);
        }

        $data = [
            'title' => 'Gestión de Usuarios',
            'usuarios' => $usuarios
        ];

        $this->view('layouts/header', $data);
        $this->view('admin/usuarios/index', $data);
        $this->view('layouts/footer');
    }

    /**
     * Show create form
     */
    public function create() {

        $data = [
            'title' => 'Crear Usuario Admin',
            'modulos' => $this->permisoModel->getModulos()
        ];

        $this->view('layouts/header', $data);
        $this->view('admin/usuarios/create', $data);
        $this->view('layouts/footer');
    }

    /**
     * Store new user
     */
    public function store() {

        try {
            $data = [
                'nombre' => $_POST['nombre'],
                'apellidos' => $_POST['apellidos'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'telefono' => $_POST['telefono'] ?? null,
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];

            // Create user
            $id = $this->usuarioAdminModel->create($data);

            // Set permissions
            $permisos = [];
            foreach ($this->permisoModel->getModulos() as $key => $nombre) {
                if (isset($_POST['permiso_leer_' . $key]) || isset($_POST['permiso_escribir_' . $key])) {
                    $permisos[$key] = [
                        'leer' => isset($_POST['permiso_leer_' . $key]),
                        'escribir' => isset($_POST['permiso_escribir_' . $key])
                    ];
                }
            }

            if (!empty($permisos)) {
                $this->permisoModel->setPermissions($id, $permisos);
            }

            $_SESSION['success'] = 'Usuario creado exitosamente';
            $this->redirect('/usuarioadmin/index');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear usuario: ' . $e->getMessage();
            $this->redirect('/usuarioadmin/create');
        }
    }

    /**
     * Show edit form
     */
    public function edit($id) {

        $usuario = $this->usuarioAdminModel->find($id);
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            $this->redirect('/usuarioadmin/index');
        }

        $permisos = $this->permisoModel->getPermissionsArray($id);

        $data = [
            'title' => 'Editar Usuario',
            'usuario' => $usuario,
            'permisos' => $permisos,
            'modulos' => $this->permisoModel->getModulos()
        ];

        $this->view('layouts/header', $data);
        $this->view('admin/usuarios/edit', $data);
        $this->view('layouts/footer');
    }

    /**
     * Update user
     */
    public function update($id) {

        try {
            $data = [
                'nombre' => $_POST['nombre'],
                'apellidos' => $_POST['apellidos'],
                'email' => $_POST['email'],
                'telefono' => $_POST['telefono'] ?? null,
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];

            $this->usuarioAdminModel->update($id, $data);

            // Update permissions
            $permisos = [];
            foreach ($this->permisoModel->getModulos() as $key => $nombre) {
                if (isset($_POST['permiso_leer_' . $key]) || isset($_POST['permiso_escribir_' . $key])) {
                    $permisos[$key] = [
                        'leer' => isset($_POST['permiso_leer_' . $key]),
                        'escribir' => isset($_POST['permiso_escribir_' . $key])
                    ];
                }
            }

            $this->permisoModel->setPermissions($id, $permisos);

            $_SESSION['success'] = 'Usuario actualizado exitosamente';
            $this->redirect('/usuarioadmin/index');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar usuario: ' . $e->getMessage();
            $this->redirect('/usuarioadmin/edit/' . $id);
        }
    }

    /**
     * Toggle active status
     */
    public function toggleStatus($id) {

        try {
            $this->usuarioAdminModel->toggleStatus($id);
            $_SESSION['success'] = 'Estado actualizado';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }
        $this->redirect('/usuarioadmin/index');
    }

    /**
     * Delete user
     */
    public function delete($id) {

        try {
            $this->usuarioAdminModel->delete($id);
            $_SESSION['success'] = 'Usuario eliminado exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar usuario: ' . $e->getMessage();
        }
        $this->redirect('/usuarioadmin/index');
    }
}
