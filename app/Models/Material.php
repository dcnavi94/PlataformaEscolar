<?php

class Material extends Model {
    protected $table = 'inventario';
    protected $primaryKey = 'id_material';

    public function getAll($filters = []) {
        $sql = "SELECT m.*, c.nombre as categoria 
                FROM inventario m 
                JOIN categorias_material c ON m.id_categoria = c.id_categoria 
                WHERE m.estado != 'BAJA'";
        
        $params = [];
        if (isset($filters['search']) && !empty($filters['search'])) {
            $sql .= " AND (m.nombre LIKE :search OR m.codigo LIKE :search OR m.modelo LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        if (isset($filters['categoria']) && !empty($filters['categoria'])) {
            $sql .= " AND m.id_categoria = :categoria";
            $params[':categoria'] = $filters['categoria'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT m.*, c.nombre as categoria 
                FROM inventario m 
                JOIN categorias_material c ON m.id_categoria = c.id_categoria 
                WHERE m.id_material = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO inventario (id_categoria, codigo, nombre, descripcion, marca, modelo, stock_total, stock_disponible, ubicacion) 
                VALUES (:id_categoria, :codigo, :nombre, :descripcion, :marca, :modelo, :stock_total, :stock_disponible, :ubicacion)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_categoria' => $data['id_categoria'],
            ':codigo' => $data['codigo'],
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':marca' => $data['marca'],
            ':modelo' => $data['modelo'],
            ':stock_total' => $data['stock_total'], // Changed from 'stock' to 'stock_total' to match array key
            ':stock_disponible' => $data['stock_disponible'],
            ':ubicacion' => $data['ubicacion']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE inventario SET 
                id_categoria = :id_categoria, 
                codigo = :codigo, 
                nombre = :nombre, 
                descripcion = :descripcion, 
                marca = :marca, 
                modelo = :modelo, 
                ubicacion = :ubicacion 
                WHERE id_material = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_categoria' => $data['id_categoria'],
            ':codigo' => $data['codigo'],
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':marca' => $data['marca'],
            ':modelo' => $data['modelo'],
            ':ubicacion' => $data['ubicacion'],
            ':id' => $id
        ]);
    }

    public function updateStock($id, $quantity) {
        $sql = "UPDATE inventario SET stock_total = stock_total + :qty, stock_disponible = stock_disponible + :qty WHERE id_material = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':qty' => $quantity, ':id' => $id]);
    }

    public function decreaseAvailableStock($id, $qty = 1) {
        $sql = "UPDATE inventario SET stock_disponible = stock_disponible - :qty WHERE id_material = :id AND stock_disponible >= :qty";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':qty' => $qty, ':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function increaseAvailableStock($id, $qty = 1) {
        $sql = "UPDATE inventario SET stock_disponible = stock_disponible + :qty WHERE id_material = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':qty' => $qty, ':id' => $id]);
    }

    public function delete($id) {
        $sql = "UPDATE inventario SET estado = 'BAJA' WHERE id_material = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
