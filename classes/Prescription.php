<?php
class Prescription extends Model {
    protected string $table = 'prescriptions';

    public function create(int $recordId, string $medicationName, string $dosage, string $instructions): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO prescriptions (record_id, medication_name, dosage, instructions) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$recordId, $medicationName, $dosage, $instructions]);
    }

    public function getByRecord(int $recordId): array {
        $stmt = $this->db->prepare("SELECT * FROM prescriptions WHERE record_id = ? ORDER BY prescribed_at DESC");
        $stmt->execute([$recordId]);
        return $stmt->fetchAll();
    }
}
