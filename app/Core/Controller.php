<?php
// app/Core/Controller.php

class Controller {
    
    /**
     * Load a model
     */
    protected function model($model) {
        require_once '../app/Models/' . $model . '.php';
        return new $model();
    }

    /**
     * Load a view with data
     */
    protected function view($view, $data = []) {
        extract($data);
        
        if (file_exists('../app/Views/' . $view . '.php')) {
            require_once '../app/Views/' . $view . '.php';
        } else {
            die("View not found: $view");
        }
    }

    /**
     * Redirect to another page
     */
    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }

    /**
     * Check if user is logged in
     */
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Check if user has specific role
     */
    protected function hasRole($role) {
        return isset($_SESSION['rol']) && $_SESSION['rol'] === $role;
    }

    /**
     * Require authentication
     */
    protected function requireAuth() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/auth/login');
        }
    }

    /**
     * Require specific role
     */
    protected function requireRole($role) {
        // First check if logged in
        if (!$this->isLoggedIn()) {
            $this->redirect('/auth/login');
        }
        
        // Then check role
        if (!$this->hasRole($role)) {
            // User is logged in but doesn't have the right role
            // Redirect to their appropriate dashboard
            if ($this->hasRole('ADMIN')) {
                $this->redirect('/admin/dashboard');
            } elseif ($this->hasRole('ALUMNO')) {
                $this->redirect('/alumno/dashboard');
            } elseif ($this->hasRole('PROFESOR')) {
                $this->redirect('/profesor/dashboard');
            } else {
                $this->redirect('/auth/login');
            }
        }
    }

    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

