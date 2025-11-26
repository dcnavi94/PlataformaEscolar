<?php
// app/Models/Bitacora.php

require_once '../app/Core/Model.php';

class Bitacora extends Model {
    protected $table = 'bitacora';

    /**
     * Log an action
     */
    public function log($userId, $table, $recordId, $action, $description = '') {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        
        return $this->insert([
            'id_usuario' => $userId,
            'tabla_afectada' => $table,
            'id_registro' => $recordId,
            'accion' => $action,
            'descripcion' => $description,
            'ip' => $ip
        ]);
    }

    /**
     * Get logs with filters
     */
    public function getLogs($filters = []) {
        $sql = "SELECT b.*, u.nombre as usuario_nombre 
                FROM bitacora b
                LEFT JOIN usuarios u ON b.id_usuario = u.id_usuario
                WHERE 1=1";
        
        $params = [];

        if (!empty($filters['usuario'])) {
            $sql .= " AND b.id_usuario = ?";
            $params[] = $filters['usuario'];
        }

        if (!empty($filters['tabla'])) {
            $sql .= " AND b.tabla_afectada = ?";
            $params[] = $filters['tabla'];
        }

        if (!empty($filters['accion'])) {
            $sql .= " AND b.accion = ?";
            $params[] = $filters['accion'];
        }

        if (!empty($filters['fecha_desde'])) {
            $sql .= " AND DATE(b.fecha_hora) >= ?";
            $params[] = $filters['fecha_desde'];
        }

        if (!empty($filters['fecha_hasta'])) {
            $sql .= " AND DATE(b.fecha_hora) <= ?";
            $params[] = $filters['fecha_hasta'];
        }

        $sql .= " ORDER BY b.fecha_hora DESC LIMIT 100";

        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
}
