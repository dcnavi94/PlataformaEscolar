<?php
// grant_permissions.php

// Define BASE_URL if needed by config, though usually for web.
define('BASE_URL', 'http://localhost');

// Adjust paths since we are in root
require_once 'app/Config/Database.php';
require_once 'app/Core/Model.php';
require_once 'app/Models/UsuarioAdmin.php';
require_once 'app/Models/PermisoUsuario.php';

try {
    $usuarioAdminModel = new UsuarioAdmin();
    $permisoModel = new PermisoUsuario();

    $email = 'admin@escuela.com';
    
    echo "Buscando usuario admin con email: $email\n";
    
    // Find by email in usuarios_admin
    // UsuarioAdmin model doesn't have findByEmail, but Model has findBy
    $admin = $usuarioAdminModel->findBy('email', $email);
    
    if (!$admin) {
        echo "Usuario admin no encontrado en tabla usuarios_admin.\n";
        // Check if exists in usuarios table and create admin profile if needed?
        // User said "EL TIENE PERMISO", implying he exists.
        // Let's try to find in usuarios table just in case.
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            echo "Encontrado en tabla usuarios (ID: {$user['id_usuario']}). Creando perfil admin...\n";
            // Create admin profile
            $data = [
                'id_usuario' => $user['id_usuario'],
                'nombre' => 'Admin', // Placeholder
                'apellidos' => 'General', // Placeholder
                'email' => $email,
                'telefono' => '',
                'activo' => 1
            ];
            $idAdmin = $usuarioAdminModel->create($data);
            echo "Perfil admin creado con ID: $idAdmin\n";
            $admin = ['id_usuario_admin' => $idAdmin];
        } else {
            die("Error: El usuario no existe en ninguna tabla.\n");
        }
    } else {
        echo "Usuario admin encontrado (ID: {$admin['id_usuario_admin']})\n";
    }

    $idAdmin = $admin['id_usuario_admin'];
    
    // Modules
    $modulos = ['alumnos', 'grupos', 'programas', 'periodos', 'pagos', 'reportes', 'configuracion', 'usuarios'];
    
    $permisos = [];
    foreach ($modulos as $modulo) {
        $permisos[$modulo] = ['leer' => true, 'escribir' => true];
    }
    
    echo "Asignando permisos totales...\n";
    $permisoModel->setPermissions($idAdmin, $permisos);
    
    echo "¡Permisos asignados correctamente!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
