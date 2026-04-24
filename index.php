<?php
session_start();
require_once __DIR__ . '/includes/autoload.php';

if (Auth::isLoggedIn()) {
    $role = $_SESSION['role'];
    header("Location: /pages/{$role}/dashboard.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {
        $userModel = new User();
        $user = $userModel->login($email, $password);
        if ($user) {
            Auth::setSession($user);
            header("Location: /pages/{$user['role']}/dashboard.php");
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login – HealthCare</title>
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="auth-wrapper">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="icon">🏥</div>
      <h1>HealthCare</h1>
      <p>National Health Database System</p>
    </div>
    <h2>Sign In</h2>

    <?php if ($error): ?>
      <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['registered'])): ?>
      <div class="alert alert-success">✅ Account created! Please login.</div>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'unauthorized'): ?>
      <div class="alert alert-warning">⛔ Access denied.</div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="you@example.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn btn-primary btn-block">🔐 Sign In</button>
    </form>

    <p style="text-align:center;margin-top:18px;font-size:.9rem;color:var(--text-muted)">
      Don't have an account? <a href="/register.php">Register</a>
    </p>

    <div style="margin-top:20px;padding:14px;background:#f0f4f8;border-radius:8px;font-size:.8rem;color:var(--text-muted)">
      <strong>Demo Account:</strong><br>
      Admin: admin@health.com / password
    </div>
  </div>
</div>
</body>
</html>
