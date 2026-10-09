<?php
require_once __DIR__ . '/includes/functions.php';
require_login();
$code=trim($_GET['code']??'');
$stmt=$pdo->prepare("SELECT r.*,rooms.name AS room_name,rooms.type AS room_type,rooms.image_url FROM reservations r JOIN rooms ON rooms.id=r.room_id WHERE r.reservation_code=? AND (r.user_id=? OR ?='admin') LIMIT 1");
$stmt->execute([$code,$_SESSION['user']['id'],$_SESSION['user']['role']]); $reservation=$stmt->fetch();
if (!$reservation) redirect('my_reservations.php');
$pageTitle='Reservation received'; include __DIR__.'/includes/header.php';
?>
<section class="section" style="padding-top:160px"><div class="container" style="max-width:760px"><div class="eyebrow mb-3">Reservation received</div><h1 class="section-title">Thank you for choosing OneThree.</h1><p class="muted mb-4">Your request has been recorded. Keep your reservation code for reference.</p><div class="summary-panel"><div class="d-flex flex-column flex-md-row justify-content-between gap-3"><div><div class="muted small">Reservation code</div><h3><?= e($reservation['reservation_code']) ?></h3></div><div><div class="muted small">Current status</div><span class="status status-<?= e($reservation['status']) ?>"><?= e($reservation['status']) ?></span></div></div><hr style="border-color:var(--line)"><h2 class="room-name"><?= e($reservation['room_name']) ?></h2><p class="muted"><?= e($reservation['check_in']) ?> → <?= e($reservation['check_out']) ?> · <?= (int)$reservation['nights'] ?> night(s) · <?= (int)$reservation['guests'] ?> guest(s)</p><div class="d-flex justify-content-between"><span class="muted">Estimated total including tax</span><strong>₱<?= number_format($reservation['total_amount'],2) ?></strong></div><p class="muted small mt-3">Payment method: Pay at hotel. Payment status: <?= e($reservation['payment_status']) ?>.</p></div><div class="d-flex gap-3 mt-4 flex-wrap"><a class="btn btn-gold px-4 py-3" href="my_reservations.php">View my reservations</a><a class="btn btn-outline-gold px-4 py-3" href="rooms.php">Explore more rooms</a></div></div></section>
<?php include __DIR__.'/includes/footer.php'; ?>
