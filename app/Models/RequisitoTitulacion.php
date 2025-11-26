<?php
// app/Models/RequisitoTitulacion.php

require_once '../app/Core/Model.php';

class RequisitoTitulacion extends Model {
    protected $table = 'requisitos_titulacion';
    protected $primaryKey = 'id_requisito';

    /**
     * Get requisitos by programa
     */
    public function getByPrograma($id_programa, $activos_only = true) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id_programa = ?";
        
        if ($activos_only) {
            $sql .= " AND activo = TRUE";
        }
        
        $sql .= " ORDER BY orden ASC, nombre_requisito ASC";
        
        return $this->query($sql, [$id_programa])->fetchAll();
    }

    /**
     * Get all requisitos with programa name
     */
    public function getAllWithPrograma() {
        $sql = "SELECT r.*, p.nombre as programa_nombre
                FROM {$this->table} r
                INNER JOIN programas p ON r.id_programa = p.id_programa
                ORDER BY p.nombre, r.orden";
        
        return $this->query($sql)->fetchAll();
    }

    /**
     * Reorder requisitos
     */
    public function updateOrden($id, $nuevo_orden) {
        return $this->update($id, ['orden' => $nuevo_orden]);
    }

    /**
     * Toggle active status
     */
    public function toggleActivo($id) {
        $req = $this->find($id);
        if ($req) {
            return $this->update($id, ['activo' => !$req['activo']]);
        }
        return false;
    }
}
