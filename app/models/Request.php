<?php
require_once __DIR__ . '/Model.php';

/**
 * Request Model
 * Handles patient-doctor requests
 */
class Request extends Model
{
    /* =========================
       GET REQUESTS
    ========================= */

    // Get all requests for a specific patient
    public static function getByPatient($patientId)
    {
        $sql = "
            SELECT r.*, u.name AS doctor_name
            FROM requests r
            LEFT JOIN users u ON r.doctor_id = u.id
            WHERE r.patient_id = ?
            ORDER BY r.created_at DESC
        ";

        return self::fetchAll($sql, [$patientId]);
    }

    // Get all pending requests (visible to doctors)
    public static function getPending()
    {
        $sql = "
            SELECT r.*, p.name AS patient_name, p.email AS patient_email
            FROM requests r
            JOIN users p ON r.patient_id = p.id
            WHERE r.status = 'pending'
            ORDER BY r.created_at DESC
        ";

        return self::fetchAll($sql);
    }

    // Get requests assigned to a doctor
    public static function getByDoctor($doctorId)
    {
        $sql = "
            SELECT r.*, p.name AS patient_name, p.email AS patient_email
            FROM requests r
            JOIN users p ON r.patient_id = p.id
            WHERE r.doctor_id = ?
            ORDER BY r.created_at DESC
        ";

        return self::fetchAll($sql, [$doctorId]);
    }

    /* =========================
       CREATE & UPDATE
    ========================= */

    // Create a new request
    public static function create($patientId, $doctorName, $message)
    {
        $sql = "
            INSERT INTO requests (patient_id, doctor_name_requested, message, status)
            VALUES (?, ?, ?, 'pending')
        ";

        return self::query($sql, [$patientId, $doctorName, $message]);
    }

    // Update request status
    public static function updateStatus($requestId, $status, $doctorId = null)
    {
        if ($doctorId) {
            $sql = "UPDATE requests SET status = ?, doctor_id = ? WHERE id = ?";
            return self::query($sql, [$status, $doctorId, $requestId]);
        }

        $sql = "UPDATE requests SET status = ? WHERE id = ?";
        return self::query($sql, [$status, $requestId]);
    }

    // Add doctor reply
    public static function addReply($requestId, $reply)
    {
        $sql = "UPDATE requests SET doctor_reply = ? WHERE id = ?";
        return self::query($sql, [$reply, $requestId]);
    }

    /* =========================
       SINGLE & ADMIN
    ========================= */

    // Find one request by ID
    public static function find($id)
    {
        $sql = "
            SELECT r.*,
                   p.name AS patient_name,
                   p.email AS patient_email,
                   d.name AS doctor_name
            FROM requests r
            JOIN users p ON r.patient_id = p.id
            LEFT JOIN users d ON r.doctor_id = d.id
            WHERE r.id = ?
        ";

        return self::fetchOne($sql, [$id]);
    }

    // Get all requests (admin view)
    public static function all()
    {
        $sql = "
            SELECT r.*, p.name AS patient_name, d.name AS doctor_name
            FROM requests r
            JOIN users p ON r.patient_id = p.id
            LEFT JOIN users d ON r.doctor_id = d.id
            ORDER BY r.created_at DESC
        ";

        return self::fetchAll($sql);
    }
}
