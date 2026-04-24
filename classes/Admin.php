<?php
class Admin extends User {
    public function getAllUsers(): array {
        $stmt = $this->db->query("SELECT id, name, email, role, phone, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function deleteUser(int $id, int $currentAdminId): bool {
        if ($id === $currentAdminId) return false;
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getDashboardStats(): array {
        return [
            'total_users'    => (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'total_doctors'  => $this->countByRole('doctor'),
            'total_patients' => $this->countByRole('patient'),
            'total_records'  => (int)$this->db->query("SELECT COUNT(*) FROM medical_records")->fetchColumn(),
        ];
    }
}
