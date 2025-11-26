<?php
// public/index.php

// Start session
session_start();

// Load environment variables
if (file_exists('../.env')) {
    $lines = file('../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') !== 0 && strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}

// Define constants
define('BASE_URL', getenv('APP_URL') ?: 'http://localhost');

// Autoload Core classes
spl_autoload_register(function($class) {
    $paths = [
        '../app/Core/' . $class . '.php',
        '../app/Config/' . $class . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
});

// Initialize App
$app = new App();
