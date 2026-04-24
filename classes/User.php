<?php
class User extends Model {
    protected string $table = 'users';

    public function register(string $name, string $email, string $password, string $role, string $phone = ''): bool {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password, role, phone) VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$name, $email, $hashed, $role, $phone]);
    }

    public function login(string $email, string $password): array|false {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function findByEmail(string $email): array|false {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function updateProfile(int $id, string $name, string $email, ?string $password = null): bool {
        if ($password) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET name=?, email=?, password=? WHERE id=?");
            return $stmt->execute([$name, $email, $hashed, $id]);
        }
        $stmt = $this->db->prepare("UPDATE users SET name=?, email=? WHERE id=?");
        return $stmt->execute([$name, $email, $id]);
    }

    public function getAllByRole(string $role): array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE role = ? ORDER BY created_at DESC");
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }

    public function searchPatients(string $query): array {
        $like = "%$query%";
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE role='patient' AND (name LIKE ? OR email LIKE ?)"
        );
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }

    public function countByRole(string $role): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE role = ?");
        $stmt->execute([$role]);
        return (int)$stmt->fetchColumn();
    }
}
