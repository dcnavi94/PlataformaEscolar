<?php
// app/Models/Programa.php

require_once '../app/Core/Model.php';

class Programa extends Model {
    protected $table = 'programas';
    protected $primaryKey = 'id_programa';

    /**
     * Get active programs
     */
    public function getActive() {
        return $this->query("SELECT * FROM {$this->table} WHERE estado = 'ACTIVO' ORDER BY nombre")->fetchAll();
    }
}
