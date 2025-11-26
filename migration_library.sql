CREATE TABLE IF NOT EXISTS categorias_libros (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS libros_digitales (
    id_libro INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    autor VARCHAR(255) NOT NULL,
    descripcion TEXT,
    portada_url VARCHAR(255),
    archivo_url VARCHAR(255),
    id_categoria INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categoria) REFERENCES categorias_libros(id_categoria) ON DELETE SET NULL
);

-- Insert default categories
INSERT INTO categorias_libros (nombre) VALUES 
('Matemáticas'),
('Ciencias'),
('Literatura'),
('Historia'),
('Programación'),
('Idiomas');
