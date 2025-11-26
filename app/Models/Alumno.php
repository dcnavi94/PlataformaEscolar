<?php
// app/Models/Alumno.php

require_once '../app/Core/Model.php';
require_once '../app/Models/Usuario.php';


class Alumno extends Model {
    protected $table = 'alumnos';
    protected $primaryKey = 'id_alumno';

    /**
     * Count total alumnos
     */
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        return $stmt->fetch()['total'];
    }

    /**
     * Count alumnos by status
     */
    public function countByStatus($status) {
        $stmt = $this->query("SELECT COUNT(*) as total FROM {$this->table} WHERE estatus = ?", [$status]);
        return $stmt->fetch()['total'];
    }

    /**
     * Get alumnos by grupo
     */
    public function getByGrupo($grupoId) {
        $sql = "SELECT a.*, p.nombre as programa_nombre, g.nombre as grupo_nombre
                FROM {$this->table} a
                INNER JOIN programas p ON a.id_programa = p.id_programa
                INNER JOIN grupos g ON a.id_grupo = g.id_grupo
                WHERE a.id_grupo = ?";
        
        $stmt = $this->query($sql, [$grupoId]);
        return $stmt->fetchAll();
    }

    /**
     * Get available alumnos for a group (same program, no group or different group? Plan said no group)
     * Let's assume we want students who are enrolled but not in THIS group, or maybe not in ANY group?
     * Usually "Available" means not currently assigned to a group, or at least not in a conflicting group.
     * Let's stick to "Not in any group" or "In this program but group is NULL".
     * Actually, the requirement is usually to add students who are in the program but not yet assigned to a group.
     */
    public function getAvailableForGroup($programaId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id_programa = ? 
                AND (id_grupo IS NULL OR id_grupo = 0)
                AND estatus = 'INSCRITO'
                ORDER BY apellidos, nombre";
        
        $stmt = $this->query($sql, [$programaId]);
        return $stmt->fetchAll();
    }

    /**
     * Get all alumnos with relations
     */
    public function getAllWithRelations() {
        $sql = "SELECT a.*, p.nombre as programa_nombre, g.nombre as grupo_nombre
                FROM {$this->table} a
                LEFT JOIN programas p ON a.id_programa = p.id_programa
                LEFT JOIN grupos g ON a.id_grupo = g.id_grupo
                ORDER BY a.apellidos, a.nombre";
        
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Get alumno by user id
     */
    public function getByUserId($id_usuario) {
        $sql = "SELECT * FROM {$this->table} WHERE id_usuario = ?";
        $stmt = $this->query($sql, [$id_usuario]);
        return $stmt->fetch();
    }

    /**
     * Get alumno by user id with relations
     */
    public function getByUserIdWithRelations($id_usuario) {
        $sql = "SELECT a.*, p.nombre as programa_nombre, g.nombre as grupo_nombre
                FROM {$this->table} a
                LEFT JOIN programas p ON a.id_programa = p.id_programa
                LEFT JOIN grupos g ON a.id_grupo = g.id_grupo
                WHERE a.id_usuario = ?";
        $stmt = $this->query($sql, [$id_usuario]);
        return $stmt->fetch();
    }

    /**
     * Get alumno with user info
     */
    public function getWithUser($id) {
        $sql = "SELECT a.*, u.correo as usuario_correo, u.rol,
                p.nombre as programa_nombre,
                g.nombre as grupo_nombre
                FROM {$this->table} a
                LEFT JOIN usuarios u ON a.id_usuario = u.id_usuario
                LEFT JOIN programas p ON a.id_programa = p.id_programa
                LEFT JOIN grupos g ON a.id_grupo = g.id_grupo
                WHERE a.id_alumno = ?";
        
        $stmt = $this->query($sql, [$id]);
        return $stmt->fetch();
    }

    /**
     * Create alumno with user and generate charges
     */
    public function createWithUser($data) {
        try {
            $this->db->beginTransaction();

            // Create user
            $usuarioModel = new Usuario();
            $userId = $usuarioModel->create([
                'nombre' => $data['nombre'],
                'correo' => $data['correo'],
                'password' => $data['password'] ?? 'alumno123', // Default password
                'rol' => 'ALUMNO',
                'estado' => 'ACTIVO'
            ]);

            // Create alumno
            $alumnoData = [
                'id_usuario' => $userId,
                'nombre' => $data['nombre'],
                'apellidos' => $data['apellidos'],
                'matricula' => $data['matricula'] ?? null,
                'correo' => $data['correo'],
                'telefono' => $data['telefono'] ?? null,
                'estatus' => 'INSCRITO',
                'porcentaje_beca' => $data['porcentaje_beca'] ?? 0,
                'id_programa' => $data['id_programa'],
                'id_grupo' => $data['id_grupo']
            ];

            $alumnoId = $this->insert($alumnoData);

            // Generate initial charges
            $this->generateInitialCharges($alumnoId, $data['id_programa'], $data['id_grupo'], $data['porcentaje_beca'] ?? 0);

            $this->db->commit();
            return $alumnoId;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Generate initial charges for new alumno
     */
    private function generateInitialCharges($alumnoId, $programaId, $grupoId, $becaPorcentaje) {
        // Get programa info
        $programa = $this->query("SELECT * FROM programas WHERE id_programa = ?", [$programaId])->fetch();
        
        // Get grupo/periodo info
        $grupo = $this->query("SELECT * FROM grupos WHERE id_grupo = ?", [$grupoId])->fetch();
        $periodo = $this->query("SELECT * FROM periodos WHERE id_periodo = ?", [$grupo['id_periodo']])->fetch();

        // Get config
        $config = $this->query("SELECT * FROM configuracion_financiera LIMIT 1")->fetch();
        $diaLimite = $config['dia_limite_pago'];

        // 1. Cargo de Inscripción
        $montoInscripcion = $programa['monto_inscripcion'];
        $this->query("INSERT INTO cargos (id_alumno, id_grupo, id_concepto, id_periodo, mes, anio, monto, saldo_pendiente, fecha_limite, estatus)
                     VALUES (?, ?, 1, ?, 1, YEAR(NOW()), ?, ?, DATE_ADD(NOW(), INTERVAL 7 DAY), 'PENDIENTE')",
                     [$alumnoId, $grupoId, $grupo['id_periodo'], $montoInscripcion, $montoInscripcion]);

        // 2. Cargos de Colegiatura (meses del cuatrimestre)
        $fechaInicio = new DateTime($periodo['fecha_inicio']);
        $fechaFin = new DateTime($periodo['fecha_fin']);
        
        $montoColegiatura = $programa['monto_colegiatura'];
        
        // Apply beca
        if ($becaPorcentaje > 0) {
            $montoColegiatura = $montoColegiatura * (1 - ($becaPorcentaje / 100));
        }

        while ($fechaInicio <= $fechaFin) {
            $mes = (int)$fechaInicio->format('m');
            $anio = (int)$fechaInicio->format('Y');
            
            // Fecha límite: día configurado del mes
            $fechaLimite = $anio . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-' . str_pad($diaLimite, 2, '0', STR_PAD_LEFT);

            $this->query("INSERT INTO cargos (id_alumno, id_grupo, id_concepto, id_periodo, mes, anio, monto, saldo_pendiente, fecha_limite, estatus)
                         VALUES (?, ?, 2, ?, ?, ?, ?, ?, ?, 'PENDIENTE')",
                         [$alumnoId, $grupoId, $grupo['id_periodo'], $mes, $anio, $montoColegiatura, $montoColegiatura, $fechaLimite]);

            $fechaInicio->modify('+1 month');
        }
    }

    /**
     * Cancel future charges when alumno is given BAJA
     */
    public function cancelFutureCharges($alumnoId) {
        $sql = "UPDATE cargos 
                SET estatus = 'CANCELADO'
                WHERE id_alumno = ? 
                AND estatus = 'PENDIENTE'
                AND fecha_limite > NOW()";
        
        return $this->query($sql, [$alumnoId]);
    }
}
