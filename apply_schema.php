<?php
require_once 'app/Core/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    $sql = file_get_contents('academic_schema.sql');
    
    // Split by semicolon to execute multiple statements if PDO doesn't support it directly in one go (it usually does but safer to split)
    // Actually PDO might handle it if emulation is on, but let's try direct execution.
    // Simple split might break on semicolons in strings, but our schema is simple.
    
    $db->exec($sql);
    echo "Schema applied successfully.\n";
} catch (Exception $e) {
    echo "Error applying schema: " . $e->getMessage() . "\n";
}
