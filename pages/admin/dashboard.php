<?php
require_once __DIR__ . '/../../includes/autoload.php';
Auth::requireRole('admin');
$admin = new Admin();
$stats = $admin->getDashboardStats();
$recentUsers = array_slice($admin->getAllUsers(), 0, 5);
$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
  <h1>🏠 Admin Dashboard</h1>
  <p>Welcome back, <?= htmlspecialchars($_SESSION['user_name']) ?>!</p>
</div>
<div class="stats-grid">
  <div class="stat-card"><div class="stat-icon">👥</div><div class="stat-info"><p>Total Users</p><h2><?= $stats['total_users'] ?></h2></div></div>
  <div class="stat-card green"><div class="stat-icon">🩺</div><div class="stat-info"><p>Doctors</p><h2><?= $stats['total_doctors'] ?></h2></div></div>
  <div class="stat-card orange"><div class="stat-icon">🧑‍⚕️</div><div class="stat-info"><p>Patients</p><h2><?= $stats['total_patients'] ?></h2></div></div>
  <div class="stat-card red"><div class="stat-icon">📋</div><div class="stat-info"><p>Medical Records</p><h2><?= $stats['total_records'] ?></h2></div></div>
</div>
<div class="card">
  <div class="card-header"><h3>Recent Users</h3><a href="/pages/admin/users.php" class="btn btn-outline btn-sm">View All</a></div>
  <div class="card-body" style="padding:0">
    <div class="table-wrap"><table>
      <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
      <tbody>
        <?php foreach($recentUsers as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u['name']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><span class="badge badge-<?= $u['role'] ?>"><?= ucfirst($u['role']) ?></span></td>
          <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table></div>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
