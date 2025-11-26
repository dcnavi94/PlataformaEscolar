<?php

class Prestamo extends Model {
    protected $table = 'prestamos';
    protected $primaryKey = 'id_prestamo';

    public function getAll($filters = []) {
        $sql = "SELECT p.*, m.nombre as material, u.nombre as usuario_nombre, u.rol 
                FROM prestamos p
                JOIN inventario m ON p.id_material = m.id_material
                JOIN usuarios u ON p.id_usuario = u.id_usuario
                WHERE 1=1";
        
        $params = [];
        if (isset($filters['estado']) && !empty($filters['estado'])) {
            $sql .= " AND p.estado = :estado";
            $params[':estado'] = $filters['estado'];
        }

        if (isset($filters['search']) && !empty($filters['search'])) {
            $sql .= " AND (u.nombre LIKE :search OR m.nombre LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY p.fecha_prestamo DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUserId($userId) {
        $sql = "SELECT p.*, m.nombre as material, m.codigo, m.modelo 
                FROM prestamos p
                JOIN inventario m ON p.id_material = m.id_material
                WHERE p.id_usuario = ?
                ORDER BY p.fecha_prestamo DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO prestamos (id_usuario, id_material, cantidad, fecha_devolucion_esperada, observaciones_prestamo, id_usuario_admin) 
                VALUES (:id_usuario, :id_material, :cantidad, :fecha_devolucion_esperada, :observaciones, :id_admin)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_usuario' => $data['id_usuario'],
            ':id_material' => $data['id_material'],
            ':cantidad' => $data['cantidad'] ?? 1,
            ':fecha_devolucion_esperada' => $data['fecha_devolucion_esperada'],
            ':observaciones' => $data['observaciones'] ?? null,
            ':id_admin' => $data['id_admin']
        ]);
    }

    public function returnLoan($id, $data) {
        $sql = "UPDATE prestamos SET 
                fecha_devolucion_real = NOW(), 
                estado = :estado, 
                observaciones_devolucion = :observaciones 
                WHERE id_prestamo = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':estado' => $data['estado'] ?? 'DEVUELTO',
            ':observaciones' => $data['observaciones'] ?? null,
            ':id' => $id
        ]);
    }

    public function getById($id) {
        $sql = "SELECT p.*, m.nombre as material, m.id_material, p.cantidad 
                FROM prestamos p
                JOIN inventario m ON p.id_material = m.id_material
                WHERE p.id_prestamo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
