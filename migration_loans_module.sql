-- Migration for Material Loans Module

-- 1. Categorías de Material
CREATE TABLE IF NOT EXISTS categorias_material (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estado ENUM('ACTIVO', 'INACTIVO') DEFAULT 'ACTIVO',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Inventario (Materiales)
CREATE TABLE IF NOT EXISTS inventario (
    id_material INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT NOT NULL,
    codigo VARCHAR(50) UNIQUE NOT NULL, -- Código de barras o interno
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    marca VARCHAR(100),
    modelo VARCHAR(100),
    stock_total INT DEFAULT 0,
    stock_disponible INT DEFAULT 0,
    ubicacion VARCHAR(100), -- Estante, Laboratorio, etc.
    estado ENUM('DISPONIBLE', 'AGOTADO', 'BAJA', 'REPARACION') DEFAULT 'DISPONIBLE',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categoria) REFERENCES categorias_material(id_categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Préstamos
CREATE TABLE IF NOT EXISTS prestamos (
    id_prestamo INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL, -- Alumno o Profesor
    id_material INT NOT NULL,
    cantidad INT DEFAULT 1,
    fecha_prestamo DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_devolucion_esperada DATETIME NOT NULL,
    fecha_devolucion_real DATETIME,
    estado ENUM('ACTIVO', 'DEVUELTO', 'VENCIDO', 'PERDIDO', 'DANADO') DEFAULT 'ACTIVO',
    observaciones_prestamo TEXT,
    observaciones_devolucion TEXT,
    id_usuario_admin INT, -- Quién autorizó el préstamo
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_material) REFERENCES inventario(id_material),
    FOREIGN KEY (id_usuario_admin) REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Data for Categories
INSERT INTO categorias_material (nombre, descripcion) VALUES 
('Libros', 'Libros de texto y consulta'),
('Electrónica', 'Componentes electrónicos, arduinos, sensores'),
('Herramienta', 'Herramientas de mano y laboratorio'),
('Cómputo', 'Laptops, tablets y periféricos');
