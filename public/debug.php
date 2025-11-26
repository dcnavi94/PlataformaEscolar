<?php
// public/debug.php - Temporary debug file
session_start();

echo "<h1>Debug Session Info</h1>";
echo "<pre>";
echo "Session ID: " . session_id() . "\n";
echo "Session Data:\n";
print_r($_SESSION);
echo "</pre>";

echo "<h2>Environment</h2>";
echo "<pre>";
echo "PHP Version: " . phpversion() . "\n";
echo "Session Save Path: " . session_save_path() . "\n";
echo "</pre>";
