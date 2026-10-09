<?php
require_once __DIR__.'/../includes/functions.php'; require_admin(); $adminPage=true; $pageTitle='Manage reservations'; $message='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 $id=(int)($_POST['id']??0); $action=$_POST['action']??'';
 if (in_array($action,['confirmed','rejected','cancelled'],true)) {
  $stmt=$pdo->prepare("SELECT * FROM reservations WHERE id=?"); $stmt->execute([$id]); $r=$stmt->fetch();
  if ($r) {
   if ($action==='confirmed' && !room_is_available($pdo,(int)$r['room_id'],$r['check_in'],$r['check_out'],$id)) $message='Cannot confirm: another pending or confirmed reservation overlaps these dates.';
   else { $stmt=$pdo->prepare("UPDATE reservations SET status=?,updated_at=NOW() WHERE id=?"); $stmt->execute([$action,$id]); $message='Reservation status updated.'; }
  }
 }
}
$rows=$pdo->query("SELECT r.*,rooms.name AS room_name FROM reservations r JOIN rooms ON rooms.id=r.room_id ORDER BY r.created_at DESC")->fetchAll();
include __DIR__.'/../includes/header.php';
?>
<section class="admin-wrap"><div class="container"><div class="eyebrow mb-3">Administration</div><h1 class="section-title mb-4">Reservations.</h1><?php if($message): ?><div class="alert alert-info"><?= e($message) ?></div><?php endif; ?><div class="table-responsive"><table class="table table-dark align-middle"><thead><tr><th>Code / Date</th><th>Guest</th><th>Room</th><th>Stay</th><th>Total</th><th>Status</th><th>Action</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['reservation_code']) ?><div class="muted small"><?= e($r['created_at']) ?></div></td><td><?= e($r['guest_name']) ?><div class="muted small"><?= e($r['guest_email']) ?></div></td><td><?= e($r['room_name']) ?></td><td><?= e($r['check_in']) ?><br>→ <?= e($r['check_out']) ?><div class="muted small"><?= (int)$r['guests'] ?> guest(s)</div></td><td>₱<?= number_format($r['total_amount'],2) ?></td><td><span class="status status-<?= e($r['status']) ?>"><?= e($r['status']) ?></span></td><td><?php if(in_array($r['status'],['pending','confirmed'],true)): ?><form method="post" class="d-flex gap-1 flex-wrap"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><?php if($r['status']==='pending'): ?><button class="btn btn-sm btn-gold" name="action" value="confirmed">Confirm</button><button class="btn btn-sm btn-outline-gold" name="action" value="rejected">Reject</button><?php else: ?><button class="btn btn-sm btn-outline-gold" name="action" value="cancelled">Cancel</button><?php endif; ?></form><?php else: ?>—<?php endif; ?></td></tr><?php endforeach; ?></tbody></table></div><a href="dashboard.php">← Back to dashboard</a></div></section>
<?php include __DIR__.'/../includes/footer.php'; ?>
