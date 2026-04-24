<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$role = $_SESSION['role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
$userId = $_SESSION['user_id'] ?? 0;

$navLinks = [
    'admin'   => [
        ['url'=>'/pages/admin/dashboard.php','icon'=>'🏠','label'=>'Dashboard'],
        ['url'=>'/pages/admin/users.php','icon'=>'👥','label'=>'Manage Users'],
        ['url'=>'/pages/admin/add_user.php','icon'=>'➕','label'=>'Add User'],
    ],
    'doctor'  => [
        ['url'=>'/pages/doctor/dashboard.php','icon'=>'🏠','label'=>'Dashboard'],
        ['url'=>'/pages/doctor/records.php','icon'=>'📋','label'=>'Medical Records'],
        ['url'=>'/pages/doctor/add_record.php','icon'=>'➕','label'=>'New Record'],
        ['url'=>'/pages/doctor/patients.php','icon'=>'🔍','label'=>'Search Patients'],
    ],
    'patient' => [
        ['url'=>'/pages/patient/dashboard.php','icon'=>'🏠','label'=>'Dashboard'],
        ['url'=>'/pages/patient/records.php','icon'=>'📋','label'=>'My Records'],
        ['url'=>'/pages/patient/profile.php','icon'=>'👤','label'=>'My Profile'],
    ],
];
$links = $navLinks[$role] ?? [];
$currentFile = '/' . ltrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']), '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($pageTitle ?? 'Health System') ?></title>
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="layout">
<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="icon">🏥</div>
    <h2>HealthCare</h2>
    <p>National Health DB</p>
  </div>
  <nav class="sidebar-nav">
    <ul>
      <?php foreach($links as $link): ?>
        <li>
          <a href="<?= $link['url'] ?>" class="<?= $currentFile === $link['url'] ? 'active' : '' ?>">
            <span><?= $link['icon'] ?></span> <?= htmlspecialchars($link['label']) ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </nav>
  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="avatar"><?= strtoupper(mb_substr($userName,0,1)) ?></div>
      <div class="sidebar-user-info">
        <p><?= htmlspecialchars($userName) ?></p>
        <span><?= htmlspecialchars($role) ?></span>
      </div>
    </div>
    <a href="/includes/logout.php" class="btn btn-outline btn-sm" style="width:100%;justify-content:center">🚪 Logout</a>
  </div>
</aside>
<main class="main">
