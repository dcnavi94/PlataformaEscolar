<?php
// app/Models/Usuario.php

require_once '../app/Core/Model.php';

class Usuario extends Model {
    protected $table = 'usuarios';

    /**
     * Find user by email
     */
    public function findByEmail($email) {
        return $this->findBy('correo', $email);
    }

    /**
     * Verify password
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Create new user
     */
    public function create($data) {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
        unset($data['password']);
        
        return $this->insert($data);
    }

    /**
     * Update password
     */
    public function updatePassword($userId, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        return $this->update($userId, ['password_hash' => $hash]);
    }

    /**
     * Check if user is moroso (delinquent)
     */
    public function isMoroso($userId) {
        $threshold = (int)(getenv('MOROSO_THRESHOLD') ?: 2);
        
        $sql = "SELECT COUNT(*) as vencidos 
                FROM cargos c
                INNER JOIN alumnos a ON c.id_alumno = a.id_alumno
                WHERE a.id_usuario = ? 
                AND c.estatus IN ('VENCIDO', 'PENALIZACION')
                AND TIMESTAMPDIFF(MONTH, c.fecha_limite, NOW()) >= ?";
        
        $stmt = $this->query($sql, [$userId, $threshold]);
        $result = $stmt->fetch();
        
        return $result['vencidos'] > 0;
    }
}
