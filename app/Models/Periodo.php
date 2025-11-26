<?php
// app/Models/Periodo.php

require_once '../app/Core/Model.php';

class Periodo extends Model {
    protected $table = 'periodos';
    protected $primaryKey = 'id_periodo';

    /**
     * Get current periodo
     */
    public function getCurrent() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE CURDATE() BETWEEN fecha_inicio AND fecha_fin 
                LIMIT 1";
        
        return $this->query($sql)->fetch();
    }

    /**
     * Get periodos by year
     */
    public function getByYear($year) {
        return $this->query("SELECT * FROM {$this->table} WHERE anio = ? ORDER BY numero_periodo", [$year])->fetchAll();
    }
}
