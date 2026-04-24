<?php
require_once __DIR__ . '/../../includes/autoload.php';
Auth::requireRole('patient');
$userModel = new User();
$user  = $userModel->findById($_SESSION['user_id']);
$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['new_password'] ?? '';
    $curr  = $_POST['current_password'] ?? '';

    if (!$name||!$email) { $error='Name and email are required.'; }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $error='Invalid email.'; }
    elseif ($pass && !password_verify($curr, $user['password'])) { $error='Current password is incorrect.'; }
    elseif ($pass && strlen($pass) < 6) { $error='New password must be at least 6 characters.'; }
    else {
        $existing = $userModel->findByEmail($email);
        if ($existing && $existing['id'] !== (int)$_SESSION['user_id']) {
            $error='Email already in use by another account.';
        } elseif ($userModel->updateProfile($_SESSION['user_id'], $name, $email, $pass?: null)) {
            $_SESSION['user_name'] = $name;
            $success = 'Profile updated successfully!';
            $user = $userModel->findById($_SESSION['user_id']);
        } else { $error='Update failed.'; }
    }
}
$pageTitle = 'My Profile';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header"><h1>👤 My Profile</h1></div>
<div class="card" style="max-width:560px">
  <div class="card-header"><h3>Update Personal Info</h3></div>
  <div class="card-body">
    <?php if ($error): ?><div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div><?php endif; ?>
    <form method="POST">
      <div class="form-group"><label>Full Name</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required></div>
      <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required></div>
      <hr style="margin:18px 0;border:none;border-top:1px solid var(--border)">
      <p class="text-muted text-sm" style="margin-bottom:14px">Leave password fields empty to keep current password.</p>
      <div class="form-group"><label>Current Password</label><input type="password" name="current_password" class="form-control"></div>
      <div class="form-group"><label>New Password</label><input type="password" name="new_password" class="form-control"></div>
      <button type="submit" class="btn btn-primary">💾 Save Changes</button>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
