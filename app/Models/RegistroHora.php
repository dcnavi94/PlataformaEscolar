<?php

class RegistroHora extends Model {
    protected $table = 'registro_horas';
    protected $primaryKey = 'id_registro';

    public function getByProfesor($id_profesor, $startDate = null, $endDate = null) {
        $sql = "SELECT * FROM registro_horas WHERE id_profesor = ?";
        $params = [$id_profesor];

        if ($startDate) {
            $sql .= " AND fecha >= ?";
            $params[] = $startDate;
        }
        if ($endDate) {
            $sql .= " AND fecha <= ?";
            $params[] = $endDate;
        }

        $sql .= " ORDER BY fecha DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPending() {
        $sql = "SELECT r.*, p.nombre, p.apellidos 
                FROM registro_horas r
                JOIN profesores p ON r.id_profesor = p.id_profesor
                WHERE r.estado = 'PENDIENTE'
                ORDER BY r.fecha ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO registro_horas (id_profesor, fecha, horas, tipo_actividad, descripcion, estado) 
                VALUES (:id_profesor, :fecha, :horas, :tipo_actividad, :descripcion, :estado)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_profesor' => $data['id_profesor'],
            ':fecha' => $data['fecha'],
            ':horas' => $data['horas'],
            ':tipo_actividad' => $data['tipo_actividad'],
            ':descripcion' => $data['descripcion'] ?? null,
            ':estado' => $data['estado'] ?? 'PENDIENTE'
        ]);
    }

    public function updateStatus($id, $estado) {
        $sql = "UPDATE registro_horas SET estado = ? WHERE id_registro = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$estado, $id]);
    }
}
