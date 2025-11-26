UPDATE programas SET tipo = 'LICENCIATURA' WHERE tipo = 'INGENIERIA';
ALTER TABLE programas MODIFY COLUMN tipo ENUM('BACHILLERATO', 'LICENCIATURA') NOT NULL;
ALTER TABLE programas MODIFY COLUMN modalidad ENUM('Virtual', 'Presencial Sabatino', 'Presencial L-V') DEFAULT 'Virtual';
