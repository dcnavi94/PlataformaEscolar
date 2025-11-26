<?php
// Setup script for academic module database

require_once 'app/Config/Database.php';

echo "=== Academic Module Database Setup ===\n\n";

try {
    $db = Database::getInstance()->getConnection();
    
    // 1. Apply PROFESOR role migration
    echo "Step 1: Adding PROFESOR role to usuarios table...\n";
    $roleMigration = file_get_contents('migration_add_profesor_role.sql');
    $db->exec($roleMigration);
    echo "✓ PROFESOR role added successfully\n\n";
    
    // 2. Apply academic schema
    echo "Step 2: Creating academic tables...\n";
    $schema = file_get_contents('academic_schema.sql');
    
    // Split by semicolons and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $schema)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $db->exec($statement);
        }
    }
    
    echo "✓ Academic tables created successfully:\n";
    echo "  - profesores\n";
    echo "  - materias\n";
    echo "  - asignaciones\n";
    echo "  - calificaciones\n\n";
    
    echo "=== Setup Complete ===\n";
    echo "Academic module database is ready to use!\n\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
