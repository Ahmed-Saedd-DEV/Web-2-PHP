<?php
require_once __DIR__ . '/../../includes/autoload.php';
Auth::requireRole('doctor');
$doctor = new Doctor();
$stats  = $doctor->getDashboardStats($_SESSION['user_id']);
$recent = $doctor->getRecentRecords($_SESSION['user_id']);
$pageTitle = 'Doctor Dashboard';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
  <h1>🩺 Doctor Dashboard</h1>
  <p>Welcome, Dr. <?= htmlspecialchars($_SESSION['user_name']) ?>!</p>
</div>
<div class="stats-grid">
  <div class="stat-card"><div class="stat-icon">🧑‍⚕️</div><div class="stat-info"><p>My Patients</p><h2><?= $stats['patients'] ?></h2></div></div>
  <div class="stat-card green"><div class="stat-icon">📋</div><div class="stat-info"><p>Total Records</p><h2><?= $stats['records'] ?></h2></div></div>
</div>
<div class="card">
  <div class="card-header"><h3>Recent Records</h3><a href="/pages/doctor/add_record.php" class="btn btn-primary btn-sm">➕ New Record</a></div>
  <div class="card-body" style="padding:0">
    <?php if (empty($recent)): ?>
      <div style="padding:24px;text-align:center;color:var(--text-muted)">No records yet. <a href="/pages/doctor/add_record.php">Add a record →</a></div>
    <?php else: ?>
    <div class="table-wrap"><table>
      <thead><tr><th>Patient</th><th>Diagnosis</th><th>Visit Date</th><th>Action</th></tr></thead>
      <tbody>
        <?php foreach($recent as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['patient_name']) ?></td>
          <td><?= htmlspecialchars(substr($r['diagnosis'],0,60)).(strlen($r['diagnosis'])>60?'…':'') ?></td>
          <td><?= date('M d, Y', strtotime($r['visit_date'])) ?></td>
          <td><a href="/pages/doctor/view_record.php?id=<?= $r['id'] ?>" class="btn btn-outline btn-sm">View</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table></div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
