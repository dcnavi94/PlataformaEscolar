<?php
// app/Models/Calificacion.php

require_once '../app/Core/Model.php';

class Calificacion extends Model {
    protected $table = 'calificaciones';
    protected $primaryKey = 'id_calificacion';

    /**
     * Save grades for an assignment (batch insert)
     */
    public function saveGrades($id_asignacion, $grades) {
        try {
            $this->db->beginTransaction();

            // Delete existing grades for this assignment (in case of re-grading by admin)
            $this->query("DELETE FROM {$this->table} WHERE id_asignacion = ?", [$id_asignacion]);

            // Insert new grades
            foreach ($grades as $grade) {
                if (isset($grade['calificacion']) && $grade['calificacion'] !== '') {
                    $data = [
                        'id_asignacion' => $id_asignacion,
                        'id_alumno' => $grade['id_alumno'],
                        'calificacion' => $grade['calificacion'],
                        'observaciones' => $grade['observaciones'] ?? null
                    ];
                    $this->insert($data);
                }
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Get all grades for a student (Kardex)
     */
    public function getByAlumno($id_alumno) {
        $sql = "SELECT 
                    c.*,
                    m.nombre as materia_nombre,
                    m.codigo as materia_codigo,
                    m.creditos,
                    g.nombre as grupo_nombre,
                    p.nombre as profesor_nombre,
                    p.apellidos as profesor_apellidos,
                    per.nombre as periodo_nombre
                FROM {$this->table} c
                INNER JOIN asignaciones a ON c.id_asignacion = a.id_asignacion
                INNER JOIN materias m ON a.id_materia = m.id_materia
                INNER JOIN grupos g ON a.id_grupo = g.id_grupo
                INNER JOIN profesores p ON a.id_profesor = p.id_profesor
                LEFT JOIN periodos per ON g.id_periodo = per.id_periodo
                WHERE c.id_alumno = ?
                ORDER BY per.fecha_inicio DESC, m.nombre";
        
        $stmt = $this->query($sql, [$id_alumno]);
        return $stmt->fetchAll();
    }

    /**
     * Get grades for an assignment
     */
    public function getByAsignacion($id_asignacion) {
        $sql = "SELECT 
                    c.*,
                    al.nombre as alumno_nombre,
                    al.apellidos as alumno_apellidos,
                    al.matricula
                FROM {$this->table} c
                INNER JOIN alumnos al ON c.id_alumno = al.id_alumno
                WHERE c.id_asignacion = ?
                ORDER BY al.apellidos, al.nombre";
        
        $stmt = $this->query($sql, [$id_asignacion]);
        return $stmt->fetchAll();
    }

    /**
     * Calculate average grade for a student
     */
    public function getPromedioByAlumno($id_alumno) {
        $sql = "SELECT AVG(calificacion) as promedio
                FROM {$this->table}
                WHERE id_alumno = ?";
        
        $stmt = $this->query($sql, [$id_alumno]);
        $result = $stmt->fetch();
        return $result['promedio'] ? round($result['promedio'], 2) : 0;
    }

    /**
     * Check if student has grade for assignment
     */
    public function hasGrade($id_asignacion, $id_alumno) {
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->table} 
                WHERE id_asignacion = ? AND id_alumno = ?";
        
        $stmt = $this->query($sql, [$id_asignacion, $id_alumno]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
}
