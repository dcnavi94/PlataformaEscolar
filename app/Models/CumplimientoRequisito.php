<?php
// app/Models/CumplimientoRequisito.php

require_once '../app/Core/Model.php';

class CumplimientoRequisito extends Model {
    protected $table = 'cumplimiento_requisitos';
    protected $primaryKey = 'id_cumplimiento';

    /**
     * Get cumplimiento by proceso
     */
    public function getByProceso($id_proceso) {
        $sql = "SELECT cr.*, rt.nombre_requisito, rt.descripcion, rt.es_obligatorio, rt.tipo_documento
                FROM {$this->table} cr
                INNER JOIN requisitos_titulacion rt ON cr.id_requisito = rt.id_requisito
                WHERE cr.id_proceso = ?
                ORDER BY rt.orden";
        
        return $this->query($sql, [$id_proceso])->fetchAll();
    }

    /**
     * Initialize requisitos for proceso
     */
    public function inicializarRequisitos($id_proceso, $id_programa) {
        require_once '../app/Models/RequisitoTitulacion.php';
        $requisitoModel = new RequisitoTitulacion();
        $requisitos = $requisitoModel->getByPrograma($id_programa);

        foreach ($requisitos as $req) {
            $this->insert([
                'id_proceso' => $id_proceso,
                'id_requisito' => $req['id_requisito'],
                'estado' => 'PENDIENTE'
            ]);
        }
    }

    /**
     * Cargar documento
     */
    public function cargarDocumento($id, $file_path) {
        return $this->update($id, [
            'documento_url' => $file_path,
            'fecha_carga' => date('Y-m-d H:i:s'),
            'estado' => 'CARGADO'
        ]);
    }

    /**
     * Aprobar requisito
     */
    public function aprobar($id, $user_id, $comentarios = null) {
        return $this->update($id, [
            'estado' => 'APROBADO',
            'revisado_por' => $user_id,
            'fecha_revision' => date('Y-m-d H:i:s'),
            'comentarios' => $comentarios
        ]);
    }

    /**
     * Rechazar requisito
     */
    public function rechazar($id, $user_id, $comentarios) {
        return $this->update($id, [
            'estado' => 'RECHAZADO',
            'revisado_por' => $user_id,
            'fecha_revision' => date('Y-m-d H:i:s'),
            'comentarios' => $comentarios
        ]);
    }

    /**
     * Check if all requisitos are approved
     */
    public function todosAprobados($id_proceso) {
        $sql = "SELECT COUNT(*) as total,
                SUM(CASE WHEN estado = 'APROBADO' THEN 1 ELSE 0 END) as aprobados,
                SUM(CASE WHEN rt.es_obligatorio = TRUE THEN 1 ELSE 0 END) as obligatorios,
                SUM(CASE WHEN rt.es_obligatorio = TRUE AND cr.estado = 'APROBADO' THEN 1 ELSE 0 END) as obligatorios_aprobados
                FROM {$this->table} cr
                INNER JOIN requisitos_titulacion rt ON cr.id_requisito = rt.id_requisito
                WHERE cr.id_proceso = ?";
        
        $stmt = $this->query($sql, [$id_proceso]);
        $result = $stmt->fetch();
        
        return $result['obligatorios'] == $result['obligatorios_aprobados'];
    }

    /**
     * Get progreso percentage
     */
    public function getProgreso($id_proceso) {
        $sql = "SELECT COUNT(*) as total,
                SUM(CASE WHEN estado = 'APROBADO' THEN 1 ELSE 0 END) as aprobados
                FROM {$this->table}
                WHERE id_proceso = ?";
        
        $stmt = $this->query($sql, [$id_proceso]);
        $result = $stmt->fetch();
        
        if ($result['total'] == 0) return 0;
        return round(($result['aprobados'] / $result['total']) * 100);
    }
}
