<?php
require_once __DIR__.'/../includes/functions.php'; require_admin(); $adminPage=true; $pageTitle='Customers';
$rows=$pdo->query("SELECT u.id,u.full_name,u.email,u.created_at,COUNT(r.id) AS reservation_count FROM users u LEFT JOIN reservations r ON r.user_id=u.id WHERE u.role='customer' GROUP BY u.id ORDER BY u.created_at DESC")->fetchAll(); include __DIR__.'/../includes/header.php';
?>
<section class="admin-wrap"><div class="container"><div class="eyebrow mb-3">Administration</div><h1 class="section-title mb-4">Customer accounts.</h1><div class="table-responsive"><table class="table table-dark align-middle"><thead><tr><th>Name</th><th>Email</th><th>Reservations</th><th>Joined</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['full_name']) ?></td><td><?= e($r['email']) ?></td><td><?= (int)$r['reservation_count'] ?></td><td><?= e($r['created_at']) ?></td></tr><?php endforeach; ?></tbody></table></div><a href="dashboard.php">← Back to dashboard</a></div></section>
<?php include __DIR__.'/../includes/footer.php'; ?>
