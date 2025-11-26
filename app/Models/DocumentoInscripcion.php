<?php
// app/Models/DocumentoInscripcion.php

require_once __DIR__ . '/../Core/Model.php';

class DocumentoInscripcion extends Model {
    protected $table = 'documentos_inscripcion';
    protected $primaryKey = 'id_documento';

    /**
     * Get by solicitud
     */
    public function getBySolicitud($id_solicitud) {
        $sql = "SELECT * FROM {$this->table}
                WHERE id_solicitud = ?
                ORDER BY created_at DESC";
        
        return $this->query($sql, [$id_solicitud])->fetchAll();
    }

    /**
     * Upload documento
     */
    public function upload($id_solicitud, $tipo_documento, $file_path, $file_name) {
        return $this->insert([
            'id_solicitud' => $id_solicitud,
            'tipo_documento' => $tipo_documento,
            'nombre_archivo' => $file_name,
            'ruta_archivo' => $file_path,
            'estado' => 'PENDIENTE'
        ]);
    }

    /**
     * Update estado
     */
    public function updateEstado($id, $estado, $comentarios = null) {
        $data = ['estado' => $estado];
        if ($comentarios) {
            $data['comentarios'] = $comentarios;
        }
        return $this->update($id, $data);
    }

    /**
     * Check if all documents approved
     */
    public function todosAprobados($id_solicitud) {
        $sql = "SELECT COUNT(*) as total,
                SUM(CASE WHEN estado = 'APROBADO' THEN 1 ELSE 0 END) as aprobados
                FROM {$this->table}
                WHERE id_solicitud = ?";
        
        $stmt = $this->query($sql, [$id_solicitud]);
        $result = $stmt->fetch();
        
        return $result['total'] > 0 && $result['total'] == $result['aprobados'];
    }
}
