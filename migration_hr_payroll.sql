-- Add HR fields to profesores table
ALTER TABLE profesores
ADD COLUMN tipo_contrato ENUM('HONORARIOS', 'NOMINA') DEFAULT 'HONORARIOS',
ADD COLUMN tarifa_hora DECIMAL(10, 2) DEFAULT 0.00,
ADD COLUMN rfc VARCHAR(13),
ADD COLUMN curp VARCHAR(18),
ADD COLUMN nss VARCHAR(11),
ADD COLUMN banco VARCHAR(50),
ADD COLUMN clabe VARCHAR(18);

-- Create registro_horas table
CREATE TABLE IF NOT EXISTS registro_horas (
    id_registro INT AUTO_INCREMENT PRIMARY KEY,
    id_profesor INT NOT NULL,
    fecha DATE NOT NULL,
    horas DECIMAL(5, 2) NOT NULL,
    tipo_actividad ENUM('CLASE', 'ADMINISTRATIVO', 'TALLER', 'OTRO') DEFAULT 'CLASE',
    descripcion TEXT,
    estado ENUM('PENDIENTE', 'APROBADO', 'PAGADO') DEFAULT 'PENDIENTE',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_profesor) REFERENCES profesores(id_profesor)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create nominas table
CREATE TABLE IF NOT EXISTS nominas (
    id_nomina INT AUTO_INCREMENT PRIMARY KEY,
    id_profesor INT NOT NULL,
    periodo_inicio DATE NOT NULL,
    periodo_fin DATE NOT NULL,
    total_horas DECIMAL(10, 2) NOT NULL,
    monto_bruto DECIMAL(10, 2) NOT NULL,
    deducciones DECIMAL(10, 2) DEFAULT 0.00,
    monto_neto DECIMAL(10, 2) NOT NULL,
    estado ENUM('PENDIENTE', 'PAGADO') DEFAULT 'PENDIENTE',
    fecha_pago DATETIME,
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_profesor) REFERENCES profesores(id_profesor)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
