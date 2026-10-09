<?php
require_once __DIR__ . '/includes/functions.php';
require_login();
$pageTitle='Reserve your stay'; $error='';
$roomId=(int)($_POST['room_id']??$_GET['room_id']??0);
$checkIn=$_POST['check_in']??$_GET['check_in']??date('Y-m-d',strtotime('+1 day'));
$checkOut=$_POST['check_out']??$_GET['check_out']??date('Y-m-d',strtotime('+2 days'));
$guests=max(1,min(6,(int)($_POST['guests']??$_GET['guests']??2)));
$stmt=$pdo->prepare("SELECT * FROM rooms WHERE id=? AND status='available'"); $stmt->execute([$roomId]); $room=$stmt->fetch();
if (!$room) { include __DIR__.'/includes/header.php'; echo '<section class="section" style="padding-top:150px"><div class="container"><h2>Room not found</h2><a href="rooms.php">Return to rooms</a></div></section>'; include __DIR__.'/includes/footer.php'; exit; }
$validDates=strtotime($checkIn) && strtotime($checkOut) && $checkOut>$checkIn && $checkIn>=date('Y-m-d');
$nights=$validDates?nights_between($checkIn,$checkOut):0;
if ($_SERVER['REQUEST_METHOD']==='POST') {
 $requests=trim($_POST['special_requests']??'');
 if (!$validDates) $error='Please select valid check-in and check-out dates.';
 elseif ($guests>(int)$room['max_guests']) $error='This room does not fit the selected number of guests.';
 elseif (!room_is_available($pdo,$roomId,$checkIn,$checkOut)) $error='Sorry, this room was just booked for some or all of those dates. Please choose another room or date.';
 else {
  $code='OT'.date('ymd').strtoupper(bin2hex(random_bytes(3)));
  $total=round((float)$room['price']*$nights*1.12,2);
  $stmt=$pdo->prepare("INSERT INTO reservations(reservation_code,user_id,room_id,guest_name,guest_email,check_in,check_out,guests,nights,room_subtotal,tax_amount,total_amount,special_requests,payment_method,payment_status,status) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,'Pay at hotel','unpaid','pending')");
  $subtotal=(float)$room['price']*$nights;
  $stmt->execute([$code,$_SESSION['user']['id'],$roomId,$_SESSION['user']['full_name'],$_SESSION['user']['email'],$checkIn,$checkOut,$guests,$nights,$subtotal,round($subtotal*.12,2),$total,$requests]);
  redirect('confirmation.php?code='.urlencode($code));
 }
}
$subtotal=$validDates?(float)$room['price']*$nights:0; $tax=round($subtotal*.12,2); $total=$subtotal+$tax;
include __DIR__.'/includes/header.php';
?>
<section class="page-head"><div class="container"><div class="eyebrow mb-3">Make it yours</div><h1 class="section-title">Reserve your stay.</h1><p class="muted">You're booking as <?= e($_SESSION['user']['full_name']) ?>.</p></div></section><section class="section pt-4"><div class="container"><div class="row g-4"><div class="col-lg-7"><div class="form-panel"><?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="room_id" value="<?= $roomId ?>"><div class="row g-3"><div class="col-md-6"><label class="form-label">Check-in</label><input class="form-control" type="date" name="check_in" min="<?= date('Y-m-d') ?>" value="<?= e($checkIn) ?>" required></div><div class="col-md-6"><label class="form-label">Check-out</label><input class="form-control" type="date" name="check_out" min="<?= date('Y-m-d',strtotime('+1 day')) ?>" value="<?= e($checkOut) ?>" required></div><div class="col-md-6"><label class="form-label">Guests</label><select class="form-select" name="guests"><?php for($i=1;$i<=6;$i++): ?><option value="<?= $i ?>" <?= $guests===$i?'selected':'' ?>><?= $i ?> guest<?= $i>1?'s':'' ?></option><?php endfor; ?></select></div><div class="col-12"><label class="form-label">Special requests (optional)</label><textarea class="form-control" rows="4" name="special_requests" placeholder="Let us know if you have any requests..."><?= e($_POST['special_requests']??'') ?></textarea></div></div><p class="muted small mt-3">This demo uses “Pay at hotel.” Do not enter card numbers or other payment-card details.</p><button class="btn btn-gold w-100 py-3 mt-2">Submit reservation · ₱<?= number_format($total,2) ?></button></form></div></div><div class="col-lg-5"><div class="summary-panel"><img class="room-img mb-4" style="height:260px" src="<?= e($room['image_url']) ?>" alt="<?= e($room['name']) ?>"><div class="room-type"><?= e($room['type']) ?></div><h2 class="room-name"><?= e($room['name']) ?></h2><p class="muted small"><?= e($room['description']) ?></p><hr style="border-color:var(--line)"><div class="d-flex justify-content-between mb-2"><span class="muted">₱<?= number_format($room['price'],2) ?> × <?= $nights ?> night(s)</span><span>₱<?= number_format($subtotal,2) ?></span></div><div class="d-flex justify-content-between mb-3"><span class="muted">Estimated tax (12%)</span><span>₱<?= number_format($tax,2) ?></span></div><hr style="border-color:var(--line)"><div class="d-flex justify-content-between align-items-center"><strong>Total estimate</strong><span class="price">₱<?= number_format($total,2) ?></span></div><p class="muted small mt-3">Reservation requests remain pending until an administrator confirms them.</p></div></div></div></div></section>
<?php include __DIR__.'/includes/footer.php'; ?>
