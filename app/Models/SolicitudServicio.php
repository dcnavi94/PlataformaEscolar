<?php
// app/Models/SolicitudServicio.php

require_once '../app/Core/Model.php';

class SolicitudServicio extends Model {
    protected $table = 'solicitudes_servicios';
    protected $primaryKey = 'id_solicitud';

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (id_alumno, tipo_servicio, comentarios, estatus) VALUES (?, ?, ?, 'PENDIENTE')";
        $this->query($sql, [
            $data['id_alumno'],
            $data['tipo_servicio'],
            $data['comentarios'] ?? ''
        ]);
        return $this->db->lastInsertId();
    }

    public function getByAlumno($id_alumno) {
        $sql = "SELECT * FROM {$this->table} WHERE id_alumno = ? ORDER BY fecha_solicitud DESC";
        $stmt = $this->query($sql, [$id_alumno]);
        return $stmt->fetchAll();
    }

    public function getAllWithDetails() {
        $sql = "SELECT s.*, a.nombre, a.apellidos, a.correo, p.nombre as programa_nombre, g.nombre as grupo_nombre
                FROM {$this->table} s
                INNER JOIN alumnos a ON s.id_alumno = a.id_alumno
                LEFT JOIN programas p ON a.id_programa = p.id_programa
                LEFT JOIN grupos g ON a.id_grupo = g.id_grupo
                ORDER BY 
                    CASE s.estatus
                        WHEN 'PENDIENTE' THEN 1
                        WHEN 'EN_PROCESO' THEN 2
                        ELSE 3
                    END,
                    s.fecha_solicitud DESC";
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $estatus) {
        $sql = "UPDATE {$this->table} SET estatus = ? WHERE id_solicitud = ?";
        return $this->query($sql, [$estatus, $id]);
    }
}
