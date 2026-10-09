<?php
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? 'OneThree Hotel';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?> | OneThree</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,400;9..144,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= isset($adminPage) ? '../assets/css/style.css' : 'assets/css/style.css' ?>" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top onethree-nav">
 <div class="container">
  <a class="navbar-brand" href="<?= isset($adminPage) ? '../index.php' : 'index.php' ?>">OneThree<span>.</span></a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button>
  <div class="collapse navbar-collapse" id="mainNav">
   <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
    <li class="nav-item"><a class="nav-link" href="<?= isset($adminPage) ? '../rooms.php' : 'rooms.php' ?>">Rooms & Suites</a></li>
    <?php if (logged_in()): ?>
      <?php if (is_admin()): ?><li class="nav-item"><a class="nav-link" href="<?= isset($adminPage) ? 'dashboard.php' : 'admin/dashboard.php' ?>">Admin</a></li><?php else: ?><li class="nav-item"><a class="nav-link" href="<?= isset($adminPage) ? '../my_reservations.php' : 'my_reservations.php' ?>">My Reservations</a></li><?php endif; ?>
      <li class="nav-item"><a class="nav-link" href="<?= isset($adminPage) ? '../logout.php' : 'logout.php' ?>">Log out</a></li>
    <?php else: ?>
      <li class="nav-item"><a class="nav-link" href="<?= isset($adminPage) ? '../login.php' : 'login.php' ?>">Log in</a></li>
      <li class="nav-item"><a class="btn btn-gold btn-sm px-3" href="<?= isset($adminPage) ? '../register.php' : 'register.php' ?>">Create account</a></li>
    <?php endif; ?>
   </ul>
  </div>
 </div>
</nav>
<main>
