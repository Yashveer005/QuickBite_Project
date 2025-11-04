
<?php include('../db_connect.php'); session_start(); if(!isset($_SESSION['admin'])){ header('Location: login.php'); exit; }
if(isset($_POST['add_item'])){
  $rest = (int)$_POST['rest_id']; $name = mysqli_real_escape_string($conn,$_POST['name']); $price = (float)$_POST['price'];
  $img = $_FILES['image']['name']; $tmp = $_FILES['image']['tmp_name']; move_uploaded_file($tmp,'../assets/images/'.$img);
  mysqli_query($conn, "INSERT INTO menu_items (rest_id,name,price,image) VALUES ($rest,'$name',$price,'$img')");
  echo "<script>alert('Added');window.location='dashboard.php';</script>";
}
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Add Item</title><link rel="stylesheet" href="../assets/css/style.css"></head><body>
<?php include '../includes/header.php'; ?>
<div class="form-container"><h2>Add Item</h2><form method="post" enctype="multipart/form-data"><select name="rest_id" required><option value=''>Select Restaurant</option><?php $r=mysqli_query($conn,'SELECT * FROM restaurants'); while($rr=mysqli_fetch_assoc($r)) echo "<option value='{$rr['id']}'>{$rr['name']}</option>"; ?></select><input name="name" placeholder="Item name" required><input name="price" placeholder="Price" required><input type="file" name="image" required><button name="add_item" class="btn">Add</button></form></div></body></html>
