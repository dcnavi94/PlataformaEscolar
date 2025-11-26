<?php
// app/Models/Grupo.php

require_once '../app/Core/Model.php';

class Grupo extends Model {
    protected $table = 'grupos';
    protected $primaryKey = 'id_grupo';

    /**
     * Get all grupos with relations
     */
    public function getAllWithRelations() {
        $sql = "SELECT g.*, p.nombre as programa_nombre, per.nombre as periodo_nombre,
                COUNT(a.id_alumno) as num_alumnos
                FROM {$this->table} g
                INNER JOIN programas p ON g.id_programa = p.id_programa
                INNER JOIN periodos per ON g.id_periodo = per.id_periodo
                LEFT JOIN alumnos a ON g.id_grupo = a.id_grupo AND a.estatus = 'INSCRITO'
                GROUP BY g.id_grupo
                ORDER BY per.anio DESC, per.numero_periodo DESC";
        
        return $this->query($sql)->fetchAll();
    }

    /**
     * Get active grupos
     */
    public function getActive() {
        return $this->query("SELECT * FROM {$this->table} WHERE estado = 'ACTIVO' ORDER BY nombre")->fetchAll();
    }

    public function getAll() {
        return $this->query("SELECT * FROM {$this->table} ORDER BY nombre")->fetchAll();
    }

    public function findById($id) {
        return $this->find($id);
    }
}
