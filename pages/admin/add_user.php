<?php
require_once __DIR__ . '/../../includes/autoload.php';
Auth::requireRole('admin');

$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $role  = $_POST['role'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $allowed = ['doctor','patient'];

    if (!$name||!$email||!$pass||!$role) { $error='All fields required.'; }
    elseif (!filter_var($email,FILTER_VALIDATE_EMAIL)) { $error='Invalid email.'; }
    elseif (!in_array($role,$allowed,true)) { $error='Invalid role.'; }
    else {
        $u = new User();
        if ($u->findByEmail($email)) { $error='Email already exists.'; }
        elseif ($u->register($name,$email,$pass,$role,$phone)) { $success='User created successfully!'; }
        else { $error='Failed to create user.'; }
    }
}
$pageTitle = 'Add User';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
  <h1>➕ Add New User</h1>
  <p>Create a doctor or patient account</p>
</div>
<div class="card" style="max-width:600px">
  <div class="card-header"><h3>User Details</h3></div>
  <div class="card-body">
    <?php if ($error): ?><div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div><?php endif; ?>
    <form method="POST">
      <div class="grid-2" style="gap:12px">
        <div class="form-group"><label>Full Name</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($_POST['name']??'') ?>" required></div>
        <div class="form-group"><label>Phone</label><input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone']??'') ?>"></div>
      </div>
      <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email']??'') ?>" required></div>
      <div class="form-group"><label>Role</label>
        <select name="role" class="form-control" required>
          <option value="">-- Select --</option>
          <option value="doctor"  <?= ($_POST['role']??'')==='doctor'  ?'selected':'' ?>>Doctor</option>
          <option value="patient" <?= ($_POST['role']??'')==='patient' ?'selected':'' ?>>Patient</option>
        </select>
      </div>
      <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" required></div>
      <div class="flex gap-2">
        <button type="submit" class="btn btn-primary">✅ Create User</button>
        <a href="/pages/admin/users.php" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
