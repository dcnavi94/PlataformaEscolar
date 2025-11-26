<?php
// app/Models/EntregaTarea.php

require_once '../app/Core/Model.php';

class EntregaTarea extends Model {
    protected $table = 'entregas_tareas';
    protected $primaryKey = 'id_entrega';

    /**
     * Get submissions by activity
     */
    public function getByActividad($id_actividad) {
        $sql = "SELECT et.*, 
                al.nombre, al.apellidos, al.matricula
                FROM {$this->table} et
                INNER JOIN alumnos al ON et.id_alumno = al.id_alumno
                WHERE et.id_actividad = ?
                ORDER BY et.fecha_entrega DESC";
        
        return $this->query($sql, [$id_actividad])->fetchAll();
    }

    /**
     * Get submissions by student
     */
    public function getByAlumno($id_alumno) {
        $sql = "SELECT et.*, 
                a.titulo as actividad_titulo,
                a.puntos_max,
                asig.id_materia,
                m.nombre as materia_nombre,
                m.codigo as materia_codigo
                FROM {$this->table} et
                INNER JOIN actividades a ON et.id_actividad = a.id_actividad
                INNER JOIN asignaciones asig ON a.id_asignacion = asig.id_asignacion
                INNER JOIN materias m ON asig.id_materia = m.id_materia
                WHERE et.id_alumno = ?
                ORDER BY et.fecha_entrega DESC";
        
        return $this->query($sql, [$id_alumno])->fetchAll();
    }

    /**
     * Submit assignment
     */
    public function submitAssignment($data) {
        // Check if submission already exists
        $existing = $this->findBy('id_actividad', $data['id_actividad']);
        if ($existing && $existing['id_alumno'] == $data['id_alumno']) {
            // Update existing submission
            return $this->update($existing['id_entrega'], $data);
        }
        
        // Create new submission
        return $this->insert($data);
    }

    /**
     * Grade submission
     */
    public function gradeSubmission($id_entrega, $calificacion, $retroalimentacion = null) {
        $data = [
            'calificacion' => $calificacion,
            'retroalimentacion' => $retroalimentacion,
            'estado' => 'CALIFICADA'
        ];
        
        return $this->update($id_entrega, $data);
    }

    /**
     * Check if student has submitted
     */
    public function hasSubmitted($id_actividad, $id_alumno) {
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->table} 
                WHERE id_actividad = ? AND id_alumno = ?";
        
        $result = $this->query($sql, [$id_actividad, $id_alumno])->fetch();
        return $result['count'] > 0;
    }

    /**
     * Get submission by activity and student
     */
    public function getByActividadAlumno($id_actividad, $id_alumno) {
        $sql = "SELECT et.* 
                FROM {$this->table} et
                WHERE et.id_actividad = ? AND et.id_alumno = ?";
        
        return $this->query($sql, [$id_actividad, $id_alumno])->fetch();
    }
}
