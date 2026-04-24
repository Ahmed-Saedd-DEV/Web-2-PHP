<?php
require_once __DIR__ . '/../../includes/autoload.php';
Auth::requireRole('patient');
$recModel = new MedicalRecord();
$records  = $recModel->getPatientRecords($_SESSION['user_id']);
$pageTitle = 'My Records';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header"><h1>📋 My Medical Records</h1><p>Full history of your visits</p></div>
<div class="card">
  <div class="card-header"><h3>Records (<?= count($records) ?>)</h3></div>
  <div class="card-body" style="padding:0">
    <?php if (empty($records)): ?>
      <div style="padding:24px;text-align:center;color:var(--text-muted)">No records found.</div>
    <?php else: ?>
    <div class="table-wrap"><table>
      <thead><tr><th>Visit Date</th><th>Doctor</th><th>Diagnosis</th><th>Action</th></tr></thead>
      <tbody>
        <?php foreach($records as $r): ?>
        <tr>
          <td><?= date('M d, Y', strtotime($r['visit_date'])) ?></td>
          <td>Dr. <?= htmlspecialchars($r['doctor_name']) ?></td>
          <td><?= htmlspecialchars(substr($r['diagnosis'],0,70)).(strlen($r['diagnosis'])>70?'…':'') ?></td>
          <td><a href="/pages/patient/view_record.php?id=<?= $r['id'] ?>" class="btn btn-outline btn-sm">👁 View</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table></div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
