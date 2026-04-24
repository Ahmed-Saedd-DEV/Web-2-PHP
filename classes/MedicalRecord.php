<?php
class MedicalRecord extends Model {
    protected string $table = 'medical_records';

    public function create(int $patientId, int $doctorId, string $diagnosis, string $notes, string $visitDate): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO medical_records (patient_id, doctor_id, diagnosis, notes, visit_date) VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$patientId, $doctorId, $diagnosis, $notes, $visitDate]);
    }

    public function updateDiagnosis(int $id, string $diagnosis, string $notes): bool {
        $stmt = $this->db->prepare("UPDATE medical_records SET diagnosis=?, notes=? WHERE id=?");
        return $stmt->execute([$diagnosis, $notes, $id]);
    }

    public function getPatientRecords(int $patientId): array {
        $stmt = $this->db->prepare(
            "SELECT mr.*, u.name as doctor_name FROM medical_records mr
             JOIN users u ON mr.doctor_id = u.id
             WHERE mr.patient_id = ? ORDER BY mr.visit_date DESC"
        );
        $stmt->execute([$patientId]);
        return $stmt->fetchAll();
    }

    public function getDoctorRecords(int $doctorId): array {
        $stmt = $this->db->prepare(
            "SELECT mr.*, u.name as patient_name FROM medical_records mr
             JOIN users u ON mr.patient_id = u.id
             WHERE mr.doctor_id = ? ORDER BY mr.visit_date DESC"
        );
        $stmt->execute([$doctorId]);
        return $stmt->fetchAll();
    }

    public function getRecordWithDetails(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT mr.*, p.name as patient_name, d.name as doctor_name
             FROM medical_records mr
             JOIN users p ON mr.patient_id = p.id
             JOIN users d ON mr.doctor_id = d.id
             WHERE mr.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function belongsToDoctor(int $recordId, int $doctorId): bool {
        $stmt = $this->db->prepare("SELECT id FROM medical_records WHERE id=? AND doctor_id=?");
        $stmt->execute([$recordId, $doctorId]);
        return (bool)$stmt->fetch();
    }

    public function belongsToPatient(int $recordId, int $patientId): bool {
        $stmt = $this->db->prepare("SELECT id FROM medical_records WHERE id=? AND patient_id=?");
        $stmt->execute([$recordId, $patientId]);
        return (bool)$stmt->fetch();
    }
}
