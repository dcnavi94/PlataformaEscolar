<?php
// app/Models/PeriodoInscripcion.php

require_once __DIR__ . '/../Core/Model.php';

class PeriodoInscripcion extends Model {
    protected $table = 'periodos_inscripcion';
    protected $primaryKey = 'id_periodo_inscripcion';

    /**
     * Get periodos activos
     */
    public function getActivos() {
        $sql = "SELECT pi.*, p.nombre as programa_nombre
                FROM {$this->table} pi
                INNER JOIN programas p ON pi.id_programa = p.id_programa
                WHERE pi.activo = TRUE
                AND pi.fecha_inicio <= CURDATE()
                AND pi.fecha_fin >= CURDATE()
                ORDER BY pi.created_at DESC";
        
        return $this->query($sql)->fetchAll();
    }

    /**
     * Get by programa
     */
    public function getByPrograma($id_programa) {
        $sql = "SELECT * FROM {$this->table}
                WHERE id_programa = ?
                ORDER BY created_at DESC";
        
        return $this->query($sql, [$id_programa])->fetchAll();
    }

    /**
     * Get with relations
     */
    public function getAllWithRelations() {
        $sql = "SELECT pi.*, p.nombre as programa_nombre,
                (SELECT COUNT(*) FROM solicitudes_inscripcion si 
                 WHERE si.id_periodo_inscripcion = pi.id_periodo_inscripcion) as total_solicitudes
                FROM {$this->table} pi
                INNER JOIN programas p ON pi.id_programa = p.id_programa
                ORDER BY pi.created_at DESC";
        
        return $this->query($sql)->fetchAll();
    }

    /**
     * Verificar cupo disponible
     */
    public function verificarCupo($id_periodo) {
        $periodo = $this->find($id_periodo);
        if (!$periodo) return false;

        $sql = "SELECT COUNT(*) as total FROM solicitudes_inscripcion
                WHERE id_periodo_inscripcion = ?
                AND estado IN ('PENDIENTE', 'EN_REVISION', 'APROBADA', 'MATRICULADO')";
        
        $stmt = $this->query($sql, [$id_periodo]);
        $result = $stmt->fetch();

        return $result['total'] < $periodo['cupo_maximo'] || $periodo['cupo_maximo'] == 0;
    }

    /**
     * Check if periodo is active
     */
    public function estaActivo($id_periodo) {
        $periodo = $this->find($id_periodo);
        if (!$periodo) return false;

        $hoy = date('Y-m-d');
        return $periodo['activo'] 
            && $periodo['fecha_inicio'] <= $hoy 
            && $periodo['fecha_fin'] >= $hoy;
    }
}
