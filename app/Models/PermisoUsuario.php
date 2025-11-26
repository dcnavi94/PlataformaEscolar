<?php
// app/Models/PermisoUsuario.php

require_once '../app/Core/Model.php';

class PermisoUsuario extends Model {

    /**
     * Set permissions for a user
     */
    public function setPermissions($idUsuarioAdmin, $permisos) {
        try {
            $this->db->beginTransaction();

            // Delete existing permissions
            $sqlDelete = "DELETE FROM permisos_usuario WHERE id_usuario_admin = ?";
            $this->query($sqlDelete, [$idUsuarioAdmin]);

            // Insert new permissions
            $sqlInsert = "INSERT INTO permisos_usuario (id_usuario_admin, modulo, puede_leer, puede_escribir) 
                          VALUES (?, ?, ?, ?)";
            
            foreach ($permisos as $modulo => $permiso) {
                $this->query($sqlInsert, [
                    $idUsuarioAdmin,
                    $modulo,
                    (int)($permiso['leer'] ?? 0),
                    (int)($permiso['escribir'] ?? 0)
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Get all permissions for a user
     */
    public function getByUser($idUsuarioAdmin) {
        $sql = "SELECT * FROM permisos_usuario WHERE id_usuario_admin = ?";
        $stmt = $this->query($sql, [$idUsuarioAdmin]);
        return $stmt->fetchAll();
    }

    /**
     * Check if user has permission
     */
    public function hasPermission($idUsuarioAdmin, $modulo, $tipo = 'leer') {
        $column = $tipo === 'escribir' ? 'puede_escribir' : 'puede_leer';
        $sql = "SELECT $column FROM permisos_usuario 
                WHERE id_usuario_admin = ? AND modulo = ?";
        $stmt = $this->query($sql, [$idUsuarioAdmin, $modulo]);
        $result = $stmt->fetch();
        return $result ? (bool)$result[$column] : false;
    }

    /**
     * Get permissions indexed by module
     */
    public function getPermissionsArray($idUsuarioAdmin) {
        $permisos = $this->getByUser($idUsuarioAdmin);
        $result = [];
        foreach ($permisos as $permiso) {
            $result[$permiso['modulo']] = [
                'leer' => (bool)$permiso['puede_leer'],
                'escribir' => (bool)$permiso['puede_escribir']
            ];
        }
        return $result;
    }

    /**
     * Get available modules
     */
    public function getModulos() {
        return [
            'alumnos' => 'Alumnos',
            'grupos' => 'Grupos',
            'programas' => 'Programas',
            'periodos' => 'Periodos',
            'pagos' => 'Pagos',
            'reportes' => 'Reportes',
            'configuracion' => 'Configuración'
        ];
    }
}
