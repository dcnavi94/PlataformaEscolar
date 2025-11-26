-- Migration: Add PROFESOR role to usuarios table
-- This enables teacher accounts in the system

-- Check current ENUM values and add PROFESOR if not present
ALTER TABLE usuarios 
MODIFY COLUMN rol ENUM('ADMIN', 'ALUMNO', 'PROFESOR') NOT NULL DEFAULT 'ALUMNO';
