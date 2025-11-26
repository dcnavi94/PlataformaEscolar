<?php
// app/Models/Profesor.php

require_once '../app/Core/Model.php';
require_once '../app/Models/Usuario.php';

class Profesor extends Model {
    protected $table = 'profesores';
    protected $primaryKey = 'id_profesor';

    /**
     * Get all profesores with user information
     */
    public function getAllWithRelations() {
        $sql = "SELECT p.*, u.correo as usuario_correo, u.rol
                FROM {$this->table} p
                LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario
                WHERE p.estado = 'ACTIVO'
                ORDER BY p.apellidos, p.nombre";
        
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Get profesor by user ID
     */
    public function getByUserId($id_usuario) {
        $sql = "SELECT * FROM {$this->table} WHERE id_usuario = ?";
        $stmt = $this->query($sql, [$id_usuario]);
        return $stmt->fetch();
    }

    /**
     * Get profesor with user info
     */
    public function getWithUser($id) {
        $sql = "SELECT p.*, u.correo as usuario_correo, u.rol, u.estado as usuario_estado
                FROM {$this->table} p
                LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario
                WHERE p.id_profesor = ?";
        
        $stmt = $this->query($sql, [$id]);
        return $stmt->fetch();
    }

    /**
     * Get groups assigned to a profesor
     */
    public function getAssignedGroups($id_profesor) {
        $sql = "SELECT 
                    a.id_asignacion,
                    a.estado_calificacion,
                    a.fecha_asignacion,
                    m.id_materia,
                    m.nombre as materia_nombre,
                    m.codigo as materia_codigo,
                    g.id_grupo,
                    g.nombre as grupo_nombre,
                    prog.nombre as programa_nombre,
                    COUNT(al.id_alumno) as total_alumnos
                FROM asignaciones a
                INNER JOIN materias m ON a.id_materia = m.id_materia
                INNER JOIN grupos g ON a.id_grupo = g.id_grupo
                INNER JOIN programas prog ON g.id_programa = prog.id_programa
                LEFT JOIN alumnos al ON al.id_grupo = g.id_grupo AND al.estatus = 'INSCRITO'
                WHERE a.id_profesor = ?
                GROUP BY a.id_asignacion
                ORDER BY a.estado_calificacion, m.nombre";
        
        $stmt = $this->query($sql, [$id_profesor]);
        return $stmt->fetchAll();
    }

    /**
     * Create profesor with user account
     */
    public function createWithUser($data) {
        try {
            $this->db->beginTransaction();

            // Create or link user
            $usuarioModel = new Usuario();
            
            if (isset($data['id_usuario']) && $data['id_usuario']) {
                // Link to existing user
                $userId = $data['id_usuario'];
                
                // Update user role to PROFESOR
                $usuarioModel->update($userId, ['rol' => 'PROFESOR']);
            } else {
                // Create new user
                $userId = $usuarioModel->create([
                    'nombre' => $data['nombre'] . ' ' . $data['apellidos'],
                    'correo' => $data['email'],
                    'password' => $data['password'] ?? 'profesor123', // Default password
                    'rol' => 'PROFESOR',
                    'estado' => 'ACTIVO'
                ]);
            }

            // Create profesor
            $profesorData = [
                'id_usuario' => $userId,
                'nombre' => $data['nombre'],
                'apellidos' => $data['apellidos'],
                'telefono' => $data['telefono'] ?? null,
                'email' => $data['email'],
                'especialidad' => $data['especialidad'] ?? null,
                'estado' => $data['estado'] ?? 'ACTIVO',
                'tipo_contrato' => $data['tipo_contrato'] ?? 'HONORARIOS',
                'tarifa_hora' => $data['tarifa_hora'] ?? 0.00,
                'rfc' => $data['rfc'] ?? null,
                'curp' => $data['curp'] ?? null,
                'nss' => $data['nss'] ?? null,
                'banco' => $data['banco'] ?? null,
                'clabe' => $data['clabe'] ?? null
            ];

            $profesorId = $this->insert($profesorData);

            $this->db->commit();
            return $profesorId;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateHRData($id, $data) {
        return $this->update($id, [
            'tipo_contrato' => $data['tipo_contrato'],
            'tarifa_hora' => $data['tarifa_hora'],
            'rfc' => $data['rfc'],
            'curp' => $data['curp'],
            'nss' => $data['nss'],
            'banco' => $data['banco'],
            'clabe' => $data['clabe']
        ]);
    }

    /**
     * Get all active profesores for dropdown
     */
    public function getAllActive() {
        $sql = "SELECT id_profesor, nombre, apellidos 
                FROM {$this->table} 
                WHERE estado = 'ACTIVO'
                ORDER BY apellidos, nombre";
        
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }
}
