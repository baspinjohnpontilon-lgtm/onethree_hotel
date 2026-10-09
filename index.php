<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Where every arrival feels like return';
$featured = $pdo->query("SELECT * FROM rooms WHERE status='available' ORDER BY id LIMIT 3")->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<section class="hero"><div class="container"><div class="eyebrow mb-4">Lambunao · Iloilo</div><h1 class="display-title mb-4">Where every arrival<br>feels like <em>return.</em></h1><p class="lead-copy mb-5">A considered stay, quietly exceptional. Discover thoughtful rooms and warm hospitality at OneThree Hotel.</p>
<form action="rooms.php" method="get" class="search-panel row g-3 align-items-end">
 <div class="col-md-4"><label class="form-label muted small">Check-in</label><input class="form-control" type="date" name="check_in" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime('+1 day')) ?>" required></div>
 <div class="col-md-4"><label class="form-label muted small">Check-out</label><input class="form-control" type="date" name="check_out" min="<?= date('Y-m-d', strtotime('+2 day')) ?>" value="<?= date('Y-m-d', strtotime('+2 days')) ?>" required></div>
 <div class="col-md-2"><label class="form-label muted small">Guests</label><select class="form-select" name="guests"><?php for($i=1;$i<=6;$i++): ?><option value="<?= $i ?>" <?= $i===2?'selected':'' ?>><?= $i ?></option><?php endfor; ?></select></div>
 <div class="col-md-2"><button class="btn btn-gold w-100 py-3">Search rooms</button></div>
</form></div></section>
<section class="section"><div class="container"><div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3"><div><div class="eyebrow mb-3">The collection</div><h2 class="section-title mb-0">Rooms made for unwinding.</h2></div><a href="rooms.php">Explore all rooms ↗</a></div><div class="row g-4">
<?php foreach($featured as $room): ?><div class="col-md-4"><article class="room-card"><img class="room-img" src="<?= e($room['image_url']) ?>" alt="<?= e($room['name']) ?>"><div class="room-content"><div class="room-type mb-2"><?= e($room['type']) ?></div><div class="room-name"><?= e($room['name']) ?></div><p class="muted small my-3"><?= e($room['description']) ?></p><div class="d-flex justify-content-between align-items-center"><div class="price">₱<?= number_format($room['price'],0) ?><small> / night</small></div><a href="rooms.php" class="btn btn-outline-gold btn-sm">Discover</a></div></div></article></div><?php endforeach; ?>
</div></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
