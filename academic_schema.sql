-- Academic Module Schema

-- Profesores Table
CREATE TABLE IF NOT EXISTS profesores (
    id_profesor INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(100),
    especialidad VARCHAR(100),
    estado ENUM('ACTIVO', 'INACTIVO') DEFAULT 'ACTIVO',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Materias Table
CREATE TABLE IF NOT EXISTS materias (
    id_materia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    codigo VARCHAR(20) UNIQUE,
    creditos INT DEFAULT 0,
    id_programa INT,
    estado ENUM('ACTIVO', 'INACTIVO') DEFAULT 'ACTIVO',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_programa) REFERENCES programas(id_programa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Asignaciones Table (Teacher -> Subject -> Group)
CREATE TABLE IF NOT EXISTS asignaciones (
    id_asignacion INT AUTO_INCREMENT PRIMARY KEY,
    id_profesor INT NOT NULL,
    id_materia INT NOT NULL,
    id_grupo INT NOT NULL,
    estado_calificacion ENUM('ABIERTA', 'CERRADA') DEFAULT 'ABIERTA',
    fecha_asignacion DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_profesor) REFERENCES profesores(id_profesor),
    FOREIGN KEY (id_materia) REFERENCES materias(id_materia),
    FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Calificaciones Table
CREATE TABLE IF NOT EXISTS calificaciones (
    id_calificacion INT AUTO_INCREMENT PRIMARY KEY,
    id_asignacion INT NOT NULL,
    id_alumno INT NOT NULL,
    calificacion DECIMAL(5, 2) NOT NULL, -- Allows 100.00 or 10.00
    observaciones TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_asignacion) REFERENCES asignaciones(id_asignacion),
    FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno),
    UNIQUE KEY unique_calificacion (id_asignacion, id_alumno) -- Ensure one grade per student per assignment
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
