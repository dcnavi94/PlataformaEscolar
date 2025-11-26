CREATE TABLE IF NOT EXISTS anuncios (
    id_anuncio INT AUTO_INCREMENT PRIMARY KEY,
    id_asignacion INT NOT NULL,
    id_profesor INT NOT NULL,
    mensaje TEXT NOT NULL,
    fecha_publicacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_asignacion) REFERENCES asignaciones(id_asignacion),
    FOREIGN KEY (id_profesor) REFERENCES profesores(id_profesor)
);

ALTER TABLE asignaciones ADD COLUMN banner_img VARCHAR(255) DEFAULT 'https://gstatic.com/classroom/themes/img_code.jpg';
