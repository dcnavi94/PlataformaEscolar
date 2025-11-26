<?php

require_once __DIR__ . '/../Core/Model.php';

class Horario extends Model {
    protected $table = 'horarios';
    protected $primaryKey = 'id_horario';
    protected $allowedFields = ['id_asignacion', 'dia_semana', 'hora_inicio', 'hora_fin', 'aula'];

    public function getByAsignacion($id_asignacion) {
        $sql = "SELECT * FROM {$this->table} WHERE id_asignacion = ?";
        return $this->query($sql, [$id_asignacion])->fetchAll();
    }

    public function getByGrupo($id_grupo) {
        $sql = "SELECT h.*, m.nombre as materia, p.nombre as profesor_nombre, p.apellidos as profesor_apellidos 
                FROM {$this->table} h
                JOIN asignaciones a ON h.id_asignacion = a.id_asignacion
                JOIN materias m ON a.id_materia = m.id_materia
                JOIN profesores p ON a.id_profesor = p.id_profesor
                WHERE a.id_grupo = ?
                ORDER BY FIELD(h.dia_semana, 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO'), h.hora_inicio";
        return $this->query($sql, [$id_grupo])->fetchAll();
    }

    public function getByProfesor($id_profesor) {
        $sql = "SELECT h.*, m.nombre as materia, g.nombre as grupo
                FROM {$this->table} h
                JOIN asignaciones a ON h.id_asignacion = a.id_asignacion
                JOIN materias m ON a.id_materia = m.id_materia
                JOIN grupos g ON a.id_grupo = g.id_grupo
                WHERE a.id_profesor = ?
                ORDER BY FIELD(h.dia_semana, 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO'), h.hora_inicio";
        return $this->query($sql, [$id_profesor])->fetchAll();
    }

    public function deleteByAsignacion($id_asignacion) {
        $sql = "DELETE FROM {$this->table} WHERE id_asignacion = ?";
        return $this->query($sql, [$id_asignacion]);
    }
}
