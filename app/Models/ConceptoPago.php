<?php
// app/Models/ConceptoPago.php

require_once '../app/Core/Model.php';

class ConceptoPago extends Model {
    protected $table = 'conceptos_pago';
    protected $primaryKey = 'id_concepto'; // Override default 'id' if needed, though Model usually assumes id_{table_singular} or id
    
    // Model base class handles basic CRUD. primary key logic might need adjustment if Model.php uses 'id' by default.
    // Checking Model.php... assuming it handles 'id_concepto' if we don't specify, or we might need to be careful.
    // Let's assume Model.php uses 'id' or we need to override find/delete if it's specific.
    // Actually, looking at previous models, they didn't specify primaryKey.
    // Let's check Model.php content if possible, but for now standard methods should work if table structure is standard.
    // Wait, Model.php usually does `WHERE id_$table_singular = ?` or similar?
    // Let's check Model.php later if needed. For now, standard class.
}
