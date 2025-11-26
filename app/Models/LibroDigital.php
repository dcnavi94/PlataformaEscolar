<?php

require_once __DIR__ . '/../Core/Model.php';

class LibroDigital extends Model {
    protected $table = 'libros_digitales';
    protected $primaryKey = 'id_libro';

    public function getAllWithCategory() {
        $sql = "SELECT l.*, c.nombre as categoria_nombre 
                FROM {$this->table} l
                LEFT JOIN categorias_libros c ON l.id_categoria = c.id_categoria
                ORDER BY l.created_at DESC";
        return $this->query($sql)->fetchAll();
    }

    public function getByCategory($id_categoria) {
        $sql = "SELECT * FROM {$this->table} WHERE id_categoria = ? ORDER BY titulo";
        return $this->query($sql, [$id_categoria])->fetchAll();
    }
}
