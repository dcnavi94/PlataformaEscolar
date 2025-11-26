<?php
// app/Helpers/AnalyticsHelper.php

class AnalyticsHelper {
    
    /**
     * Get alumnos statistics
     */
    public static function getAlumnosStats() {
        require_once __DIR__ . '/../Config/Database.php';
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN estatus = 'ACTIVO' THEN 1 ELSE 0 END) as activos,
                SUM(CASE WHEN estatus = 'INACTIVO' THEN 1 ELSE 0 END) as inactivos,
                SUM(CASE WHEN estatus = 'BAJA' THEN 1 ELSE 0 END) as bajas,
                SUM(CASE WHEN estatus = 'EGRESADO' THEN 1 ELSE 0 END) as egresados
                FROM alumnos";
        
        $stmt = $db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get financial KPIs
     */
    public static function getFinancialKPIs($periodo = 'mes_actual') {
        require_once __DIR__ . '/../Config/Database.php';
        $db = Database::getInstance()->getConnection();
        
        // Determine date range
        $where = "";
        if ($periodo == 'mes_actual') {
            $where = "MONTH(fecha_pago) = MONTH(CURDATE()) AND YEAR(fecha_pago) = YEAR(CURDATE())";
        } elseif ($periodo == 'año_actual') {
            $where = "YEAR(fecha_pago) = YEAR(CURDATE())";
        }
        
        // Ingresos
        $sql = "SELECT 
                COALESCE(SUM(monto_total), 0) as total_ingresos
                FROM pagos 
                WHERE $where";
        $stmt = $db->query($sql);
        $ingresos = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Cuentas por cobrar (saldo pendiente)
        $sql = "SELECT COALESCE(SUM(saldo_pendiente), 0) as cuentas_por_cobrar
                FROM cargos
                WHERE estatus IN ('PENDIENTE', 'PARCIAL', 'VENCIDO')";
        $stmt = $db->query($sql);
        $cobrar = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Morosidad
        $sql = "SELECT COALESCE(SUM(saldo_pendiente), 0) as morosidad
                FROM cargos
                WHERE estatus = 'VENCIDO'";
        $stmt = $db->query($sql);
        $morosidad = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return [
            'ingresos' => $ingresos['total_ingresos'],
            'cuentas_por_cobrar' => $cobrar['cuentas_por_cobrar'],
            'morosidad' => $morosidad['morosidad'],
            'morosidad_porcentaje' => $cobrar['cuentas_por_cobrar'] > 0 
                ? round(($morosidad['morosidad'] / $cobrar['cuentas_por_cobrar']) * 100, 2) 
                : 0
        ];
    }

    /**
     * Get academic KPIs
     */
    public static function getAcademicKPIs() {
        require_once __DIR__ . '/../Config/Database.php';
        $db = Database::getInstance()->getConnection();
        
        // Note: Academic data not available in current schema
        // Return default values for now
        $promedio = 0;
        $asistencia = 0;
        
        // Check if asistencias table exists and has data
        if (self::tableExists('asistencias')) {
            try {
                $sql = "SELECT 
                        (SUM(CASE WHEN estado = 'PRESENTE' THEN 1 ELSE 0 END) / COUNT(*)) * 100 as asistencia_promedio
                        FROM asistencias
                        WHERE MONTH(fecha) = MONTH(CURDATE())";
                $stmt = $db->query($sql);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $asistencia = round($result['asistencia_promedio'] ?? 0, 2);
            } catch (Exception $e) {
                $asistencia = 0;
            }
        }
        
        return [
            'promedio_general' => $promedio,
            'asistencia_promedio' => $asistencia
        ];
    }

    /**
     * Get desercion rate
     */
    public static function getDesercionRate($meses = 12) {
        require_once __DIR__ . '/../Config/Database.php';
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT 
                COUNT(*) as total_bajas
                FROM alumnos
                WHERE estatus = 'BAJA'
                AND fecha_alta >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$meses]);
        $bajas = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $sql = "SELECT COUNT(*) as total FROM alumnos";
        $stmt = $db->query($sql);
        $total = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $rate = $total['total'] > 0 
            ? round(($bajas['total_bajas'] / $total['total']) * 100, 2) 
            : 0;
        
        return [
            'total_bajas' => $bajas['total_bajas'],
            'tasa_desercion' => $rate
        ];
    }

    /**
     * Get ingresos por mes
     */
    public static function getIngresosPorMes($meses = 6) {
        require_once __DIR__ . '/../Config/Database.php';
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT 
                DATE_FORMAT(fecha_pago, '%Y-%m') as mes,
                SUM(monto_total) as total
                FROM pagos
                WHERE fecha_pago >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
                GROUP BY DATE_FORMAT(fecha_pago, '%Y-%m')
                ORDER BY mes ASC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$meses]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get distribucion por programa
     */
    public static function getDistribucionPorPrograma() {
        require_once __DIR__ . '/../Config/Database.php';
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT 
                p.nombre as programa,
                COUNT(a.id_alumno) as total
                FROM programas p
                LEFT JOIN alumnos a ON p.id_programa = a.id_programa
                WHERE a.estatus = 'ACTIVO'
                GROUP BY p.id_programa, p.nombre
                ORDER BY total DESC";
        
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get estado de pagos distribution
     */
    public static function getEstadoPagosDistribution() {
        require_once __DIR__ . '/../Config/Database.php';
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT 
                estatus,
                COUNT(*) as total,
                SUM(saldo_pendiente) as monto_total
                FROM cargos
                GROUP BY estatus";
        
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Check if table exists
     */
    private static function tableExists($table) {
        try {
            require_once __DIR__ . '/../Config/Database.php';
            $db = Database::getInstance()->getConnection();
            $result = $db->query("SHOW TABLES LIKE '$table'");
            return $result->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get nuevos ingresos (alumnos) por mes
     */
    public static function getNuevosIngresosPorMes($meses = 6) {
        require_once __DIR__ . '/../Config/Database.php';
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT 
                DATE_FORMAT(fecha_alta, '%Y-%m') as mes,
                COUNT(*) as total
                FROM alumnos
                WHERE fecha_alta >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
                GROUP BY DATE_FORMAT(fecha_alta, '%Y-%m')
                ORDER BY mes ASC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$meses]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
