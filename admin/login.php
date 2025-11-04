
<?php session_start(); if(isset($_POST['login'])){ if($_POST['email']=='admin@quickbite.com' && $_POST['password']=='admin123'){ $_SESSION['admin']=true; header('Location: dashboard.php'); exit; } else $err='Invalid'; } ?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Admin Login</title><link rel="stylesheet" href="../assets/css/style.css"></head><body>
<div class="form-container"><h2>Admin Login</h2><?php if(isset($err)) echo '<p class="error">Invalid credentials</p>'; ?>
<form method="post"><input name="email" type="email" placeholder="Email" required><input name="password" type="password" placeholder="Password" required><button name="login" class="btn">Login</button></form></div>
</body></html>
