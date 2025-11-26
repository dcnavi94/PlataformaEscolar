<?php

require_once __DIR__ . '/../Core/Model.php';

class Asistencia extends Model {
    protected $table = 'asistencias';
    protected $primaryKey = 'id_asistencia';

    public function getByAsignacionAndDate($id_asignacion, $fecha) {
        $sql = "SELECT * FROM {$this->table} WHERE id_asignacion = ? AND fecha = ?";
        return $this->query($sql, [$id_asignacion, $fecha])->fetchAll();
    }

    public function saveAttendance($data) {
        // $data is an array of [id_asignacion, id_alumno, fecha, estado, observaciones]
        // We use ON DUPLICATE KEY UPDATE to handle both insert and update
        
        $sql = "INSERT INTO {$this->table} (id_asignacion, id_alumno, fecha, estado, observaciones) 
                VALUES (?, ?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE estado = VALUES(estado), observaciones = VALUES(observaciones)";
        
        $stmt = $this->db->prepare($sql);
        
        try {
            $this->db->beginTransaction();
            foreach ($data as $row) {
                $stmt->execute([
                    $row['id_asignacion'],
                    $row['id_alumno'],
                    $row['fecha'],
                    $row['estado'],
                    $row['observaciones']
                ]);
            }
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getReportByGroup($id_grupo, $month, $year) {
        $startDate = "$year-$month-01";
        $endDate = date("Y-m-t", strtotime($startDate));

        $sql = "SELECT 
                    a.id_alumno,
                    a.nombre,
                    a.apellidos,
                    asist.fecha,
                    asist.estado,
                    m.nombre as materia
                FROM alumnos a
                JOIN asignaciones asig ON asig.id_grupo = a.id_grupo
                JOIN materias m ON asig.id_materia = m.id_materia
                LEFT JOIN asistencias asist ON asist.id_alumno = a.id_alumno 
                    AND asist.id_asignacion = asig.id_asignacion
                    AND asist.fecha BETWEEN ? AND ?
                WHERE a.id_grupo = ? AND a.estatus = 'INSCRITO'
                ORDER BY a.apellidos, a.nombre, asist.fecha";

        return $this->query($sql, [$startDate, $endDate, $id_grupo])->fetchAll();
    }
}
