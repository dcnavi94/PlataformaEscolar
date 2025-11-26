<?php
// app/Models/SolicitudInscripcion.php

require_once __DIR__ . '/../Core/Model.php';

class SolicitudInscripcion extends Model {
    protected $table = 'solicitudes_inscripcion';
    protected $primaryKey = 'id_solicitud';

    /**
     * Generate unique folio
     */
    public function generateFolio() {
        $year = date('Y');
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE YEAR(created_at) = ?";
        $stmt = $this->query($sql, [$year]);
        $result = $stmt->fetch();
        $numero = str_pad($result['total'] + 1, 5, '0', STR_PAD_LEFT);
        return "INS-{$year}-{$numero}";
    }

    /**
     * Get by folio
     */
    public function getByFolio($folio) {
        $sql = "SELECT si.*, pi.nombre as periodo_nombre, p.nombre as programa_nombre
                FROM {$this->table} si
                INNER JOIN periodos_inscripcion pi ON si.id_periodo_inscripcion = pi.id_periodo_inscripcion
                INNER JOIN programas p ON pi.id_programa = p.id_programa
                WHERE si.folio = ?";
        
        return $this->query($sql, [$folio])->fetch();
    }

    /**
     * Verificar duplicado
     */
    public function verificarDuplicado($curp, $correo, $excluir_id = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}
                WHERE (curp = ? OR correo = ?)
                AND estado NOT IN ('RECHAZADA')";
        
        $params = [$curp, $correo];
        
        if ($excluir_id) {
            $sql .= " AND id_solicitud != ?";
            $params[] = $excluir_id;
        }
        
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        
        return $result['count'] > 0;
    }

    /**
     * Get by estado
     */
    public function getByEstado($estado) {
        $sql = "SELECT si.*, pi.nombre as periodo_nombre, p.nombre as programa_nombre
                FROM {$this->table} si
                INNER JOIN periodos_inscripcion pi ON si.id_periodo_inscripcion = pi.id_periodo_inscripcion
                INNER JOIN programas p ON pi.id_programa = p.id_programa
                WHERE si.estado = ?
                ORDER BY si.created_at DESC";
        
        return $this->query($sql, [$estado])->fetchAll();
    }

    /**
     * Get all with relations
     */
    public function getAllWithRelations() {
        $sql = "SELECT si.*, pi.nombre as periodo_nombre, p.nombre as programa_nombre
                FROM {$this->table} si
                INNER JOIN periodos_inscripcion pi ON si.id_periodo_inscripcion = pi.id_periodo_inscripcion
                INNER JOIN programas p ON pi.id_programa = p.id_programa
                ORDER BY si.created_at DESC";
        
        return $this->query($sql)->fetchAll();
    }

    /**
     * Update estado
     */
    public function updateEstado($id, $estado, $comentarios = null, $user_id = null) {
        $data = [
            'estado' => $estado,
            'fecha_revision' => date('Y-m-d H:i:s')
        ];
        
        if ($comentarios) {
            $data['comentarios_admin'] = $comentarios;
        }
        
        if ($user_id) {
            $data['revisado_por'] = $user_id;
        }
        
        return $this->update($id, $data);
    }

    /**
     * Get estadísticas
     */
    public function getStats() {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN estado = 'PENDIENTE' THEN 1 ELSE 0 END) as pendientes,
                SUM(CASE WHEN estado = 'EN_REVISION' THEN 1 ELSE 0 END) as en_revision,
                SUM(CASE WHEN estado = 'APROBADA' THEN 1 ELSE 0 END) as aprobadas,
                SUM(CASE WHEN estado = 'MATRICULADO' THEN 1 ELSE 0 END) as matriculados,
                SUM(CASE WHEN estado = 'RECHAZADA' THEN 1 ELSE 0 END) as rechazadas
                FROM {$this->table}";
        
        return $this->query($sql)->fetch();
    }
}
