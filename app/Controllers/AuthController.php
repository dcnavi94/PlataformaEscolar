<?php
// app/Controllers/AuthController.php

require_once '../app/Core/Controller.php';

class AuthController extends Controller {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = $this->model('Usuario');
    }

    /**
     * Index method
     */
    public function index() {
        $this->redirect('/auth/login');
    }

    /**
     * Show login form
     */
    public function login() {
        // If already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard();
        }

        $this->view('auth/login', [
            'title' => 'Iniciar Sesión'
        ]);
    }

    /**
     * Process login
     */
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/auth/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Por favor complete todos los campos';
            $this->redirect('/auth/login');
        }

        // Find user
        $user = $this->usuarioModel->findByEmail($email);

        if (!$user || !$this->usuarioModel->verifyPassword($password, $user['password_hash'])) {
            $_SESSION['error'] = 'Credenciales incorrectas';
            $this->redirect('/auth/login');
        }

        // Check if user is active
        if ($user['estado'] !== 'ACTIVO') {
            $_SESSION['error'] = 'Su cuenta está inactiva. Contacte al administrador';
            $this->redirect('/auth/login');
        }

        // Set session
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['correo'] = $user['correo'];
        $_SESSION['rol'] = $user['rol'];

        // Get full name based on role
        if ($user['rol'] === 'ALUMNO') {
            $alumnoModel = $this->model('Alumno');
            $alumno = $alumnoModel->getByUserId($user['id_usuario']);
            if ($alumno) {
                $_SESSION['nombre'] = $alumno['nombre'] . ' ' . $alumno['apellidos'];
            } else {
                $_SESSION['nombre'] = $user['nombre'];
            }
        } elseif ($user['rol'] === 'PROFESOR') {
            $profesorModel = $this->model('Profesor');
            $profesor = $profesorModel->getByUserId($user['id_usuario']);
            if ($profesor) {
                $_SESSION['nombre'] = $profesor['nombre'] . ' ' . $profesor['apellidos'];
            } else {
                $_SESSION['nombre'] = $user['nombre'];
            }
        } else {
            $_SESSION['nombre'] = $user['nombre'];
        }

        // Check if user is moroso (only for ALUMNO)
        if ($user['rol'] === 'ALUMNO') {
            $_SESSION['is_moroso'] = $this->usuarioModel->isMoroso($user['id_usuario']);
        }

        // Log the login
        require_once '../app/Models/Bitacora.php';
        $bitacora = new Bitacora();
        $bitacora->log($user['id_usuario'], 'usuarios', $user['id_usuario'], 'LOGIN', 'Usuario inició sesión');

        // Redirect to dashboard
        $this->redirectToDashboard();
    }

    /**
     * Logout
     */
    public function logout() {
        session_destroy();
        $this->redirect('/auth/login');
    }

    /**
     * Redirect to appropriate dashboard based on role
     */
    private function redirectToDashboard() {
        if ($this->hasRole('ADMIN')) {
            $this->redirect('/admin/dashboard');
        } else {
            $this->redirect('/alumno/dashboard');
        }
    }
}
