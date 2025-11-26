<?php
// app/Models/ProcesoTitulacion.php

require_once '../app/Core/Model.php';

class ProcesoTitulacion extends Model {
    protected $table = 'procesos_titulacion';
    protected $primaryKey = 'id_proceso';

    /**
     * Get proceso by alumno
     */
    public function getByAlumno($id_alumno) {
        $sql = "SELECT pt.*, 
                p.nombre as programa_nombre,
                a.nombre as alumno_nombre,
                a.apellidos as alumno_apellidos
                FROM {$this->table} pt
                INNER JOIN programas p ON pt.id_programa = p.id_programa
                INNER JOIN alumnos a ON pt.id_alumno = a.id_alumno
                WHERE pt.id_alumno = ?
                ORDER BY pt.created_at DESC";
        
        return $this->query($sql, [$id_alumno])->fetch();
    }

    /**
     * Get all procesos with relations
     */
    public function getAllWithRelations() {
        $sql = "SELECT pt.*, 
                p.nombre as programa_nombre,
                a.nombre as alumno_nombre,
                a.apellidos as alumno_apellidos,
                a.matricula
                FROM {$this->table} pt
                INNER JOIN programas p ON pt.id_programa = p.id_programa
                INNER JOIN alumnos a ON pt.id_alumno = a.id_alumno
                ORDER BY pt.created_at DESC";
        
        return $this->query($sql)->fetchAll();
    }

    /**
     * Get procesos by programa
     */
    public function getByPrograma($id_programa) {
        $sql = "SELECT pt.*, 
                a.nombre as alumno_nombre,
                a.apellidos as alumno_apellidos,
                a.matricula
                FROM {$this->table} pt
                INNER JOIN alumnos a ON pt.id_alumno = a.id_alumno
                WHERE pt.id_programa = ?
                ORDER BY pt.created_at DESC";
        
        return $this->query($sql, [$id_programa])->fetchAll();
    }

    /**
     * Get procesos by estado
     */
    public function getByEstado($estado) {
        $sql = "SELECT pt.*, 
                p.nombre as programa_nombre,
                a.nombre as alumno_nombre,
                a.apellidos as alumno_apellidos
                FROM {$this->table} pt
                INNER JOIN programas p ON pt.id_programa = p.id_programa
                INNER JOIN alumnos a ON pt.id_alumno = a.id_alumno
                WHERE pt.estado = ?
                ORDER BY pt.created_at DESC";
        
        return $this->query($sql, [$estado])->fetchAll();
    }

    /**
     * Update estado
     */
    public function updateEstado($id, $estado, $observaciones = null) {
        $data = ['estado' => $estado];
        if ($observaciones) {
            $data['observaciones'] = $observaciones;
        }
        return $this->update($id, $data);
    }

    /**
     * Generate folio number
     */
    public function generateFolio() {
        $year = date('Y');
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE YEAR(created_at) = ?";
        $stmt = $this->query($sql, [$year]);
        $result = $stmt->fetch();
        $numero = str_pad($result['total'] + 1, 4, '0', STR_PAD_LEFT);
        return "TIT-{$year}-{$numero}";
    }

    /**
     * Get statistics
     */
    public function getStats() {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN estado = 'SOLICITADO' THEN 1 ELSE 0 END) as solicitados,
                SUM(CASE WHEN estado = 'EN_REVISION' THEN 1 ELSE 0 END) as en_revision,
                SUM(CASE WHEN estado = 'APROBADO' THEN 1 ELSE 0 END) as aprobados,
                SUM(CASE WHEN estado = 'TITULADO' THEN 1 ELSE 0 END) as titulados
                FROM {$this->table}";
        
        return $this->query($sql)->fetch();
    }

    /**
     * Check if alumno already has proceso
     */
    public function hasProcesoActivo($id_alumno) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE id_alumno = ? 
                AND estado NOT IN ('RECHAZADO', 'TITULADO')";
        $stmt = $this->query($sql, [$id_alumno]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
}
