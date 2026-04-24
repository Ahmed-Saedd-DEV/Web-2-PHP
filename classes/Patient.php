<?php
require_once __DIR__ . '/User.php';

class Patient extends User {

    public function getMyRecords(int $patientId): array {
        $stmt = $this->db->prepare(
            "SELECT mr.*, u.name AS doctor_name
             FROM medical_records mr
             JOIN users u ON mr.doctor_id = u.id
             WHERE mr.patient_id = ?
             ORDER BY mr.visit_date DESC"
        );
        $stmt->execute([$patientId]);
        return $stmt->fetchAll();
    }

    public function getMyPrescriptions(int $patientId): array {
        $stmt = $this->db->prepare(
            "SELECT p.*, mr.diagnosis, mr.visit_date, u.name AS doctor_name
             FROM prescriptions p
             JOIN medical_records mr ON p.record_id = mr.id
             JOIN users u ON mr.doctor_id = u.id
             WHERE mr.patient_id = ?
             ORDER BY p.prescribed_at DESC"
        );
        $stmt->execute([$patientId]);
        return $stmt->fetchAll();
    }

    public function getRecordPrescriptions(int $recordId, int $patientId): array {
        // Make sure this record belongs to the patient
        $stmt = $this->db->prepare(
            "SELECT p.* FROM prescriptions p
             JOIN medical_records mr ON p.record_id = mr.id
             WHERE p.record_id = ? AND mr.patient_id = ?"
        );
        $stmt->execute([$recordId, $patientId]);
        return $stmt->fetchAll();
    }
}
