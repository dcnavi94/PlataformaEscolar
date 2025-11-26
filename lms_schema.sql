-- LMS Schema Extensions

-- Modulos Table (Units/Sections within a Course)
CREATE TABLE IF NOT EXISTS modulos (
    id_modulo INT AUTO_INCREMENT PRIMARY KEY,
    id_asignacion INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    orden INT DEFAULT 0,
    estado ENUM('ACTIVO', 'INACTIVO', 'BORRADOR') DEFAULT 'ACTIVO',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_asignacion) REFERENCES asignaciones(id_asignacion) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Temas Table (Topics within a Module)
CREATE TABLE IF NOT EXISTS temas (
    id_tema INT AUTO_INCREMENT PRIMARY KEY,
    id_modulo INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    orden INT DEFAULT 0,
    estado ENUM('ACTIVO', 'INACTIVO') DEFAULT 'ACTIVO',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_modulo) REFERENCES modulos(id_modulo) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add id_tema to actividades
ALTER TABLE actividades ADD COLUMN id_tema INT NULL;
ALTER TABLE actividades ADD COLUMN link_video VARCHAR(500) NULL;
ALTER TABLE actividades MODIFY COLUMN tipo ENUM('TAREA', 'EXAMEN', 'PROYECTO', 'LECTURA', 'QUIZ', 'VIDEO_CUESTIONARIO', 'OTRO') DEFAULT 'TAREA';
ALTER TABLE actividades ADD CONSTRAINT fk_actividad_tema FOREIGN KEY (id_tema) REFERENCES temas(id_tema) ON DELETE SET NULL;

-- Add id_tema to recursos_clase
ALTER TABLE recursos_clase ADD COLUMN id_tema INT NULL;
ALTER TABLE recursos_clase ADD CONSTRAINT fk_recurso_tema FOREIGN KEY (id_tema) REFERENCES temas(id_tema) ON DELETE SET NULL;
