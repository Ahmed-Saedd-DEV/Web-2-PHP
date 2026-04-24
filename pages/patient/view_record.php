<?php
require_once __DIR__ . '/../../includes/autoload.php';
Auth::requireRole('patient');
$recModel  = new MedicalRecord();
$presModel = new Prescription();
$id     = (int)($_GET['id'] ?? 0);
$record = $recModel->getRecordWithDetails($id);
if (!$record || !$recModel->belongsToPatient($id, $_SESSION['user_id'])) {
    header('Location: /pages/patient/records.php'); exit;
}
$prescriptions = $presModel->getByRecord($id);
$pageTitle = 'Record Details';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
  <div class="breadcrumb"><a href="/pages/patient/records.php">My Records</a><span>›</span><span>Visit <?= date('M d, Y', strtotime($record['visit_date'])) ?></span></div>
  <h1>📋 Visit Details</h1>
</div>
<div class="grid-2 mb-4">
  <div class="card">
    <div class="card-header"><h3>Visit Information</h3></div>
    <div class="card-body">
      <table style="width:100%">
        <tr><td style="color:var(--text-muted);padding:7px 0;width:110px">Doctor</td><td><strong>Dr. <?= htmlspecialchars($record['doctor_name']) ?></strong></td></tr>
        <tr><td style="color:var(--text-muted);padding:7px 0">Date</td><td><?= date('F d, Y', strtotime($record['visit_date'])) ?></td></tr>
        <tr><td style="color:var(--text-muted);padding:7px 0;vertical-align:top">Diagnosis</td><td><?= htmlspecialchars($record['diagnosis']) ?></td></tr>
        <?php if ($record['notes']): ?><tr><td style="color:var(--text-muted);padding:7px 0;vertical-align:top">Notes</td><td><?= htmlspecialchars($record['notes']) ?></td></tr><?php endif; ?>
      </table>
    </div>
  </div>
  <div class="card">
    <div class="card-header"><h3>💊 Prescriptions</h3></div>
    <div class="card-body" style="padding:0">
      <?php if (empty($prescriptions)): ?>
        <div style="padding:20px;text-align:center;color:var(--text-muted)">No prescriptions for this visit.</div>
      <?php else: ?>
      <?php foreach($prescriptions as $p): ?>
        <div style="padding:14px 16px;border-bottom:1px solid var(--border)">
          <div style="font-weight:600"><?= htmlspecialchars($p['medication_name']) ?></div>
          <div><span class="badge badge-blue" style="margin:4px 0"><?= htmlspecialchars($p['dosage']) ?></span></div>
          <?php if ($p['instructions']): ?>
            <div class="text-sm text-muted" style="margin-top:4px"><?= htmlspecialchars($p['instructions']) ?></div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
<a href="/pages/patient/records.php" class="btn btn-outline">← Back to Records</a>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
