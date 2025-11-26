-- Base de Datos: control_pagos

CREATE DATABASE IF NOT EXISTS control_pagos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE control_pagos;

-- 1. Usuarios
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('ADMIN', 'ALUMNO') NOT NULL,
    estado ENUM('ACTIVO', 'INACTIVO') DEFAULT 'ACTIVO',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Programas
CREATE TABLE programas (
    id_programa INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo ENUM('BACHILLERATO', 'LICENCIATURA') NOT NULL,
    modalidad ENUM('Lunes a Viernes', 'Sabatina', 'Virtual') DEFAULT 'Virtual',
    turno VARCHAR(50),
    monto_colegiatura DECIMAL(10,2) NOT NULL,
    monto_inscripcion DECIMAL(10,2) NOT NULL,
    estado ENUM('ACTIVO', 'INACTIVO') DEFAULT 'ACTIVO'
);

-- 3. Periodos
CREATE TABLE periodos (
    id_periodo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL, -- Enero-Abril, etc.
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    anio INT NOT NULL,
    numero_periodo INT NOT NULL -- 1, 2, 3
);

-- 4. Grupos
CREATE TABLE grupos (
    id_grupo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    id_programa INT NOT NULL,
    id_periodo INT NOT NULL,
    estado ENUM('ACTIVO', 'INACTIVO') DEFAULT 'ACTIVO',
    FOREIGN KEY (id_programa) REFERENCES programas(id_programa),
    FOREIGN KEY (id_periodo) REFERENCES periodos(id_periodo)
);

-- 5. Alumnos
CREATE TABLE alumnos (
    id_alumno INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNIQUE, -- Opcional, si el alumno ya tiene usuario
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    estatus ENUM('INSCRITO', 'BAJA', 'EGRESADO', 'SUSPENDIDO') DEFAULT 'INSCRITO',
    porcentaje_beca DECIMAL(5,2) DEFAULT 0.00, -- 0 a 100
    id_programa INT NOT NULL,
    id_grupo INT,
    fecha_alta DATE DEFAULT (CURRENT_DATE),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_programa) REFERENCES programas(id_programa),
    FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo)
);

-- 6. Conceptos de Pago
CREATE TABLE conceptos_pago (
    id_concepto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    tipo ENUM('INSCRIPCION', 'COLEGIATURA', 'EXTRA', 'PENALIZACION') NOT NULL,
    recurrente BOOLEAN DEFAULT FALSE,
    admite_recargo BOOLEAN DEFAULT FALSE,
    monto_default DECIMAL(10,2) DEFAULT 0.00,
    dias_tolerancia INT DEFAULT 0,
    aplica_beca BOOLEAN DEFAULT FALSE,
    recargo_fijo DECIMAL(10,2) DEFAULT 0.00,
    recargo_porcentaje DECIMAL(5,2) DEFAULT 0.00,
    estado ENUM('ACTIVO', 'INACTIVO') DEFAULT 'ACTIVO'
);

-- 7. Configuración Financiera
CREATE TABLE configuracion_financiera (
    id_config INT AUTO_INCREMENT PRIMARY KEY,
    dia_limite_pago INT NOT NULL DEFAULT 7,
    tipo_penalizacion ENUM('MONTO', 'PORCENTAJE') NOT NULL DEFAULT 'MONTO',
    valor_penalizacion DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    fecha_ultima_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 8. Cargos
CREATE TABLE cargos (
    id_cargo INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT NOT NULL,
    id_grupo INT NOT NULL,
    id_concepto INT NOT NULL,
    id_periodo INT NOT NULL,
    mes INT NOT NULL, -- 1-12
    anio INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    saldo_pendiente DECIMAL(10,2) NOT NULL, -- Para pagos parciales
    estatus ENUM('PENDIENTE', 'PAGADO', 'PARCIAL', 'VENCIDO', 'CANCELADO', 'PENALIZACION') DEFAULT 'PENDIENTE',
    fecha_generacion DATE DEFAULT (CURRENT_DATE),
    fecha_limite DATE NOT NULL,
    fecha_pago DATETIME,
    id_pago INT, -- Se llenará cuando se pague
    FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno),
    FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo),
    FOREIGN KEY (id_concepto) REFERENCES conceptos_pago(id_concepto),
    FOREIGN KEY (id_periodo) REFERENCES periodos(id_periodo)
);

