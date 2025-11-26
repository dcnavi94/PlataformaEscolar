-- Seeder data for testing

-- Insert default admin user
INSERT INTO usuarios (nombre, correo, password_hash, rol, estado) 
VALUES ('Administrador', 'admin@escuela.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN', 'ACTIVO');
-- Password: password

-- Insert conceptos de pago
INSERT INTO conceptos_pago (id_concepto, nombre, descripcion, tipo, recurrente, admite_recargo, estado) VALUES
(1, 'Inscripción', 'Pago de inscripción al cuatrimestre', 'INSCRIPCION', FALSE, FALSE, 'ACTIVO'),
(2, 'Colegiatura Mensual', 'Pago mensual de colegiatura', 'COLEGIATURA', TRUE, TRUE, 'ACTIVO'),
(3, 'Uniforme', 'Pago por uniforme escolar', 'EXTRA', FALSE, FALSE, 'ACTIVO'),
(4, 'Penalización', 'Penalización por pago atrasado', 'PENALIZACION', FALSE, FALSE, 'ACTIVO');

-- Insert programas
INSERT INTO programas (nombre, tipo, modalidad, turno, monto_colegiatura, monto_inscripcion, estado) VALUES
('Bachillerato Virtual L-V', 'BACHILLERATO', 'Virtual', 'Lunes a Viernes', 1500.00, 800.00, 'ACTIVO'),
('Bachillerato Virtual Sabatino', 'BACHILLERATO', 'Virtual', 'Sabatino', 1500.00, 800.00, 'ACTIVO'),
('Ingeniería en Software', 'INGENIERIA', 'Virtual', 'Lunes a Viernes', 3000.00, 1500.00, 'ACTIVO'),
('Ingeniería en Telemática', 'INGENIERIA', 'Virtual', 'Lunes a Viernes', 3000.00, 1500.00, 'ACTIVO');

-- Insert periodos 2025
INSERT INTO periodos (nombre, fecha_inicio, fecha_fin, anio, numero_periodo) VALUES
('Enero - Abril 2025', '2025-01-01', '2025-04-30', 2025, 1),
('Mayo - Agosto 2025', '2025-05-01', '2025-08-31', 2025, 2),
('Septiembre - Diciembre 2025', '2025-09-01', '2025-12-31', 2025, 3);

-- Insert grupos
INSERT INTO grupos (nombre, id_programa, id_periodo, estado) VALUES
('BACH-LV-2025-1', 1, 1, 'ACTIVO'),
('BACH-SAB-2025-1', 2, 1, 'ACTIVO'),
('ISW-2025-1', 3, 1, 'ACTIVO'),
('ITEL-2025-1', 4, 1, 'ACTIVO');

-- Insert test student
INSERT INTO usuarios (nombre, correo, password_hash, rol, estado) 
VALUES ('Juan Pérez', 'alumno@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ALUMNO', 'ACTIVO');
-- Password: password

INSERT INTO alumnos (id_usuario, nombre, apellidos, correo, telefono, estatus, porcentaje_beca, id_programa, id_grupo) 
VALUES (LAST_INSERT_ID(), 'Juan', 'Pérez García', 'alumno@test.com', '1234567890', 'INSCRITO', 0.00, 1, 1);
