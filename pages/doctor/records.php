<?php
require_once __DIR__ . '/../../includes/autoload.php';
Auth::requireRole('doctor');
$recModel   = new MedicalRecord();
$userModel  = new User();
$patientFilter = (int)($_GET['patient_id'] ?? 0);
$records = $recModel->getDoctorRecords($_SESSION['user_id']);
if ($patientFilter) {
    $records = array_filter($records, fn($r) => $r['patient_id'] === $patientFilter);
}
$pageTitle = 'Medical Records';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header"><h1>📋 Medical Records</h1></div>
<?php if ($patientFilter): ?>
  <div class="alert alert-info">🔎 Showing records for one patient. <a href="?">Show all</a></div>
<?php endif; ?>
<div class="card">
  <div class="card-header"><h3>Records (<?= count($records) ?>)</h3>
    <a href="/pages/doctor/add_record.php" class="btn btn-primary btn-sm">➕ New Record</a>
  </div>
  <div class="card-body" style="padding:0">
    <?php if (empty($records)): ?>
      <div style="padding:24px;text-align:center;color:var(--text-muted)">No records found.</div>
    <?php else: ?>
    <div class="table-wrap"><table>
      <thead><tr><th>Patient</th><th>Diagnosis</th><th>Visit Date</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach($records as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['patient_name']) ?></td>
          <td><?= htmlspecialchars(substr($r['diagnosis'],0,60)).(strlen($r['diagnosis'])>60?'…':'') ?></td>
          <td><?= date('M d, Y', strtotime($r['visit_date'])) ?></td>
          <td>
            <a href="/pages/doctor/view_record.php?id=<?= $r['id'] ?>" class="btn btn-outline btn-sm">👁 View</a>
            <a href="/pages/doctor/edit_record.php?id=<?= $r['id'] ?>" class="btn btn-primary btn-sm">✏️ Edit</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table></div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
