<?php
require_once __DIR__ . '/../../includes/autoload.php';
Auth::requireRole('doctor');
$userModel = new User();
$patients  = $userModel->getAllByRole('patient');
$error = $success = '';
$preselected = (int)($_GET['patient_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientId = (int)($_POST['patient_id'] ?? 0);
    $diagnosis = trim($_POST['diagnosis'] ?? '');
    $notes     = trim($_POST['notes'] ?? '');
    $visitDate = trim($_POST['visit_date'] ?? '');

    if (!$patientId||!$diagnosis||!$visitDate) { $error='Patient, diagnosis and date are required.'; }
    else {
        $rec = new MedicalRecord();
        if ($rec->create($patientId, $_SESSION['user_id'], $diagnosis, $notes, $visitDate)) {
            $success='Record added successfully!';
        } else { $error='Failed to add record.'; }
    }
}
$pageTitle = 'Add Medical Record';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header"><h1>➕ New Medical Record</h1></div>
<div class="card" style="max-width:640px">
  <div class="card-header"><h3>Record Details</h3></div>
  <div class="card-body">
    <?php if ($error): ?><div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div><?php endif; ?>
    <form method="POST">
      <div class="form-group"><label>Patient</label>
        <select name="patient_id" class="form-control" required>
          <option value="">-- Select Patient --</option>
          <?php foreach($patients as $p): ?>
            <option value="<?= $p['id'] ?>" <?= ($preselected===$p['id']||($_POST['patient_id']??0)==$p['id'])?'selected':'' ?>>
              <?= htmlspecialchars($p['name']) ?> (<?= htmlspecialchars($p['email']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group"><label>Visit Date</label>
        <input type="date" name="visit_date" class="form-control" value="<?= htmlspecialchars($_POST['visit_date']??date('Y-m-d')) ?>" required>
      </div>
      <div class="form-group"><label>Diagnosis</label>
        <textarea name="diagnosis" class="form-control" rows="3" required><?= htmlspecialchars($_POST['diagnosis']??'') ?></textarea>
      </div>
      <div class="form-group"><label>Notes (optional)</label>
        <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($_POST['notes']??'') ?></textarea>
      </div>
      <div class="flex gap-2">
        <button type="submit" class="btn btn-primary">💾 Save Record</button>
        <a href="/pages/doctor/records.php" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
