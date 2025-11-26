<?php
// app/Models/UsuarioAdmin.php

require_once '../app/Core/Model.php';

class UsuarioAdmin extends Model {

    /**
     * Get all admin users
     */
    public function all() {
        $sql = "SELECT ua.* 
                FROM usuarios_admin ua
                JOIN usuarios u ON ua.id_usuario = u.id_usuario
                ORDER BY ua.created_at DESC";
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Find admin user by ID
     */
    public function find($id) {
        $sql = "SELECT ua.* 
                FROM usuarios_admin ua
                JOIN usuarios u ON ua.id_usuario = u.id_usuario
                WHERE ua.id_usuario_admin = ?";
        $stmt = $this->query($sql, [$id]);
        return $stmt->fetch();
    }

    /**
     * Find admin user by User ID (from usuarios table)
     */
    public function findByUserId($idUsuario) {
        $sql = "SELECT * FROM usuarios_admin WHERE id_usuario = ?";
        $stmt = $this->query($sql, [$idUsuario]);
        return $stmt->fetch();
    }

    /**
     * Create new admin user
     */
    public function create($data) {
        try {
            $this->db->beginTransaction();

            // Create user account first
            $sqlUser = "INSERT INTO usuarios (nombre, correo, password_hash, rol) VALUES (?, ?, ?, 'ADMIN')";
            $nombreCompleto = $data['nombre'] . ' ' . $data['apellidos'];
            $stmt = $this->query($sqlUser, [$nombreCompleto, $data['email'], password_hash($data['password'], PASSWORD_DEFAULT)]);
            $idUsuario = $this->db->lastInsertId();

            // Create admin profile
            $sqlAdmin = "INSERT INTO usuarios_admin (id_usuario, nombre, apellidos, email, telefono, activo) 
                         VALUES (?, ?, ?, ?, ?, ?)";
            $this->query($sqlAdmin, [
                $idUsuario,
                $data['nombre'],
                $data['apellidos'],
                $data['email'],
                $data['telefono'] ?? null,
                $data['activo'] ?? true
            ]);

            $idAdmin = $this->db->lastInsertId();

            $this->db->commit();
            return $idAdmin;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Update admin user
     */
    public function update($id, $data) {
        $sql = "UPDATE usuarios_admin 
                SET nombre = ?, apellidos = ?, email = ?, telefono = ?, activo = ?
                WHERE id_usuario_admin = ?";
        
        $this->query($sql, [
            $data['nombre'],
            $data['apellidos'],
            $data['email'],
            $data['telefono'] ?? null,
            $data['activo'] ?? true,
            $id
        ]);

        // Update user email if changed
        $sqlUser = "UPDATE usuarios u 
                    JOIN usuarios_admin ua ON u.id_usuario = ua.id_usuario
                    SET u.correo = ?
                    WHERE ua.id_usuario_admin = ?";
        $this->query($sqlUser, [$data['email'], $id]);

        return true;
    }

    /**
     * Delete admin user
     */
    public function delete($id) {
        // Get user id first
        $admin = $this->find($id);
        if ($admin) {
            $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
            $this->query($sql, [$admin['id_usuario']]);
            return true;
        }
        return false;
    }

    /**
     * Toggle active status
     */
    public function toggleStatus($id) {
        $sql = "UPDATE usuarios_admin SET activo = NOT activo WHERE id_usuario_admin = ?";
        $this->query($sql, [$id]);
        return true;
    }

    /**
     * Get permissions for admin user
     */
    public function getPermissions($id) {
        $sql = "SELECT * FROM permisos_usuario WHERE id_usuario_admin = ?";
        $stmt = $this->query($sql, [$id]);
        return $stmt->fetchAll();
    }
}
