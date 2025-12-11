<?php
require_once __DIR__ . '/Model.php';

/**
 * MedicalRecord Model - handles medical records
 */
class MedicalRecord extends Model {
    
    /**
     * Get all records for a patient
     */
    public static function getByPatient($patientId) {
        $sql = "SELECT mr.*, u.name AS doctor_name
                FROM medical_records mr
                JOIN users u ON mr.doctor_id = u.id
                WHERE mr.patient_id = ?
                ORDER BY mr.date DESC";
        
        return self::fetchAll($sql, [$patientId]);
    }
    
    /**
     * Get all records created by a doctor
     */
    public static function getByDoctor($doctorId) {
        $sql = "SELECT mr.*, 
                       p.name AS patient_name
                FROM medical_records mr
                JOIN users p ON mr.patient_id = p.id
                WHERE mr.doctor_id = ?
                ORDER BY mr.date DESC";
        
        return self::fetchAll($sql, [$doctorId]);
    }
    
    /**
     * Create new medical record
     */
    public static function create($patientId, $doctorId, $diagnosis, $prescriptions, $notes = null) {
        $sql = "INSERT INTO medical_records (patient_id, doctor_id, diagnosis, prescriptions, notes) 
                VALUES (?, ?, ?, ?, ?)";
        
        return self::query($sql, [$patientId, $doctorId, $diagnosis, $prescriptions, $notes]);
    }
    
    /**
     * Get all records (for admin)
     */
    public static function all() {
        $sql = "SELECT mr.*, 
                       p.name AS patient_name,
                       d.name AS doctor_name
                FROM medical_records mr
                JOIN users p ON mr.patient_id = p.id
                JOIN users d ON mr.doctor_id = d.id
                ORDER BY mr.date DESC";
        
        return self::fetchAll($sql);
    }
}
