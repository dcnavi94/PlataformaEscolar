<?php
// app/Models/ConfiguracionFinanciera.php

require_once '../app/Core/Model.php';

class ConfiguracionFinanciera extends Model {
    protected $table = 'configuracion_financiera';
    protected $primaryKey = 'id_configuracion';

    public function get() {
        $stmt = $this->query("SELECT * FROM {$this->table} LIMIT 1");
        return $stmt->fetch();
    }

    public function updateConfig($data) {
        // Assuming id 1 always exists or we update the first row
        return $this->update(1, $data);
    }
}
