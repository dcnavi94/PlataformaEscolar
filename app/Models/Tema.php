<?php
// app/Models/Tema.php

require_once '../app/Core/Model.php';

class Tema extends Model {
    protected $table = 'temas';
    protected $primaryKey = 'id_tema';

    /**
     * Get topics by module
     */
    public function getByModulo($id_modulo) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id_modulo = ? AND estado = 'ACTIVO'
                ORDER BY orden ASC, created_at ASC";
        return $this->query($sql, [$id_modulo])->fetchAll();
    }

    /**
     * Get topic with content (Activities and Resources)
     */
    public function getWithContent($id_tema) {
        $tema = $this->find($id_tema);
        if (!$tema) return null;

        // Get Activities
        $sqlAct = "SELECT *, 'ACTIVIDAD' as item_type FROM actividades 
                   WHERE id_tema = ? AND estado = 'ACTIVA'
                   ORDER BY fecha_publicacion DESC";
        $tema['actividades'] = $this->query($sqlAct, [$id_tema])->fetchAll();

        // Get Resources
        $sqlRec = "SELECT *, 'RECURSO' as item_type FROM recursos_clase 
                   WHERE id_tema = ? AND visible = 1
                   ORDER BY fecha_publicacion DESC";
        $tema['recursos'] = $this->query($sqlRec, [$id_tema])->fetchAll();
        
        return $tema;
    }
}
