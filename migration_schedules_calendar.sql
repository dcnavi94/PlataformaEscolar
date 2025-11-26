-- Migration: Schedules and Calendar Module

-- Table for Class Schedules
CREATE TABLE IF NOT EXISTS horarios (
    id_horario INT AUTO_INCREMENT PRIMARY KEY,
    id_asignacion INT NOT NULL,
    dia_semana ENUM('LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO') NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    aula VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_asignacion) REFERENCES asignaciones(id_asignacion) ON DELETE CASCADE,
    UNIQUE KEY unique_schedule (id_asignacion, dia_semana, hora_inicio) -- Prevent duplicate schedules for same assignment
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table for Calendar Events
CREATE TABLE IF NOT EXISTS eventos_calendario (
    id_evento INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
    tipo ENUM('FERIADO', 'EXAMEN', 'EVENTO', 'ADMINISTRATIVO') DEFAULT 'EVENTO',
    color VARCHAR(20) DEFAULT '#3788d8', -- Hex color for UI
    created_by INT, -- User ID who created it (optional)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
