<?php
require_once __DIR__ . '/../../includes/autoload.php';
Auth::requireRole('doctor');
$recModel  = new MedicalRecord();
$presModel = new Prescription();
$id = (int)($_GET['id'] ?? 0);
$record = $recModel->getRecordWithDetails($id);
if (!$record || !$recModel->belongsToDoctor($id, $_SESSION['user_id'])) {
    header('Location: /pages/doctor/records.php'); exit;
}
$prescriptions = $presModel->getByRecord($id);
$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $med   = trim($_POST['medication_name'] ?? '');
    $dos   = trim($_POST['dosage'] ?? '');
    $instr = trim($_POST['instructions'] ?? '');
    if (!$med||!$dos) { $error='Medication name and dosage required.'; }
    elseif ($presModel->create($id, $med, $dos, $instr)) {
        $success='Prescription added!';
        $prescriptions = $presModel->getByRecord($id);
    } else { $error='Failed to add prescription.'; }
}
$pageTitle = 'View Record';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
  <div class="breadcrumb"><a href="/pages/doctor/records.php">Records</a><span>›</span><span>Record #<?= $record['id'] ?></span></div>
  <h1>📋 Medical Record</h1>
</div>
<div class="grid-2 mb-4">
  <div class="card">
    <div class="card-header"><h3>Record Details</h3>
      <a href="/pages/doctor/edit_record.php?id=<?= $id ?>" class="btn btn-primary btn-sm">✏️ Edit</a>
    </div>
    <div class="card-body">
      <table style="width:100%">
        <tr><td style="color:var(--text-muted);padding:6px 0;width:120px">Patient</td><td><strong><?= htmlspecialchars($record['patient_name']) ?></strong></td></tr>
        <tr><td style="color:var(--text-muted);padding:6px 0">Doctor</td><td>Dr. <?= htmlspecialchars($record['doctor_name']) ?></td></tr>
        <tr><td style="color:var(--text-muted);padding:6px 0">Visit Date</td><td><?= date('F d, Y', strtotime($record['visit_date'])) ?></td></tr>
        <tr><td style="color:var(--text-muted);padding:6px 0;vertical-align:top">Diagnosis</td><td><?= htmlspecialchars($record['diagnosis']) ?></td></tr>
        <?php if ($record['notes']): ?><tr><td style="color:var(--text-muted);padding:6px 0;vertical-align:top">Notes</td><td><?= htmlspecialchars($record['notes']) ?></td></tr><?php endif; ?>
      </table>
    </div>
  </div>
  <div class="card">
    <div class="card-header"><h3>💊 Add Prescription</h3></div>
    <div class="card-body">
      <?php if ($error): ?><div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div><?php endif; ?>
      <?php if ($success): ?><div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div><?php endif; ?>
      <form method="POST">
        <div class="form-group"><label>Medication Name</label><input type="text" name="medication_name" class="form-control" required></div>
        <div class="form-group"><label>Dosage</label><input type="text" name="dosage" class="form-control" placeholder="e.g. 500mg twice daily" required></div>
        <div class="form-group"><label>Instructions</label><textarea name="instructions" class="form-control" rows="2"></textarea></div>
        <button type="submit" class="btn btn-success">💾 Add</button>
      </form>
    </div>
  </div>
</div>
<div class="card">
  <div class="card-header"><h3>💊 Prescriptions (<?= count($prescriptions) ?>)</h3></div>
  <div class="card-body" style="padding:0">
    <?php if (empty($prescriptions)): ?>
      <div style="padding:20px;text-align:center;color:var(--text-muted)">No prescriptions yet.</div>
    <?php else: ?>
    <div class="table-wrap"><table>
      <thead><tr><th>Medication</th><th>Dosage</th><th>Instructions</th><th>Date</th></tr></thead>
      <tbody>
        <?php foreach($prescriptions as $p): ?>
        <tr>
          <td><strong><?= htmlspecialchars($p['medication_name']) ?></strong></td>
          <td><span class="badge badge-blue"><?= htmlspecialchars($p['dosage']) ?></span></td>
          <td><?= htmlspecialchars($p['instructions']??'—') ?></td>
          <td><?= date('M d, Y', strtotime($p['prescribed_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table></div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
