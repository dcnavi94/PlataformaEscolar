<?php
// app/Helpers/FileUploadHelper.php

class FileUploadHelper {
    private static $uploadBasePath = '../uploads/';
    
    /**
     * Upload file to specific directory
     */
    public static function uploadFile($file, $directory, $allowedTypes = [], $maxSize = 10485760) {
        // Validate file
        $validation = self::validateFile($file, $allowedTypes, $maxSize);
        if ($validation !== true) {
            throw new Exception($validation);
        }
        
        // Create directory if not exists
        $fullPath = self::$uploadBasePath . $directory;
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $destination = $fullPath . '/' . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $directory . '/' . $filename;
        }
        
        throw new Exception('Error al subir el archivo');
    }
    
    /**
     * Validate file upload
     */
    public static function validateFile($file, $allowedTypes = [], $maxSize = 10485760) {
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return 'Error en la subida del archivo';
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            $maxMB = $maxSize / 1048576;
            return "El archivo excede el tamaño máximo permitido ({$maxMB}MB)";
        }
        
        // Check file type if specified
        if (!empty($allowedTypes)) {
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, $allowedTypes)) {
                return 'Tipo de archivo no permitido. Tipos permitidos: ' . implode(', ', $allowedTypes);
            }
        }
        
        return true;
    }
    
    /**
     * Delete file
     */
    public static function deleteFile($path) {
        $fullPath = self::$uploadBasePath . $path;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
    
    /**
     * Get file URL
     */
    public static function getFileUrl($path) {
        if (empty($path)) {
            return null;
        }
        return BASE_URL . '/uploads/' . $path;
    }
    
    /**
     * Get file path
     */
    public static function getFilePath($path) {
        return self::$uploadBasePath . $path;
    }
    
    /**
     * Check if file exists
     */
    public static function fileExists($path) {
        $fullPath = self::$uploadBasePath . $path;
        return file_exists($fullPath);
    }
}
