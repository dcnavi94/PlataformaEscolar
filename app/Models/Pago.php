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
}
