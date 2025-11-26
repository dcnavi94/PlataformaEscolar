<?php
// app/Models/Certificado.php

require_once '../app/Core/Model.php';

class Certificado extends Model {
    protected $table = 'certificados';
    protected $primaryKey = 'id_certificado';

    /**
     * Generate certificado
     */
    public function generar($id_proceso, $data) {
        // Generate unique hash for verification
        $hash = $this->generateHash($id_proceso, $data['nombre_completo']);
        
        $certificadoData = [
            'id_proceso' => $id_proceso,
            'numero_certificado' => $this->generateNumeroCertificado(),
            'tipo' => $data['tipo'] ?? 'TITULO',
            'nombre_completo' => $data['nombre_completo'],
            'programa' => $data['programa'],
            'fecha_expedicion' => $data['fecha_expedicion'] ?? date('Y-m-d'),
            'fecha_ceremonia' => $data['fecha_ceremonia'] ?? null,
            'promedio_final' => $data['promedio_final'] ?? null,
            'mencion_honorifica' => $data['mencion_honorifica'] ?? null,
            'hash_verificacion' => $hash,
            'firmado_por' => $data['firmado_por'] ?? 'Dirección General'
        ];
        
        return $this->insert($certificadoData);
    }

    /**
     * Generate unique certificate number
     */
    private function generateNumeroCertificado() {
        $year = date('Y');
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE YEAR(created_at) = ?";
        $stmt = $this->query($sql, [$year]);
        $result = $stmt->fetch();
        $numero = str_pad($result['total'] + 1, 6, '0', STR_PAD_LEFT);
        return "CERT-{$year}-{$numero}";
    }

    /**
     * Generate verification hash
     */
    private function generateHash($id_proceso, $nombre) {
        $data = $id_proceso . $nombre . time() . rand();
        return hash('sha256', $data);
    }

    /**
     * Verificar certificado by hash
     */
    public function verificar($hash) {
        $sql = "SELECT c.*, pt.modalidad, pt.fecha_ceremonia as ceremonia_proceso
                FROM {$this->table} c
                INNER JOIN procesos_titulacion pt ON c.id_proceso = pt.id_proceso
                WHERE c.hash_verificacion = ?";
        
        return $this->query($sql, [$hash])->fetch();
    }

    /**
     * Get certificado by alumno
     */
    public function getByAlumno($id_alumno) {
        $sql = "SELECT c.*, pt.modalidad
                FROM {$this->table} c
                INNER JOIN procesos_titulacion pt ON c.id_proceso = pt.id_proceso
                WHERE pt.id_alumno = ?
                ORDER BY c.created_at DESC";
        
        return $this->query($sql, [$id_alumno])->fetchAll();
    }

    /**
     * Update PDF path
     */
    public function updatePDF($id, $pdf_path) {
        return $this->update($id, ['archivo_pdf' => $pdf_path]);
    }

    /**
     * Update QR path
     */
    public function updateQR($id, $qr_path) {
        return $this->update($id, ['codigo_qr' => $qr_path]);
    }
}
