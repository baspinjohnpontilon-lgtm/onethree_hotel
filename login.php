<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle='Log in'; $error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 $email=trim($_POST['email']??''); $password=$_POST['password']??'';
 $stmt=$pdo->prepare("SELECT id,full_name,email,password_hash,role FROM users WHERE email=? LIMIT 1"); $stmt->execute([$email]); $user=$stmt->fetch();
 if ($user && password_verify($password,$user['password_hash'])) {
  session_regenerate_id(true); unset($user['password_hash']); $_SESSION['user']=$user;
  $target=$_SESSION['redirect_after_login']??($user['role']==='admin'?'admin/dashboard.php':'my_reservations.php'); unset($_SESSION['redirect_after_login']); redirect($target);
 } else $error='The email or password you entered is incorrect.';
}
include __DIR__.'/includes/header.php';
?>
<section class="page-head"><div class="container"><div class="eyebrow mb-3">Welcome back</div><h1 class="section-title">Log in to OneThree.</h1></div></section><section class="section pt-4"><div class="container"><div class="form-panel mx-auto" style="max-width:520px"><?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?><form method="post"><div class="mb-3"><label class="form-label">Email address</label><input class="form-control" type="email" name="email" required></div><div class="mb-4"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required></div><button class="btn btn-gold w-100 py-3">Log in</button></form><p class="muted mt-4 mb-0">New here? <a href="register.php">Create an account</a></p></div></div></section>
<?php include __DIR__.'/includes/footer.php'; ?>
