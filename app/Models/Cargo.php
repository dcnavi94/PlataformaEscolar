<?php
// app/Models/Cargo.php

require_once '../app/Core/Model.php';

class Cargo extends Model {
    protected $table = 'cargos';
    protected $primaryKey = 'id_cargo';

    /**
     * Count cargos by status
     */
    public function countByStatus($status) {
        $stmt = $this->query("SELECT COUNT(*) as total FROM {$this->table} WHERE estatus = ?", [$status]);
        return $stmt->fetch()['total'];
    }

    /**
     * Get cargos by alumno
     */
    public function getByAlumno($alumnoId) {
        $sql = "SELECT c.*, cp.nombre as concepto_nombre, p.nombre as periodo_nombre
                FROM {$this->table} c
                INNER JOIN conceptos_pago cp ON c.id_concepto = cp.id_concepto
                INNER JOIN periodos p ON c.id_periodo = p.id_periodo
                WHERE c.id_alumno = ?
                ORDER BY c.anio ASC, c.mes ASC, 
                         CASE WHEN cp.tipo = 'INSCRIPCION' THEN 0 ELSE 1 END ASC";
        
        $stmt = $this->query($sql, [$alumnoId]);
        return $stmt->fetchAll();
    }

    /**
     * Get pending cargos with overdue date
     */
    public function getPendingOverdue() {
        $sql = "SELECT c.*, a.nombre, a.apellidos, a.correo
                FROM {$this->table} c
                INNER JOIN alumnos a ON c.id_alumno = a.id_alumno
                WHERE c.estatus IN ('PENDIENTE', 'PARCIAL')
                AND c.fecha_limite < CURDATE()
                AND a.estatus = 'INSCRITO'";
        
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Apply partial payment
     */
    public function applyPartialPayment($cargoId, $monto) {
        $cargo = $this->find($cargoId);
        $nuevoSaldo = $cargo['saldo_pendiente'] - $monto;
        
        $estatus = ($nuevoSaldo <= 0) ? 'PAGADO' : 'PARCIAL';
        
        return $this->update($cargoId, [
            'saldo_pendiente' => max(0, $nuevoSaldo),
            'estatus' => $estatus,
            'fecha_pago' => $estatus === 'PAGADO' ? date('Y-m-d H:i:s') : null
        ]);
    }
}
