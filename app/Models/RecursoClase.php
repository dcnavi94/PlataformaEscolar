<?php
// app/Models/RecursoClase.php

require_once '../app/Core/Model.php';

class RecursoClase extends Model {
    protected $table = 'recursos_clase';
    protected $primaryKey = 'id_recurso';

    /**
     * Get resources by asignacion
     */
    public function getByAsignacion($id_asignacion) {
        $sql = "SELECT r.* 
                FROM {$this->table} r
                WHERE r.id_asignacion = ? AND r.visible = TRUE
                ORDER BY r.fecha_publicacion DESC";
        
        return $this->query($sql, [$id_asignacion])->fetchAll();
    }

    /**
     * Get visible resources for student
     */
    public function getVisibleForStudent($id_alumno) {
        $sql = "SELECT r.*, 
                asig.id_materia,
                m.nombre as materia_nombre,
                m.codigo as materia_codigo,
                g.nombre as grupo_nombre,
                p.nombre as profesor_nombre
                FROM {$this->table} r
                INNER JOIN asignaciones asig ON r.id_asignacion = asig.id_asignacion
                INNER JOIN materias m ON asig.id_materia = m.id_materia
                INNER JOIN grupos g ON asig.id_grupo = g.id_grupo
                INNER JOIN profesores p ON asig.id_profesor = p.id_profesor
                INNER JOIN alumnos al ON g.id_grupo = al.id_grupo
                WHERE al.id_alumno = ? AND r.visible = TRUE
                ORDER BY r.fecha_publicacion DESC";
        
        return $this->query($sql, [$id_alumno])->fetchAll();
    }

    /**
     * Get all resources with relations
     */
    public function getAllWithRelations() {
        $sql = "SELECT r.*, 
                asig.id_profesor, asig.id_materia, asig.id_grupo,
                m.nombre as materia_nombre,
                g.nombre as grupo_nombre,
                p.nombre as profesor_nombre
                FROM {$this->table} r
                INNER JOIN asignaciones asig ON r.id_asignacion = asig.id_asignacion
                INNER JOIN materias m ON asig.id_materia = m.id_materia
                INNER JOIN grupos g ON asig.id_grupo = g.id_grupo
                INNER JOIN profesores p ON asig.id_profesor = p.id_profesor
                ORDER BY r.fecha_publicacion DESC";
        
        return $this->query($sql)->fetchAll();
    }

    /**
     * Get resources by topic
     */
    public function getByTema($id_tema) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id_tema = ? AND visible = TRUE
                ORDER BY fecha_publicacion DESC";
        return $this->query($sql, [$id_tema])->fetchAll();
    }

    /**
     * Create new resource
     */
    public function create($data) {
        return $this->insert($data);
    }
}
