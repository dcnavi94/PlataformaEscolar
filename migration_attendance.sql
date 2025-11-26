CREATE TABLE IF NOT EXISTS asistencias (
    id_asistencia INT AUTO_INCREMENT PRIMARY KEY,
    id_asignacion INT NOT NULL,
    id_alumno INT NOT NULL,
    fecha DATE NOT NULL,
    estado ENUM('PRESENTE', 'AUSENTE', 'RETARDO', 'JUSTIFICADO') NOT NULL,
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_asignacion) REFERENCES asignaciones(id_asignacion),
    FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno),
    UNIQUE KEY unique_attendance (id_asignacion, id_alumno, fecha)
);
