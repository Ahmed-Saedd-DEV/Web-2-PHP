<?php
require_once __DIR__ . '/../../includes/autoload.php';
Auth::requireRole('admin');

$admin = new Admin();
$msg = $err = '';

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];
    if ($admin->deleteUser($deleteId, $_SESSION['user_id'])) {
        $msg = 'User deleted successfully.';
    } else {
        $err = 'Cannot delete this user.';
    }
}

$users = $admin->getAllUsers();
$pageTitle = 'Manage Users';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
  <h1>👥 Manage Users</h1>
  <p>View and manage all registered users</p>
</div>
<?php if ($msg): ?><div class="alert alert-success">✅ <?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger">⚠️ <?= htmlspecialchars($err) ?></div><?php endif; ?>

<div class="card">
  <div class="card-header">
    <h3>All Users (<?= count($users) ?>)</h3>
    <a href="/pages/admin/add_user.php" class="btn btn-primary btn-sm">➕ Add User</a>
  </div>
  <div class="card-body" style="padding:0">
    <div class="table-wrap"><table>
      <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Phone</th><th>Joined</th><th>Action</th></tr></thead>
      <tbody>
        <?php foreach($users as $u): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td><?= htmlspecialchars($u['name']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><span class="badge badge-<?= $u['role'] ?>"><?= ucfirst($u['role']) ?></span></td>
          <td><?= htmlspecialchars($u['phone'] ?? '—') ?></td>
          <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
          <td>
            <?php if ($u['id'] !== (int)$_SESSION['user_id']): ?>
            <form method="POST" onsubmit="return confirm('Delete this user?')">
              <input type="hidden" name="delete_id" value="<?= $u['id'] ?>">
              <button type="submit" class="btn btn-danger btn-sm">🗑 Delete</button>
            </form>
            <?php else: ?>
            <span class="text-muted text-sm">You</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table></div>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
