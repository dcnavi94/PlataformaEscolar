<?php
require_once 'app/Core/Database.php';
require_once 'app/Core/Model.php';
require_once 'app/Models/Asignacion.php';

// Mock session/environment if needed, but Model just needs DB
$model = new Asignacion();
$id_alumno = 1; // Juan
$asignaciones = $model->getByAlumno($id_alumno);

echo "Asignaciones for Alumno $id_alumno:\n";
print_r($asignaciones);

$id_alumno = 2; // Ivan
$asignaciones = $model->getByAlumno($id_alumno);
echo "Asignaciones for Alumno $id_alumno:\n";
print_r($asignaciones);
