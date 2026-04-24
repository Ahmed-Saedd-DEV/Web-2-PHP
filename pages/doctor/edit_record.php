<?php
require_once __DIR__ . '/../../includes/autoload.php';
Auth::requireRole('doctor');
$recModel = new MedicalRecord();
$id = (int)($_GET['id'] ?? 0);
$record = $recModel->findById($id);
if (!$record || !$recModel->belongsToDoctor($id, $_SESSION['user_id'])) {
    header('Location: /pages/doctor/records.php'); exit;
}
$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diagnosis = trim($_POST['diagnosis'] ?? '');
    $notes     = trim($_POST['notes'] ?? '');
    if (!$diagnosis) { $error='Diagnosis is required.'; }
    elseif ($recModel->updateDiagnosis($id, $diagnosis, $notes)) {
        $success='Record updated!';
        $record = $recModel->findById($id);
    } else { $error='Update failed.'; }
}
$pageTitle = 'Edit Record';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header"><h1>✏️ Edit Record</h1></div>
<div class="card" style="max-width:600px">
  <div class="card-header"><h3>Update Diagnosis</h3></div>
  <div class="card-body">
    <?php if ($error): ?><div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div><?php endif; ?>
    <form method="POST">
      <div class="form-group"><label>Diagnosis</label>
        <textarea name="diagnosis" class="form-control" rows="4" required><?= htmlspecialchars($record['diagnosis']) ?></textarea>
      </div>
      <div class="form-group"><label>Notes</label>
        <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($record['notes']??'') ?></textarea>
      </div>
      <div class="flex gap-2">
        <button type="submit" class="btn btn-primary">💾 Update</button>
        <a href="/pages/doctor/view_record.php?id=<?= $id ?>" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
