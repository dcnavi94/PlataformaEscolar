<?php

require_once __DIR__ . '/../Core/Model.php';

class EventoCalendario extends Model {
    protected $table = 'eventos_calendario';
    protected $primaryKey = 'id_evento';
    protected $allowedFields = ['titulo', 'descripcion', 'fecha_inicio', 'fecha_fin', 'tipo', 'color', 'created_by'];

    public function getAllEvents() {
        $sql = "SELECT * FROM {$this->table} ORDER BY fecha_inicio ASC";
        return $this->query($sql)->fetchAll();
    }

    public function getEventsByRange($start, $end) {
        $sql = "SELECT * FROM {$this->table} WHERE fecha_inicio >= ? AND fecha_fin <= ? ORDER BY fecha_inicio ASC";
        return $this->query($sql, [$start, $end])->fetchAll();
    }
}
