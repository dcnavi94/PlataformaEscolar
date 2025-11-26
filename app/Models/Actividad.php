<?php
// app/Models/Actividad.php

require_once '../app/Core/Model.php';

class Actividad extends Model {
    protected $table = 'actividades';
    protected $primaryKey = 'id_actividad';

    /**
     * Get all activities with relations
     */
    public function getAllWithRelations() {
        $sql = "SELECT a.*, 
                asig.id_profesor, asig.id_materia, asig.id_grupo,
                m.nombre as materia_nombre,
                g.nombre as grupo_nombre,
                p.nombre as profesor_nombre
                FROM {$this->table} a
                INNER JOIN asignaciones asig ON a.id_asignacion = asig.id_asignacion
                INNER JOIN materias m ON asig.id_materia = m.id_materia
                INNER JOIN grupos g ON asig.id_grupo = g.id_grupo
                INNER JOIN profesores p ON asig.id_profesor = p.id_profesor
                ORDER BY a.fecha_publicacion DESC";
        
        return $this->query($sql)->fetchAll();
    }

    /**
     * Get activities by asignacion
     */
    public function getByAsignacion($id_asignacion) {
        $sql = "SELECT a.*, 
                COUNT(DISTINCT et.id_entrega) as total_entregas
                FROM {$this->table} a
                LEFT JOIN entregas_tareas et ON a.id_actividad = et.id_actividad
                WHERE a.id_asignacion = ?
                GROUP BY a.id_actividad
                ORDER BY a.fecha_publicacion DESC";
        
        return $this->query($sql, [$id_asignacion])->fetchAll();
    }

    /**
     * Get active activities for a student
     */
    public function getActiveForStudent($id_alumno) {
        $sql = "SELECT a.*, 
                asig.id_materia,
                m.nombre as materia_nombre,
                m.codigo as materia_codigo,
                g.nombre as grupo_nombre,
                p.nombre as profesor_nombre,
                et.id_entrega,
                et.fecha_entrega,
                et.calificacion,
                et.estado as estado_entrega,
                CASE 
                    WHEN a.fecha_limite < NOW() AND et.id_entrega IS NULL THEN 'VENCIDA'
                    WHEN et.id_entrega IS NOT NULL THEN 'ENTREGADA'
                    ELSE 'PENDIENTE'
                END as status
                FROM {$this->table} a
                INNER JOIN asignaciones asig ON a.id_asignacion = asig.id_asignacion
                INNER JOIN materias m ON asig.id_materia = m.id_materia
                INNER JOIN grupos g ON asig.id_grupo = g.id_grupo
                INNER JOIN profesores p ON asig.id_profesor = p.id_profesor
                INNER JOIN alumnos al ON g.id_grupo = al.id_grupo
                LEFT JOIN entregas_tareas et ON a.id_actividad = et.id_actividad AND et.id_alumno = al.id_alumno
                WHERE al.id_alumno = ? AND a.estado = 'ACTIVA'
                ORDER BY a.fecha_limite ASC, a.fecha_publicacion DESC";
        
        return $this->query($sql, [$id_alumno])->fetchAll();
    }

    /**
     * Get pending activities for student
     */
    public function getPendingForStudent($id_alumno) {
        $sql = "SELECT a.*, 
                asig.id_materia,
                m.nombre as materia_nombre,
                m.codigo as materia_codigo,
                g.nombre as grupo_nombre
                FROM {$this->table} a
                INNER JOIN asignaciones asig ON a.id_asignacion = asig.id_asignacion
                INNER JOIN materias m ON asig.id_materia = m.id_materia
                INNER JOIN grupos g ON asig.id_grupo = g.id_grupo
                INNER JOIN alumnos al ON g.id_grupo = al.id_grupo
                LEFT JOIN entregas_tareas et ON a.id_actividad = et.id_actividad AND et.id_alumno = al.id_alumno
                WHERE al.id_alumno = ? 
                AND a.estado = 'ACTIVA'
                AND a.permite_entrega = TRUE
                AND et.id_entrega IS NULL
                AND (a.fecha_limite IS NULL OR a.fecha_limite > NOW())
                ORDER BY a.fecha_limite ASC";
        
        return $this->query($sql, [$id_alumno])->fetchAll();
    }

    /**
     * Get activity by id with details
     */
    public function findById($id) {
        $sql = "SELECT a.*,
                asig.id_profesor, asig.id_materia, asig.id_grupo,
                m.nombre as materia_nombre,
                g.nombre as grupo_nombre,
                p.nombre as profesor_nombre
                FROM {$this->table} a
                INNER JOIN asignaciones asig ON a.id_asignacion = asig.id_asignacion
                INNER JOIN materias m ON asig.id_materia = m.id_materia
                INNER JOIN grupos g ON asig.id_grupo = g.id_grupo
                INNER JOIN profesores p ON asig.id_profesor = p.id_profesor
                WHERE a.id_actividad = ?";
        
        return $this->query($sql, [$id])->fetch();
    }

    /**
     * Get activities by topic
     */
    public function getByTema($id_tema) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id_tema = ? AND estado = 'ACTIVA'
                ORDER BY fecha_publicacion DESC";
        return $this->query($sql, [$id_tema])->fetchAll();
    }

    /**
     * Create new activity
     */
    public function create($data) {
        return $this->insert($data);
    }
}
