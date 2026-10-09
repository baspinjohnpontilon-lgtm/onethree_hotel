<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle='Create account'; $error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 $name=trim($_POST['full_name']??''); $email=strtolower(trim($_POST['email']??'')); $password=$_POST['password']??'';
 if ($name==='' || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password)<8) $error='Enter your name, a valid email, and a password with at least 8 characters.';
 else {
  try { $stmt=$pdo->prepare("INSERT INTO users(full_name,email,password_hash,role) VALUES(?,?,?,'customer')"); $stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT)]); $_SESSION['user']=['id'=>(int)$pdo->lastInsertId(),'full_name'=>$name,'email'=>$email,'role'=>'customer']; redirect('my_reservations.php'); }
  catch(PDOException $ex){ $error='That email may already be registered. Try logging in instead.'; }
 }
}
include __DIR__.'/includes/header.php';
?>
<section class="page-head"><div class="container"><div class="eyebrow mb-3">Your stay starts here</div><h1 class="section-title">Create an account.</h1></div></section><section class="section pt-4"><div class="container"><div class="form-panel mx-auto" style="max-width:560px"><?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?><form method="post"><div class="mb-3"><label class="form-label">Full name</label><input class="form-control" name="full_name" required value="<?= e($_POST['full_name']??'') ?>"></div><div class="mb-3"><label class="form-label">Email address</label><input class="form-control" type="email" name="email" required value="<?= e($_POST['email']??'') ?>"></div><div class="mb-4"><label class="form-label">Password (at least 8 characters)</label><input class="form-control" type="password" name="password" minlength="8" required></div><button class="btn btn-gold w-100 py-3">Create account</button></form></div></div></section>
<?php include __DIR__.'/includes/footer.php'; ?>
