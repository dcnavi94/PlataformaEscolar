-- Migration: Sistema de Titulación y Certificación
-- Created: 2025-11-25

-- Procesos de Titulación
CREATE TABLE IF NOT EXISTS procesos_titulacion (
    id_proceso INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT NOT NULL,
    id_programa INT NOT NULL,
    fecha_solicitud DATE NOT NULL,
    fecha_ceremonia DATE,
    estado ENUM('SOLICITADO', 'EN_REVISION', 'APROBADO', 'RECHAZADO', 'TITULADO') DEFAULT 'SOLICITADO',
    modalidad ENUM('TESIS', 'EXAMEN_PROFESIONAL', 'PROYECTO', 'PROMEDIO', 'CURSO') NOT NULL,
    numero_folio VARCHAR(50) UNIQUE,
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno) ON DELETE CASCADE,
    FOREIGN KEY (id_programa) REFERENCES programas(id_programa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Requisitos de Titulación por Programa
CREATE TABLE IF NOT EXISTS requisitos_titulacion (
    id_requisito INT AUTO_INCREMENT PRIMARY KEY,
    id_programa INT NOT NULL,
    nombre_requisito VARCHAR(255) NOT NULL,
    descripcion TEXT,
    es_obligatorio BOOLEAN DEFAULT TRUE,
    tipo_documento ENUM('PDF', 'IMAGEN', 'AMBOS') DEFAULT 'PDF',
    orden INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_programa) REFERENCES programas(id_programa) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cumplimiento de Requisitos
CREATE TABLE IF NOT EXISTS cumplimiento_requisitos (
    id_cumplimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_proceso INT NOT NULL,
    id_requisito INT NOT NULL,
    documento_url VARCHAR(500),
    fecha_carga DATETIME,
    estado ENUM('PENDIENTE', 'CARGADO', 'APROBADO', 'RECHAZADO') DEFAULT 'PENDIENTE',
    comentarios TEXT,
    revisado_por INT,
    fecha_revision DATETIME,
    FOREIGN KEY (id_proceso) REFERENCES procesos_titulacion(id_proceso) ON DELETE CASCADE,
    FOREIGN KEY (id_requisito) REFERENCES requisitos_titulacion(id_requisito),
    FOREIGN KEY (revisado_por) REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Certificados Generados
CREATE TABLE IF NOT EXISTS certificados (
    id_certificado INT AUTO_INCREMENT PRIMARY KEY,
    id_proceso INT NOT NULL,
    numero_certificado VARCHAR(100) UNIQUE NOT NULL,
    tipo ENUM('TITULO', 'CERTIFICADO_ESTUDIOS', 'DIPLOMA') DEFAULT 'TITULO',
    nombre_completo VARCHAR(255) NOT NULL,
    programa VARCHAR(255) NOT NULL,
    fecha_expedicion DATE NOT NULL,
    fecha_ceremonia DATE,
    promedio_final DECIMAL(4,2),
    mencion_honorifica VARCHAR(100),
    archivo_pdf VARCHAR(500),
    codigo_qr VARCHAR(500),
    hash_verificacion VARCHAR(64) UNIQUE,
    firmado_por VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_proceso) REFERENCES procesos_titulacion(id_proceso) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Registro de Egresados
CREATE TABLE IF NOT EXISTS egresados (
    id_egresado INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT NOT NULL,
    id_programa INT NOT NULL,
    fecha_egreso DATE NOT NULL,
    promedio_general DECIMAL(4,2),
    generacion VARCHAR(20),
    titulo_obtenido VARCHAR(255),
    cedula_profesional VARCHAR(50),
    empresa_actual VARCHAR(255),
    puesto_actual VARCHAR(255),
    email_contacto VARCHAR(255),
    telefono_contacto VARCHAR(20),
    linkedin_url VARCHAR(500),
    acepta_contacto BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno) ON DELETE CASCADE,
    FOREIGN KEY (id_programa) REFERENCES programas(id_programa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Índices para mejor rendimiento
CREATE INDEX idx_proceso_alumno ON procesos_titulacion(id_alumno);
CREATE INDEX idx_proceso_estado ON procesos_titulacion(estado);
CREATE INDEX idx_certificado_hash ON certificados(hash_verificacion);
CREATE INDEX idx_egresado_programa ON egresados(id_programa);
CREATE INDEX idx_requisito_programa ON requisitos_titulacion(id_programa);
