<?php
require_once __DIR__ . '/../../includes/autoload.php';
Auth::requireRole('patient');
$recModel = new MedicalRecord();
$records  = $recModel->getPatientRecords($_SESSION['user_id']);
$pageTitle = 'Patient Dashboard';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
  <h1>👤 My Dashboard</h1>
  <p>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</p>
</div>
<div class="stats-grid">
  <div class="stat-card"><div class="stat-icon">📋</div><div class="stat-info"><p>My Records</p><h2><?= count($records) ?></h2></div></div>
</div>
<div class="card">
  <div class="card-header"><h3>📅 Medical History Timeline</h3><a href="/pages/patient/records.php" class="btn btn-outline btn-sm">View All</a></div>
  <div class="card-body">
    <?php if (empty($records)): ?>
      <div style="text-align:center;color:var(--text-muted);padding:20px">No medical records yet.</div>
    <?php else: ?>
    <div class="timeline">
      <?php foreach(array_slice($records,0,5) as $r): ?>
      <div class="timeline-item">
        <div class="timeline-date"><?= date('F d, Y', strtotime($r['visit_date'])) ?></div>
        <div class="timeline-card">
          <h4><?= htmlspecialchars(substr($r['diagnosis'],0,80)) ?></h4>
          <p>Dr. <?= htmlspecialchars($r['doctor_name']) ?></p>
          <a href="/pages/patient/view_record.php?id=<?= $r['id'] ?>" class="btn btn-outline btn-sm" style="margin-top:8px">View Details</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
