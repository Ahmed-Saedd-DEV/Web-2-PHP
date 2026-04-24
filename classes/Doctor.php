<?php
class Doctor extends User {
    public function getDashboardStats(int $doctorId): array {
        $stmt = $this->db->prepare(
            "SELECT COUNT(DISTINCT patient_id) as patients, COUNT(*) as records FROM medical_records WHERE doctor_id = ?"
        );
        $stmt->execute([$doctorId]);
        return $stmt->fetch();
    }

    public function getRecentRecords(int $doctorId, int $limit = 5): array {
        $stmt = $this->db->prepare(
            "SELECT mr.*, u.name as patient_name FROM medical_records mr
             JOIN users u ON mr.patient_id = u.id
             WHERE mr.doctor_id = ?
             ORDER BY mr.visit_date DESC LIMIT ?"
        );
        $stmt->execute([$doctorId, $limit]);
        return $stmt->fetchAll();
    }
}
