-- Create SIMPLE demo data using existing tables

-- Check and insert test materias using existing structure
INSERT IGNORE INTO materias (nombre, creditos, estado) VALUES
('Desarrollo Web Full-Stack', 8, 'ACTIVO'),
('Base de Datos Avanzadas', 6, 'ACTIVO'),
('Diseño de Interfaces UX/UI', 6, 'ACTIVO');

-- Just SELECT to get first profesor and use him for all assignments
SELECT 'Using existing profesor and creating assignments...' as Status;