-- 9. Pagos
CREATE TABLE pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT NOT NULL,
    fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
    monto_total DECIMAL(10,2) NOT NULL,
    metodo_pago ENUM('EFECTIVO', 'TRANSFERENCIA', 'PAYPAL') NOT NULL,
    comprobante_url VARCHAR(255), -- Para foto/pdf de transferencia
    id_usuario_registro INT, -- Null si fue online
    referencia_externa VARCHAR(255), -- ID PayPal
    estado ENUM('COMPLETADO', 'CANCELADO') DEFAULT 'COMPLETADO',
    FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno),
    FOREIGN KEY (id_usuario_registro) REFERENCES usuarios(id_usuario)
);

-- 10. Detalle de Pago
CREATE TABLE pago_detalle (
    id_pago_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pago INT NOT NULL,
    id_cargo INT NOT NULL,
    monto_aplicado DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pago) REFERENCES pagos(id_pago),
    FOREIGN KEY (id_cargo) REFERENCES cargos(id_cargo)
);

-- 11. Bitácora
CREATE TABLE bitacora (
    id_bitacora INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    tabla_afectada VARCHAR(50),
    id_registro INT,
    accion VARCHAR(50),
    descripcion TEXT,
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip VARCHAR(45),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- 12. Importaciones
CREATE TABLE importaciones (
    id_importacion INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('ALUMNOS', 'GRUPOS', 'PAGOS'),
    archivo_nombre VARCHAR(255),
    estado ENUM('EXITO', 'ERROR', 'PARCIAL'),
    total_registros INT,
    registros_correctos INT,
    registros_error INT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_usuario INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- 13. Notificaciones
CREATE TABLE notificaciones (
    id_notificacion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    mensaje TEXT NOT NULL,
    leida BOOLEAN DEFAULT FALSE,
    tipo ENUM('INFO', 'ALERTA', 'PAGO', 'ACADEMICO') DEFAULT 'INFO',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- 14. Solicitudes de Servicios
CREATE TABLE solicitudes_servicios (
    id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT NOT NULL,
    tipo_servicio ENUM('CONSTANCIA', 'KARDEX', 'ACTUALIZACION_DATOS', 'OTRO') NOT NULL,
    estatus ENUM('PENDIENTE', 'EN_PROCESO', 'ENTREGADO', 'RECHAZADO') DEFAULT 'PENDIENTE',
    comentarios TEXT,
    fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno)
);

-- ==========================================
-- ETAPA 2: MÓDULOS ACADÉMICOS (FUTURO)
-- ==========================================

-- 14. Docentes
-- CREATE TABLE docentes (
--     id_docente INT AUTO_INCREMENT PRIMARY KEY,
--     id_usuario INT UNIQUE,
--     nombre VARCHAR(100),
--     especialidad VARCHAR(100),
--     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
-- );

-- 15. Materias
-- CREATE TABLE materias (
--     id_materia INT AUTO_INCREMENT PRIMARY KEY,
--     nombre VARCHAR(100) NOT NULL,
--     clave VARCHAR(20) UNIQUE,
--     creditos INT
-- );

-- 16. Planes de Estudio (Relación Programa - Materias)
-- CREATE TABLE plan_estudios (
--     id_plan INT AUTO_INCREMENT PRIMARY KEY,
--     id_programa INT NOT NULL,
--     id_materia INT NOT NULL,
--     cuatrimestre INT NOT NULL, -- 1, 2, 3...
--     FOREIGN KEY (id_programa) REFERENCES programas(id_programa),
--     FOREIGN KEY (id_materia) REFERENCES materias(id_materia)
-- );

-- 17. Carga Académica (Grupos - Materias - Docentes)
-- CREATE TABLE carga_academica (
--     id_carga INT AUTO_INCREMENT PRIMARY KEY,
--     id_grupo INT NOT NULL,
--     id_materia INT NOT NULL,
--     id_docente INT,
--     id_periodo INT NOT NULL,
--     FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo),
--     FOREIGN KEY (id_materia) REFERENCES materias(id_materia),
--     FOREIGN KEY (id_docente) REFERENCES docentes(id_docente)
-- );

-- 18. Calificaciones
-- CREATE TABLE calificaciones (
--     id_calificacion INT AUTO_INCREMENT PRIMARY KEY,
--     id_carga INT NOT NULL, -- Relación Grupo-Materia
--     id_alumno INT NOT NULL,
--     parcial_1 DECIMAL(4,2),
--     parcial_2 DECIMAL(4,2),
--     final DECIMAL(4,2),
--     promedio DECIMAL(4,2),
--     FOREIGN KEY (id_carga) REFERENCES carga_academica(id_carga),
--     FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno)
-- );

-- Datos Semilla (Seeders)
INSERT INTO configuracion_financiera (dia_limite_pago, tipo_penalizacion, valor_penalizacion) VALUES (10, 'MONTO', 150.00);

-- Note: Run sp_generar_cargos_mensuales.sql and seeders.sql after this schema
