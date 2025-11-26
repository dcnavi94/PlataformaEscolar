-- Actualizar la columna modalidad en la tabla programas
-- APLICADO EXITOSAMENTE el 2025-11-23
ALTER TABLE programas 
MODIFY COLUMN modalidad ENUM('Lunes a Viernes', 'Sabatina', 'Virtual') DEFAULT 'Virtual';
