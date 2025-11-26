-- Migration: Create Admin Users and Permissions System
-- Date: 2025-11-23

-- Table: usuarios_admin
CREATE TABLE IF NOT EXISTS usuarios_admin (
    id_usuario_admin INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: permisos_usuario
CREATE TABLE IF NOT EXISTS permisos_usuario (
    id_permiso INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario_admin INT NOT NULL,
    modulo VARCHAR(50) NOT NULL,
    puede_leer BOOLEAN DEFAULT FALSE,
    puede_escribir BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_usuario_modulo (id_usuario_admin, modulo),
    FOREIGN KEY (id_usuario_admin) REFERENCES usuarios_admin(id_usuario_admin) ON DELETE CASCADE,
    INDEX idx_usuario_modulo (id_usuario_admin, modulo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add comment
ALTER TABLE usuarios_admin COMMENT = 'Usuarios administrativos del sistema';
ALTER TABLE permisos_usuario COMMENT = 'Permisos granulares por módulo para usuarios admin';
