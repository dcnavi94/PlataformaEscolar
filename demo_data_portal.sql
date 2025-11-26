-- Complete demo data for Udemy-style portal
-- Creates courses, activities, and resources for student alumno@test.com

-- Create demo subjects for grupo 1 if they don't exist
INSERT IGNORE INTO materias (nombre, creditos, estado) VALUES
('Desarrollo Web Full-Stack', 8, 'ACTIVA'),
('Base de Datos Avanzadas', 6, 'ACTIVA'),
('Diseño de Interfaces UX/UI', 6, 'ACTIVA');

-- Create demo professor if doesn't exist  
INSERT IGNORE INTO profesores (nombre, especialidad, correo, telefono, estado) VALUES
('Prof. María González', 'Desarrollo Web', 'maria@escuela.com', '5551234567', 'ACTIVO');

-- Get IDs
SET @id_materia1 = (SELECT id_materia FROM materias WHERE nombre = 'Desarrollo Web Full-Stack' LIMIT 1);
SET @id_materia2 = (SELECT id_materia FROM materias WHERE nombre = 'Base de Datos Avanzadas' LIMIT 1);
SET @id_materia3 = (SELECT id_materia FROM materias WHERE nombre = 'Diseño de Interfaces UX/UI' LIMIT 1);
SET @id_profesor = (SELECT id_profesor FROM profesores WHERE nombre = 'Prof. María González' LIMIT 1);

-- Create assignments for grupo 1
INSERT INTO asignaciones (id_profesor, id_materia, id_grupo, fecha_asignacion, estado_calificacion) VALUES
(@id_profesor, @id_materia1, 1, NOW(), 'ABIERTA'),
(@id_profesor, @id_materia2, 1, NOW(), 'ABIERTA'),
(@id_profesor, @id_materia3, 1, NOW(), 'ABIERTA');

-- Get assignment IDs
SET @id_asig1 = (SELECT id_asignacion FROM asignaciones WHERE id_materia = @id_materia1 AND id_grupo = 1 LIMIT 1);
SET @id_asig2 = (SELECT id_asignacion FROM asignaciones WHERE id_materia = @id_materia2 AND id_grupo = 1 LIMIT 1);
SET @id_asig3 = (SELECT id_asignacion FROM asignaciones WHERE id_materia = @id_materia3 AND id_grupo = 1 LIMIT 1);

-- Activities for Course 1: Desarrollo Web
INSERT INTO actividades (id_asignacion, titulo, descripcion, tipo, fecha_publicacion, fecha_limite, puntos_max, permite_entrega, estado) VALUES
(@id_asig1, 'Bienvenida al Curso', 'Introducción al desarrollo web moderno. Conoce el programa, objetivos y metodología del curso.', 'LECTURA', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 10, 0, 'ACTIVA'),
(@id_asig1, 'Proyecto: Sitio Web Responsivo', 'Desarrolla un sitio web responsivo desde cero usando HTML5, CSS3 y JavaScript.', 'PROYECTO', NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 200, 1, 'ACTIVA'),
(@id_asig1, 'Tarea: Fundamentos de HTML', 'Crea una página web básica con los elementos fundamentales de HTML5.', 'TAREA', NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY), 100, 1, 'ACTIVA'),
(@id_asig1, 'Quiz: CSS y Flexbox', 'Evaluación sobre selectores CSS y sistema de layout Flexbox.', 'EXAMEN', NOW(), DATE_ADD(NOW(), INTERVAL 10 DAY), 50, 1, 'ACTIVA'),
(@id_asig1, 'Proyecto Final: Aplicación Web', 'Desarrolla una aplicación web completa con backend y frontend.', 'PROYECTO', NOW(), DATE_ADD(NOW(), INTERVAL 60 DAY), 300, 1, 'ACTIVA');

-- Activities for Course 2: Base de Datos
INSERT INTO actividades (id_asignacion, titulo, descripcion, tipo, fecha_publicacion, fecha_limite, puntos_max, permite_entrega, estado) VALUES
(@id_asig2, 'Introducción a Bases de Datos', 'Conceptos fundamentales de bases de datos relacionales y no relacionales.', 'LECTURA', NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), 10, 0, 'ACTIVA'),
(@id_asig2, 'Tarea: Diseño de Esquema', 'Diseña el esquema de base de datos para un sistema de gestión escolar.', 'TAREA', NOW(), DATE_ADD(NOW(), INTERVAL 15 DAY), 100, 1, 'ACTIVA'),
(@id_asig2, 'Práctica: Consultas SQL', 'Resuelve 20 ejercicios de consultas SQL avanzadas.', 'TAREA', NOW(), DATE_ADD(NOW(), INTERVAL 20 DAY), 150, 1, 'ACTIVA');

