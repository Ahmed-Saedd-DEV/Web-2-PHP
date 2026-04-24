<?php
session_start();
require_once __DIR__ . '/includes/autoload.php';

if (Auth::isLoggedIn()) {
    header("Location: /pages/{$_SESSION['role']}/dashboard.php"); exit;
}

$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $role     = $_POST['role'] ?? '';
    $phone    = trim($_POST['phone'] ?? '');

    $allowed = ['doctor','patient'];
    if (!$name || !$email || !$password || !$role) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (!in_array($role, $allowed, true)) {
        $error = 'Invalid role selected.';
    } else {
        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            $error = 'Email already registered.';
        } elseif ($userModel->register($name, $email, $password, $role, $phone)) {
            header('Location: /index.php?registered=1'); exit;
        } else {
            $error = 'Registration failed. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Register – HealthCare</title>
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="auth-wrapper">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="icon">🏥</div>
      <h1>HealthCare</h1>
      <p>Create your account</p>
    </div>
    <h2>Register</h2>

    <?php if ($error): ?>
      <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="grid-2" style="gap:12px">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($_POST['name']??'') ?>" required>
        </div>
        <div class="form-group">
          <label>Phone</label>
          <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone']??'') ?>">
        </div>
      </div>
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email']??'') ?>" required>
      </div>
      <div class="form-group">
        <label>Role</label>
        <select name="role" class="form-control" required>
          <option value="">-- Select Role --</option>
          <option value="doctor"  <?= ($_POST['role']??'')==='doctor'  ? 'selected':'' ?>>Doctor</option>
          <option value="patient" <?= ($_POST['role']??'')==='patient' ? 'selected':'' ?>>Patient</option>
        </select>
      </div>
      <div class="grid-2" style="gap:12px">
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Confirm Password</label>
          <input type="password" name="confirm_password" class="form-control" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary btn-block">✅ Create Account</button>
    </form>
    <p style="text-align:center;margin-top:16px;font-size:.9rem;color:var(--text-muted)">
      Already have an account? <a href="/index.php">Login</a>
    </p>
  </div>
</div>
</body>
</html>
