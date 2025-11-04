
<?php
include('db_connect.php');
if(isset($_POST['signup'])){
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $phone = mysqli_real_escape_string($conn, $_POST['phone']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $sql = "INSERT INTO users (name,email,phone,password) VALUES ('$name','$email','$phone','$password')";
  if(mysqli_query($conn, $sql)){
    header('Location: login.php');
    exit;
  } else {
    $error = "Error: " . mysqli_error($conn);
  }
}
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Signup</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<?php include 'includes/header.php'; ?>
<div class="form-container">
  <h2>Signup</h2>
  <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
  <form method="post">
    <input name="name" placeholder="Name" required>
    <input name="email" placeholder="Email" type="email" required>
    <input name="phone" placeholder="Phone">
    <input name="password" placeholder="Password" type="password" required>
    <button name="signup" class="btn">Signup</button>
  </form>
</div>
</body></html>
