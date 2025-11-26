-- Academic Portal Schema
-- Tables for activities, assignments, and class resources

-- Actividades/Tareas Table
CREATE TABLE IF NOT EXISTS actividades (
    id_actividad INT AUTO_INCREMENT PRIMARY KEY,
    id_asignacion INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    tipo ENUM('TAREA', 'EXAMEN', 'PROYECTO', 'LECTURA', 'OTRO') DEFAULT 'TAREA',
    fecha_publicacion DATETIME NOT NULL,
    fecha_limite DATETIME,
    puntos_max DECIMAL(5, 2) DEFAULT 100.00,
    permite_entrega BOOLEAN DEFAULT TRUE,
    archivo_adjunto VARCHAR(255),
    estado ENUM('ACTIVA', 'CERRADA', 'BORRADOR') DEFAULT 'ACTIVA',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_asignacion) REFERENCES asignaciones(id_asignacion) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Entregas de Tareas Table
CREATE TABLE IF NOT EXISTS entregas_tareas (
    id_entrega INT AUTO_INCREMENT PRIMARY KEY,
    id_actividad INT NOT NULL,
    id_alumno INT NOT NULL,
    fecha_entrega DATETIME NOT NULL,
    archivo_entrega VARCHAR(255),
    comentarios TEXT,
    calificacion DECIMAL(5, 2),
    retroalimentacion TEXT,
    estado ENUM('ENVIADA', 'CALIFICADA', 'TARDIA', 'REVISION') DEFAULT 'ENVIADA',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_actividad) REFERENCES actividades(id_actividad) ON DELETE CASCADE,
    FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno) ON DELETE CASCADE,
    UNIQUE KEY unique_entrega (id_actividad, id_alumno)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Recursos de Clase Table
CREATE TABLE IF NOT EXISTS recursos_clase (
    id_recurso INT AUTO_INCREMENT PRIMARY KEY,
    id_asignacion INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    tipo ENUM('LINK', 'DOCUMENTO', 'VIDEO', 'PRESENTACION', 'OTRO') DEFAULT 'LINK',
    url VARCHAR(500),
    archivo VARCHAR(255),
    descripcion TEXT,
    fecha_publicacion DATETIME NOT NULL,
    visible BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_asignacion) REFERENCES asignaciones(id_asignacion) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Indexes for better performance
CREATE INDEX idx_actividades_asignacion ON actividades(id_asignacion);
CREATE INDEX idx_actividades_fecha_limite ON actividades(fecha_limite);
CREATE INDEX idx_entregas_actividad ON entregas_tareas(id_actividad);
CREATE INDEX idx_entregas_alumno ON entregas_tareas(id_alumno);
CREATE INDEX idx_recursos_asignacion ON recursos_clase(id_asignacion);
