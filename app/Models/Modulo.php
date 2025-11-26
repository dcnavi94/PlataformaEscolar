<?php
// app/Models/Modulo.php

require_once '../app/Core/Model.php';

class Modulo extends Model {
    protected $table = 'modulos';
    protected $primaryKey = 'id_modulo';

    /**
     * Get modules by asignacion
     */
    public function getByAsignacion($id_asignacion) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id_asignacion = ? AND estado != 'INACTIVO'
                ORDER BY orden ASC, created_at ASC";
        return $this->query($sql, [$id_asignacion])->fetchAll();
    }

    /**
     * Get module with topics
     */
    public function getWithTopics($id_modulo) {
        $modulo = $this->find($id_modulo);
        if (!$modulo) return null;

        $sql = "SELECT * FROM temas 
                WHERE id_modulo = ? AND estado = 'ACTIVO'
                ORDER BY orden ASC, created_at ASC";
        $modulo['temas'] = $this->query($sql, [$id_modulo])->fetchAll();
        
        return $modulo;
    }
    
    /**
     * Get full course structure (Modules -> Topics)
     */
    public function getCourseStructure($id_asignacion) {
        $modulos = $this->getByAsignacion($id_asignacion);
        
        foreach ($modulos as &$modulo) {
            $sql = "SELECT * FROM temas 
                    WHERE id_modulo = ? AND estado = 'ACTIVO'
                    ORDER BY orden ASC, created_at ASC";
            $modulo['temas'] = $this->query($sql, [$modulo['id_modulo']])->fetchAll();
        }
        
        return $modulos;
    }
}