-- Activities for Course 3: UX/UI
INSERT INTO actividades (id_asignacion, titulo, descripcion, tipo, fecha_publicacion, fecha_limite, puntos_max, permite_entrega, estado) VALUES
(@id_asig3, 'Principios de Diseño UX', 'Lee sobre los principios fundamentales de experiencia de usuario.', 'LECTURA', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 10, 0, 'ACTIVA'),
(@id_asig3, 'Proyecto: Wireframes', 'Crea wireframes para una aplicación móvil de comercio electrónico.', 'PROYECTO', NOW(), DATE_ADD(NOW(), INTERVAL 21 DAY), 150, 1, 'ACTIVA');

-- Class Resources for Course 1
INSERT INTO recursos_clase (id_asignacion, titulo, tipo, url, descripcion, fecha_publicacion, visible) VALUES
(@id_asig1, 'Zoom: Clases en Vivo', 'LINK', 'https://zoom.us/j/987654321', 'Sesiones en vivo todos los martes y jueves 6:00 PM', NOW(), 1),
(@id_asig1, 'Curso Completo en Video', 'VIDEO', 'https://youtube.com/playlist/webdev', 'Playlist completa del curso (40 videos)', NOW(), 1),
(@id_asig1, 'Repositorio GitHub', 'LINK', 'https://github.com/curso-web', 'Código fuente de todos los ejemplos del curso', NOW(), 1),
(@id_asig1, 'Documentación MDN', 'LINK', 'https://developer.mozilla.org', 'Referencia oficial de HTML, CSS y JavaScript', NOW(), 1),
(@id_asig1, 'Grupo de Slack', 'LINK', 'https://slack.com/grupo-webdev', 'Canal para dudas y discusiones del curso', NOW(), 1);

-- Resources for Course 2
INSERT INTO recursos_clase (id_asignacion, titulo, tipo, url, descripcion, fecha_publicacion, visible) VALUES
(@id_asig2, 'Sesiones Virtuales', 'LINK', 'https://meet.google.com/abc-defg-hij', 'Clases todos los lunes 5:00 PM', NOW(), 1),
(@id_asig2, 'Tutorial PostgreSQL', 'VIDEO', 'https://youtube.com/postgresql-tutorial', 'Guía completa de PostgreSQL', NOW(), 1),
(@id_asig2, 'Libro: Database Design', 'DOCUMENTO', 'https://drive.google.com/db-book', 'Libro completo en PDF sobre diseño de bases de datos', NOW(), 1);

-- Resources for Course 3
INSERT INTO recursos_clase (id_asignacion, titulo, tipo, url, descripcion, fecha_publicacion, visible) VALUES
(@id_asig3, 'Clases Online', 'LINK', 'https://teams.microsoft.com/ux-class', 'Sesiones miércoles 7:00 PM', NOW(), 1),
(@id_asig3, 'Figma Templates', 'LINK', 'https://figma.com/@templates', 'Plantillas y recursos de diseño', NOW(), 1);

-- Add sample submissions (one completed, one pending)
SET @id_act_html = (SELECT id_actividad FROM actividades WHERE titulo = 'Tarea: Fundamentos de HTML' LIMIT 1);
SET @id_act_bienvenida = (SELECT id_actividad FROM actividades WHERE titulo = 'Bienvenida al Curso' LIMIT 1);

INSERT INTO entregas_tareas (id_actividad, id_alumno, fecha_entrega, comentarios, calificacion, retroalimentacion, estado) VALUES
(@id_act_bienvenida, 1, DATE_SUB(NOW(), INTERVAL 3 DAY), 'Completé la lectura introductoria', 10, '¡Excelente inicio! Bienvenido al curso.', 'CALIFICADA');

INSERT INTO entregas_tareas (id_actividad, id_alumno, fecha_entrega, comentarios, estado) VALUES
(@id_act_html, 1, DATE_SUB(NOW(), INTERVAL 1 DAY), 'Adjunto mi página web con los elementos HTML5 solicitados', 'ENVIADA');
