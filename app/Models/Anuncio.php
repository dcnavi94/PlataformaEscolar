<?php
// app/Models/Anuncio.php

require_once '../app/Core/Model.php';

class Anuncio extends Model {
    protected $table = 'anuncios';
    protected $primaryKey = 'id_anuncio';

    public function getByAsignacion($id_asignacion) {
        $sql = "SELECT a.*, p.nombre as profesor_nombre, p.apellidos as profesor_apellidos 
                FROM {$this->table} a
                INNER JOIN profesores p ON a.id_profesor = p.id_profesor
                WHERE a.id_asignacion = ?
                ORDER BY a.fecha_publicacion DESC";
        $stmt = $this->query($sql, [$id_asignacion]);
        return $stmt->fetchAll();
    }
}
