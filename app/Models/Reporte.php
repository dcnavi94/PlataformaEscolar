<?php
// app/Models/Reporte.php

require_once '../app/Core/Model.php';

class Reporte extends Model {
    
    public function getAlumnosPendientes($filtros = []) {
        $sql = "SELECT 
                    a.id_alumno,
                    CONCAT(a.nombre, ' ', a.apellidos) as alumno,
                    g.nombre as grupo,
                    p.nombre as programa,
                    COUNT(c.id_cargo) as cargos_pendientes,
                    SUM(c.saldo_pendiente) as total_deuda,
                    MIN(c.fecha_limite) as deuda_mas_antigua
                FROM alumnos a
                INNER JOIN grupos g ON a.id_grupo = g.id_grupo
                INNER JOIN programas p ON a.id_programa = p.id_programa
                INNER JOIN cargos c ON a.id_alumno = c.id_alumno
                WHERE c.estatus IN ('PENDIENTE', 'VENCIDO', 'PARCIAL', 'PENALIZACION')
                AND a.estatus = 'INSCRITO'";

        $params = [];

        if (!empty($filtros['grupo_id'])) {
            $sql .= " AND a.id_grupo = ?";
            $params[] = $filtros['grupo_id'];
        }

        if (!empty($filtros['periodo_id'])) {
            $sql .= " AND c.id_periodo = ?";
            $params[] = $filtros['periodo_id'];
        }

        $sql .= " GROUP BY a.id_alumno, a.nombre, a.apellidos, g.nombre, p.nombre
                  ORDER BY total_deuda DESC";

        return $this->query($sql, $params)->fetchAll();
    }

    public function getIngresosPorPeriodo($fechaInicio, $fechaFin) {
        $sql = "SELECT 
                    DATE(p.fecha_pago) as fecha,
                    cp.nombre as concepto,
                    pm.metodo_pago,
                    COUNT(*) as transacciones,
                    SUM(p.monto) as total
                FROM pagos p
                INNER JOIN cargos c ON p.id_cargo = c.id_cargo
                INNER JOIN conceptos_pago cp ON c.id_concepto = cp.id_concepto
                LEFT JOIN (
                    SELECT DISTINCT metodo_pago FROM pagos
                ) pm ON p.metodo_pago = pm.metodo_pago
                WHERE DATE(p.fecha_pago) BETWEEN ? AND ?
                AND p.estado = 'COMPLETADO'
                GROUP BY DATE(p.fecha_pago), cp.nombre, p.metodo_pago
                ORDER BY p.fecha_pago DESC";

        return $this->query($sql, [$fechaInicio, $fechaFin])->fetchAll();
    }

    public function getTotalesIngresos($fechaInicio, $fechaFin) {
        $sql = "SELECT 
                    IFNULL(SUM(monto), 0) as total_general,
                    COUNT(*) as total_transacciones
                FROM pagos
                WHERE DATE(fecha_pago) BETWEEN ? AND ?
                AND estado = 'COMPLETADO'";

        return $this->query($sql, [$fechaInicio, $fechaFin])->fetch();
    }
}
