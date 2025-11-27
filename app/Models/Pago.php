<?php
// app/Models/Pago.php

require_once '../app/Core/Model.php';
require_once '../app/Models/Cargo.php';

class Pago extends Model {
    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';

    /**
     * Process single payment for a cargo
     */
    public function processPago($data) {
        // Get cargo info to get alumno_id
        $cargoModel = new Cargo();
        $cargo = $cargoModel->find($data['id_cargo']);
        
        if (!$cargo) {
            throw new Exception("Cargo no encontrado");
        }

        $pagoData = [
            'id_alumno' => $cargo['id_alumno'],
            'monto_total' => $data['monto'],
            'metodo_pago' => $data['metodo_pago'],
            'comprobante_url' => $data['comprobante_url'] ?? null,
            'referencia_externa' => $data['referencia'] ?? null,
            'id_usuario_registro' => $_SESSION['user_id'] ?? null
        ];

        $cargos = [
            [
                'id_cargo' => $data['id_cargo'],
                'monto_aplicado' => $data['monto']
            ]
        ];

        return $this->createPayment($pagoData, $cargos);
    }

    /**
     * Get total ingresos for a month
     */
    public function getTotalIngresosMes($mesAnio) {
        $sql = "SELECT COALESCE(SUM(monto_total), 0) as total
                FROM {$this->table}
                WHERE DATE_FORMAT(fecha_pago, '%Y-%m') = ?
                AND estado = 'COMPLETADO'";
        
        $stmt = $this->query($sql, [$mesAnio]);
        return $stmt->fetch()['total'];
    }

    /**
     * Create payment and update cargos
     */
    public function createPayment($data, $cargos) {
        try {
            $this->db->beginTransaction();

            // Create pago
            $pagoId = $this->insert([
                'id_alumno' => $data['id_alumno'],
                'monto_total' => $data['monto_total'],
                'metodo_pago' => $data['metodo_pago'],
                'comprobante_url' => $data['comprobante_url'] ?? null,
                'id_usuario_registro' => $data['id_usuario_registro'] ?? null,
                'referencia_externa' => $data['referencia_externa'] ?? null,
                'estado' => 'COMPLETADO'
            ]);

            // Update cargos and create pago_detalle
            $cargoModel = new Cargo();
            foreach ($cargos as $cargo) {
                // Apply payment to cargo
                $cargoModel->applyPartialPayment($cargo['id_cargo'], $cargo['monto_aplicado']);

                // Create pago_detalle
                $this->query("INSERT INTO pago_detalle (id_pago, id_cargo, monto_aplicado) VALUES (?, ?, ?)",
                            [$pagoId, $cargo['id_cargo'], $cargo['monto_aplicado']]);
            }

            $this->db->commit();
            return $pagoId;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Get pagos by alumno
     */
    public function getByAlumno($alumnoId) {
        $sql = "SELECT p.*, u.nombre as registrado_por
                FROM {$this->table} p
                LEFT JOIN usuarios u ON p.id_usuario_registro = u.id_usuario
                WHERE p.id_alumno = ?
                ORDER BY p.fecha_pago DESC";
        
        $stmt = $this->query($sql, [$alumnoId]);
        return $stmt->fetchAll();
    }

    /**
     * Get ingresos for last N months (for chart)
     */
    public function getIngresosPorMes($meses = 6) {
        $sql = "SELECT 
                    DATE_FORMAT(fecha_pago, '%Y-%m') as mes,
                    COALESCE(SUM(monto_total), 0) as total
                FROM {$this->table}
                WHERE estado = 'COMPLETADO'
                AND fecha_pago >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
                GROUP BY DATE_FORMAT(fecha_pago, '%Y-%m')
                ORDER BY mes ASC";
        
        $stmt = $this->query($sql, [$meses]);
        return $stmt->fetchAll();
    }

    /**
     * Get payment distribution by method
     */
    public function getDistribucionPorMetodo() {
        $sql = "SELECT 
                    metodo_pago,
                    COUNT(*) as cantidad,
                    COALESCE(SUM(monto_total), 0) as total
                FROM {$this->table}
                WHERE estado = 'COMPLETADO'
                AND YEAR(fecha_pago) = YEAR(CURDATE())
                GROUP BY metodo_pago
                ORDER BY total DESC";
        
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Get last N transactions with details
     */
    public function getUltimasTransacciones($limit = 10) {
        $sql = "SELECT 
                    p.id_pago,
                    p.fecha_pago,
                    p.monto_total,
                    p.metodo_pago,
                    p.estado,
                    CONCAT(a.nombre, ' ', a.apellidos) as alumno_nombre,
                    a.id_alumno,
                    GROUP_CONCAT(cp.nombre SEPARATOR ', ') as conceptos
                FROM {$this->table} p
                INNER JOIN alumnos a ON p.id_alumno = a.id_alumno
                LEFT JOIN pago_detalle pd ON p.id_pago = pd.id_pago
                LEFT JOIN cargos c ON pd.id_cargo = c.id_cargo
                LEFT JOIN conceptos_pago cp ON c.id_concepto = cp.id_concepto
                WHERE p.estado = 'COMPLETADO'
                GROUP BY p.id_pago, p.fecha_pago, p.monto_total, p.metodo_pago, p.estado, a.nombre, a.apellidos, a.id_alumno
                ORDER BY p.fecha_pago DESC
                LIMIT ?";
        
        $stmt = $this->query($sql, [$limit]);
        return $stmt->fetchAll();
    }
}
