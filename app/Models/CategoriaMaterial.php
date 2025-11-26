<?php

class CategoriaMaterial extends Model {
    protected $table = 'categorias_material';
    protected $primaryKey = 'id_categoria';

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM categorias_material WHERE estado = 'ACTIVO'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categorias_material WHERE id_categoria = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO categorias_material (nombre, descripcion) VALUES (:nombre, :descripcion)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion']
        ]);
    }

    // Overriding update because of specific logic or just using parent?
    // Parent update is generic. Let's keep specific if needed or use parent.
    // Keeping specific for now to ensure compatibility with controller data.
    public function update($id, $data) {
        $sql = "UPDATE categorias_material SET nombre = :nombre, descripcion = :descripcion WHERE id_categoria = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $sql = "UPDATE categorias_material SET estado = 'INACTIVO' WHERE id_categoria = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
