-- Migration: Sistema de Inscripciones en Línea
-- Created: 2025-11-25

-- Periodos de Inscripción
CREATE TABLE IF NOT EXISTS periodos_inscripcion (
    id_periodo_inscripcion INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    id_programa INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    cupo_maximo INT DEFAULT 0,
    monto_inscripcion DECIMAL(10,2) DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    requisitos_texto TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_programa) REFERENCES programas(id_programa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Solicitudes de Inscripción
CREATE TABLE IF NOT EXISTS solicitudes_inscripcion (
    id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
    folio VARCHAR(50) UNIQUE NOT NULL,
    id_periodo_inscripcion INT NOT NULL,
    
    -- Datos Personales
    nombre VARCHAR(100) NOT NULL,
    apellido_paterno VARCHAR(100) NOT NULL,
    apellido_materno VARCHAR(100),
    fecha_nacimiento DATE NOT NULL,
    curp VARCHAR(18) UNIQUE NOT NULL,
    sexo ENUM('M', 'F', 'OTRO') NOT NULL,
    
    -- Contacto
    correo VARCHAR(255) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    celular VARCHAR(20),
    
    -- Dirección
    calle VARCHAR(255),
    numero_exterior VARCHAR(20),
    numero_interior VARCHAR(20),
    colonia VARCHAR(100),
    ciudad VARCHAR(100),
    estado_residencia VARCHAR(100),
    codigo_postal VARCHAR(10),
    
    -- Académicos
    escuela_procedencia VARCHAR(255),
    promedio_anterior DECIMAL(4,2),
    
    -- Estado de Solicitud
    estado ENUM('PENDIENTE', 'EN_REVISION', 'APROBADA', 'RECHAZADA', 'MATRICULADO') DEFAULT 'PENDIENTE',
    comentarios_admin TEXT,
    revisado_por INT,
    fecha_revision DATETIME,
    
    -- Pago
    pago_realizado BOOLEAN DEFAULT FALSE,
    comprobante_pago VARCHAR(500),
    
    -- Matrícula generada
    id_alumno_generado INT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_periodo_inscripcion) REFERENCES periodos_inscripcion(id_periodo_inscripcion),
    FOREIGN KEY (revisado_por) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_alumno_generado) REFERENCES alumnos(id_alumno)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Documentos de Inscripción
CREATE TABLE IF NOT EXISTS documentos_inscripcion (
    id_documento INT AUTO_INCREMENT PRIMARY KEY,
    id_solicitud INT NOT NULL,
    tipo_documento VARCHAR(100) NOT NULL,
    nombre_archivo VARCHAR(255) NOT NULL,
    ruta_archivo VARCHAR(500) NOT NULL,
    estado ENUM('PENDIENTE', 'APROBADO', 'RECHAZADO') DEFAULT 'PENDIENTE',
    comentarios TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_solicitud) REFERENCES solicitudes_inscripcion(id_solicitud) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Índices para mejor rendimiento
CREATE INDEX idx_solicitud_folio ON solicitudes_inscripcion(folio);
CREATE INDEX idx_solicitud_estado ON solicitudes_inscripcion(estado);
CREATE INDEX idx_solicitud_curp ON solicitudes_inscripcion(curp);
CREATE INDEX idx_solicitud_correo ON solicitudes_inscripcion(correo);
CREATE INDEX idx_periodo_activo ON periodos_inscripcion(activo);
