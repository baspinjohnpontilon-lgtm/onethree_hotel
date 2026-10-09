<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Rooms & Suites';
$checkIn = $_GET['check_in'] ?? date('Y-m-d', strtotime('+1 day'));
$checkOut = $_GET['check_out'] ?? date('Y-m-d', strtotime('+2 days'));
$guests = max(1, min(6, (int)($_GET['guests'] ?? 2)));
$type = $_GET['type'] ?? 'All';
$validDates = strtotime($checkIn) && strtotime($checkOut) && $checkOut > $checkIn;
$rooms = $pdo->query("SELECT * FROM rooms WHERE status='available' ORDER BY price")->fetchAll();
$rooms = array_values(array_filter($rooms, function($room) use ($type,$guests,$validDates,$pdo,$checkIn,$checkOut) {
 if ($type !== 'All' && $room['type'] !== $type) return false;
 if ((int)$room['max_guests'] < $guests) return false;
 if ($validDates && !room_is_available($pdo,(int)$room['id'],$checkIn,$checkOut)) return false;
 return true;
}));
include __DIR__ . '/includes/header.php';
?>
<section class="page-head"><div class="container"><div class="eyebrow mb-3">Stay a little longer</div><h1 class="section-title">Rooms & Suites</h1><p class="muted">Eleven considered spaces, each with its own character.</p></div></section>
<section class="section pt-4"><div class="container">
<form method="get" class="search-panel row g-3 align-items-end mb-4">
<div class="col-md-4"><label class="form-label muted small">Check-in</label><input type="date" class="form-control" name="check_in" min="<?= date('Y-m-d') ?>" value="<?= e($checkIn) ?>" required></div>
<div class="col-md-4"><label class="form-label muted small">Check-out</label><input type="date" class="form-control" name="check_out" min="<?= date('Y-m-d',strtotime('+1 day')) ?>" value="<?= e($checkOut) ?>" required></div>
<div class="col-md-2"><label class="form-label muted small">Guests</label><select name="guests" class="form-select"><?php for($i=1;$i<=6;$i++): ?><option value="<?= $i ?>" <?= $guests===$i?'selected':'' ?>><?= $i ?></option><?php endfor; ?></select></div>
<div class="col-md-2"><button class="btn btn-gold w-100 py-3">Update search</button></div>
<div class="col-12"><?php foreach(['All','Standard','Studio','Suite','Penthouse'] as $t): ?><a class="filter-link <?= $type===$t?'active':'' ?>" href="?<?= http_build_query(['check_in'=>$checkIn,'check_out'=>$checkOut,'guests'=>$guests,'type'=>$t]) ?>"><?= e($t) ?></a><?php endforeach; ?></div>
</form>
<?php if (!$validDates): ?><div class="alert alert-warning">Please choose a valid check-out date after check-in.</div><?php elseif (!$rooms): ?><div class="py-5 text-center"><h3 class="section-title">No rooms found for those dates.</h3><p class="muted">Try another date, guest count, or room category.</p></div><?php else: ?><div class="row g-4"><?php foreach($rooms as $room): ?><div class="col-md-6 col-lg-4"><article class="room-card"><img class="room-img" src="<?= e($room['image_url']) ?>" alt="<?= e($room['name']) ?>"><div class="room-content"><div class="room-type mb-2"><?= e($room['type']) ?> · <?= (int)$room['sqft'] ?> sqft</div><div class="room-name"><?= e($room['name']) ?></div><p class="muted small my-3"><?= e($room['description']) ?></p><div class="d-flex justify-content-between align-items-center"><div class="price">₱<?= number_format($room['price'],0) ?><small> / night</small></div><a class="btn btn-gold btn-sm px-3" href="booking.php?room_id=<?= (int)$room['id'] ?>&check_in=<?= urlencode($checkIn) ?>&check_out=<?= urlencode($checkOut) ?>&guests=<?= $guests ?>">Reserve</a></div><div class="muted small mt-3">Floor <?= e($room['floor_label']) ?> · Up to <?= (int)$room['max_guests'] ?> guests</div></div></article></div><?php endforeach; ?></div><?php endif; ?>
</div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
