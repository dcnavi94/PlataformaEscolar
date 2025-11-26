<?php
// app/Models/Asignacion.php

require_once '../app/Core/Model.php';

class Asignacion extends Model {
    protected $table = 'asignaciones';
    protected $primaryKey = 'id_asignacion';

    /**
     * Get all asignaciones with relations
     */
    public function getAllWithRelations() {
        $sql = "SELECT 
                    a.*,
                    p.nombre as profesor_nombre,
                    p.apellidos as profesor_apellidos,
                    m.nombre as materia_nombre,
                    m.codigo as materia_codigo,
                    g.nombre as grupo_nombre,
                    prog.nombre as programa_nombre
                FROM {$this->table} a
                INNER JOIN profesores p ON a.id_profesor = p.id_profesor
                INNER JOIN materias m ON a.id_materia = m.id_materia
                INNER JOIN grupos g ON a.id_grupo = g.id_grupo
                INNER JOIN programas prog ON g.id_programa = prog.id_programa
                ORDER BY a.created_at DESC";
        
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Get asignaciones by profesor
     */
    public function getByProfesor($id_profesor) {
        $sql = "SELECT 
                    a.*,
                    m.nombre as materia_nombre,
                    m.codigo as materia_codigo,
                    g.nombre as grupo_nombre
                FROM {$this->table} a
                INNER JOIN materias m ON a.id_materia = m.id_materia
                INNER JOIN grupos g ON a.id_grupo = g.id_grupo
                WHERE a.id_profesor = ?
                ORDER BY a.estado_calificacion, m.nombre";
        
        $stmt = $this->query($sql, [$id_profesor]);
        return $stmt->fetchAll();
    }

    /**
     * Get asignacion with full details
     */
    public function getWithDetails($id_asignacion) {
        $sql = "SELECT 
                    a.*,
                    p.nombre as profesor_nombre,
                    p.apellidos as profesor_apellidos,
                    m.nombre as materia_nombre,
                    m.codigo as materia_codigo,
                    m.creditos,
                    g.nombre as grupo_nombre,
                    g.id_programa,
                    prog.nombre as programa_nombre
                FROM {$this->table} a
                INNER JOIN profesores p ON a.id_profesor = p.id_profesor
                INNER JOIN materias m ON a.id_materia = m.id_materia
                INNER JOIN grupos g ON a.id_grupo = g.id_grupo
                INNER JOIN programas prog ON g.id_programa = prog.id_programa
                WHERE a.id_asignacion = ?";
        
        $stmt = $this->query($sql, [$id_asignacion]);
        return $stmt->fetch();
    }

    /**
     * Get student list for an asignacion
     */
    public function getStudentList($id_asignacion) {
        // First get the grupo from asignacion
        $asignacion = $this->find($id_asignacion);
        if (!$asignacion) {
            return [];
        }

        $sql = "SELECT 
                    al.id_alumno,
                    al.nombre,
                    al.apellidos,
                    c.id_calificacion,
                    c.calificacion,
                    c.observaciones
                FROM alumnos al
                LEFT JOIN calificaciones c ON c.id_alumno = al.id_alumno 
                    AND c.id_asignacion = ?
                WHERE al.id_grupo = ? 
                    AND al.estatus = 'INSCRITO'
                ORDER BY al.apellidos, al.nombre";
        
        $stmt = $this->query($sql, [$id_asignacion, $asignacion['id_grupo']]);
        return $stmt->fetchAll();
    }

    /**
     * Check if assignment is closed
     */
    public function isAssignmentClosed($id_asignacion) {
        $sql = "SELECT estado_calificacion FROM {$this->table} WHERE id_asignacion = ?";
        $stmt = $this->query($sql, [$id_asignacion]);
        $result = $stmt->fetch();
        return $result && $result['estado_calificacion'] === 'CERRADA';
    }

    /**
     * Close assignment (mark as CERRADA)
     */
    public function closeAssignment($id_asignacion) {
        $sql = "UPDATE {$this->table} 
                SET estado_calificacion = 'CERRADA'
                WHERE id_asignacion = ?";
        
        return $this->query($sql, [$id_asignacion]);
    }

    /**
     * Check if assignment already exists
     */
    public function assignmentExists($id_profesor, $id_materia, $id_grupo, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->table} 
                WHERE id_profesor = ? AND id_materia = ? AND id_grupo = ?";
        $params = [$id_profesor, $id_materia, $id_grupo];
        
        if ($excludeId) {
            $sql .= " AND id_asignacion != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    /**
     * Get assignments for a student (based on their group)
     */
    public function getByAlumno($id_alumno) {
        $sql = "SELECT 
                    a.*,
                    m.nombre as materia_nombre,
                    m.codigo as materia_codigo,
                    m.creditos,
                    p.nombre as profesor_nombre,
                    p.apellidos as profesor_apellidos,
                    g.nombre as grupo_nombre
                FROM {$this->table} a
                INNER JOIN materias m ON a.id_materia = m.id_materia
                INNER JOIN profesores p ON a.id_profesor = p.id_profesor
                INNER JOIN grupos g ON a.id_grupo = g.id_grupo
                INNER JOIN alumnos al ON g.id_grupo = al.id_grupo
                WHERE al.id_alumno = ?
                ORDER BY m.nombre";
        
        $stmt = $this->query($sql, [$id_alumno]);
        return $stmt->fetchAll();
    }

    /**
     * Get assignments by group
     */
    public function getByGrupo($id_grupo) {
        $sql = "SELECT 
                    a.*,
                    m.nombre as materia_nombre,
                    p.nombre as profesor_nombre,
                    p.apellidos as profesor_apellidos
                FROM {$this->table} a
                INNER JOIN materias m ON a.id_materia = m.id_materia
                INNER JOIN profesores p ON a.id_profesor = p.id_profesor
                WHERE a.id_grupo = ?
                ORDER BY m.nombre";
        
        $stmt = $this->query($sql, [$id_grupo]);
        return $stmt->fetchAll();
    }
}
