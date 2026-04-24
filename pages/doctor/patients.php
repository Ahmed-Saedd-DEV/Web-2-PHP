<?php
require_once __DIR__ . '/../../includes/autoload.php';
Auth::requireRole('doctor');
$userModel = new User();
$patients  = [];
$query     = '';
if (isset($_GET['q'])) {
    $query = trim($_GET['q']);
    $patients = $query ? $userModel->searchPatients($query) : $userModel->getAllByRole('patient');
} else {
    $patients = $userModel->getAllByRole('patient');
}
$pageTitle = 'Search Patients';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header"><h1>🔍 Patients</h1><p>Search and view patient information</p></div>
<div class="card mb-4">
  <div class="card-body">
    <form method="GET" class="search-box">
      <input type="text" name="q" class="form-control" placeholder="Search by name or email…" value="<?= htmlspecialchars($query) ?>">
      <button type="submit" class="btn btn-primary">Search</button>
      <?php if ($query): ?><a href="?" class="btn btn-outline">Clear</a><?php endif; ?>
    </form>
  </div>
</div>
<div class="card">
  <div class="card-header"><h3>Patients (<?= count($patients) ?>)</h3></div>
  <div class="card-body" style="padding:0">
    <?php if (empty($patients)): ?>
      <div style="padding:24px;text-align:center;color:var(--text-muted)">No patients found.</div>
    <?php else: ?>
    <div class="table-wrap"><table>
      <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Joined</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach($patients as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td><?= htmlspecialchars($p['email']) ?></td>
          <td><?= htmlspecialchars($p['phone']??'—') ?></td>
          <td><?= date('M d, Y', strtotime($p['created_at'])) ?></td>
          <td>
            <a href="/pages/doctor/records.php?patient_id=<?= $p['id'] ?>" class="btn btn-outline btn-sm">📋 Records</a>
            <a href="/pages/doctor/add_record.php?patient_id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">➕ Add</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table></div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
