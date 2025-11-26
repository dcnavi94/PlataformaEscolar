<?php
// app/Models/Materia.php

require_once '../app/Core/Model.php';

class Materia extends Model {
    protected $table = 'materias';
    protected $primaryKey = 'id_materia';

    /**
     * Get all materias with program information
     */
    public function getAllWithPrograms() {
        $sql = "SELECT m.*, p.nombre as programa_nombre
                FROM {$this->table} m
                LEFT JOIN programas p ON m.id_programa = p.id_programa
                WHERE m.estado = 'ACTIVO'
                ORDER BY p.nombre, m.nombre";
        
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Get materias by program
     */
    public function getByProgram($id_programa) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id_programa = ? AND estado = 'ACTIVO'
                ORDER BY nombre";
        
        $stmt = $this->query($sql, [$id_programa]);
        return $stmt->fetchAll();
    }

    /**
     * Get materia with program info
     */
    public function getWithProgram($id) {
        $sql = "SELECT m.*, p.nombre as programa_nombre
                FROM {$this->table} m
                LEFT JOIN programas p ON m.id_programa = p.id_programa
                WHERE m.id_materia = ?";
        
        $stmt = $this->query($sql, [$id]);
        return $stmt->fetch();
    }

    /**
     * Get all active materias for dropdown
     */
    public function getAllActive() {
        $sql = "SELECT m.id_materia, m.nombre, m.codigo, p.nombre as programa_nombre
                FROM {$this->table} m
                LEFT JOIN programas p ON m.id_programa = p.id_programa
                WHERE m.estado = 'ACTIVO'
                ORDER BY m.nombre";
        
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Check if codigo is unique
     */
    public function isCodigoUnique($codigo, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE codigo = ?";
        $params = [$codigo];
        
        if ($excludeId) {
            $sql .= " AND id_materia != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result['count'] == 0;
    }
}
