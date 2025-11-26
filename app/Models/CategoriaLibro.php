<?php

require_once __DIR__ . '/../Core/Model.php';

class CategoriaLibro extends Model {
    protected $table = 'categorias_libros';
    protected $primaryKey = 'id_categoria';

    public function getAll() {
        return $this->query("SELECT * FROM {$this->table} ORDER BY nombre")->fetchAll();
    }
}
