<?php

class Nomina extends Model {
    protected $table = 'nominas';
    protected $primaryKey = 'id_nomina';

    public function getAll($filters = []) {
        $sql = "SELECT n.*, p.nombre, p.apellidos 
                FROM nominas n
                JOIN profesores p ON n.id_profesor = p.id_profesor
                WHERE 1=1";
        
        $params = [];
        if (isset($filters['id_profesor']) && !empty($filters['id_profesor'])) {
            $sql .= " AND n.id_profesor = :id_profesor";
            $params[':id_profesor'] = $filters['id_profesor'];
        }

        $sql .= " ORDER BY n.periodo_fin DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO nominas (id_profesor, periodo_inicio, periodo_fin, total_horas, monto_bruto, deducciones, monto_neto, estado, observaciones) 
                VALUES (:id_profesor, :periodo_inicio, :periodo_fin, :total_horas, :monto_bruto, :deducciones, :monto_neto, :estado, :observaciones)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_profesor' => $data['id_profesor'],
            ':periodo_inicio' => $data['periodo_inicio'],
            ':periodo_fin' => $data['periodo_fin'],
            ':total_horas' => $data['total_horas'],
            ':monto_bruto' => $data['monto_bruto'],
            ':deducciones' => $data['deducciones'] ?? 0,
            ':monto_neto' => $data['monto_neto'],
            ':estado' => $data['estado'] ?? 'PENDIENTE',
            ':observaciones' => $data['observaciones'] ?? null
        ]);
    }

    public function markAsPaid($id) {
        $sql = "UPDATE nominas SET estado = 'PAGADO', fecha_pago = NOW() WHERE id_nomina = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
