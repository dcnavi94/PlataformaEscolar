<?php
// app/Models/Egresado.php

require_once '../app/Core/Model.php';

class Egresado extends Model {
    protected $table = 'egresados';
    protected $primaryKey = 'id_egresado';

    /**
     * Registrar egresado
     */
    public function registrar($data) {
        return $this->insert($data);
    }

    /**
     * Get egresados by programa
     */
    public function getByPrograma($id_programa) {
        $sql = "SELECT e.*, 
                a.nombre as alumno_nombre,
                a.apellidos as alumno_apellidos,
                a.matricula,
                p.nombre as programa_nombre
                FROM {$this->table} e
                INNER JOIN alumnos a ON e.id_alumno = a.id_alumno
                INNER JOIN programas p ON e.id_programa = p.id_programa
                WHERE e.id_programa = ?
                ORDER BY e.fecha_egreso DESC";
        
        return $this->query($sql, [$id_programa])->fetchAll();
    }

    /**
     * Get all egresados with relations
     */
    public function getAllWithRelations() {
        $sql = "SELECT e.*, 
                a.nombre as alumno_nombre,
                a.apellidos as alumno_apellidos,
                a.matricula,
                p.nombre as programa_nombre
                FROM {$this->table} e
                INNER JOIN alumnos a ON e.id_alumno = a.id_alumno
                INNER JOIN programas p ON e.id_programa = p.id_programa
                ORDER BY e.fecha_egreso DESC";
        
        return $this->query($sql)->fetchAll();
    }

    /**
     * Buscar egresados
     */
    public function buscar($criterios) {
        $sql = "SELECT e.*, 
                a.nombre as alumno_nombre,
                a.apellidos as alumno_apellidos,
                p.nombre as programa_nombre
                FROM {$this->table} e
                INNER JOIN alumnos a ON e.id_alumno = a.id_alumno
                INNER JOIN programas p ON e.id_programa = p.id_programa
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($criterios['nombre'])) {
            $sql .= " AND (a.nombre LIKE ? OR a.apellidos LIKE ?)";
            $params[] = "%{$criterios['nombre']}%";
            $params[] = "%{$criterios['nombre']}%";
        }
        
        if (!empty($criterios['generacion'])) {
            $sql .= " AND e.generacion = ?";
            $params[] = $criterios['generacion'];
        }
        
        if (!empty($criterios['id_programa'])) {
            $sql .= " AND e.id_programa = ?";
            $params[] = $criterios['id_programa'];
        }
        
        $sql .= " ORDER BY e.fecha_egreso DESC";
        
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Get estadísticas
     */
    public function getStats() {
        $sql = "SELECT 
                COUNT(*) as total,
                COUNT(DISTINCT id_programa) as programas,
                COUNT(DISTINCT generacion) as generaciones,
                AVG(promedio_general) as promedio_avg
                FROM {$this->table}";
        
        return $this->query($sql)->fetch();
    }

    /**
     * Get egresado by alumno
     */
    public function getByAlumno($id_alumno) {
        $sql = "SELECT e.*, p.nombre as programa_nombre
                FROM {$this->table} e
                INNER JOIN programas p ON e.id_programa = p.id_programa
                WHERE e.id_alumno = ?";
        
        return $this->query($sql, [$id_alumno])->fetch();
    }
}
