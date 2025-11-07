<?php
// checkout.php (Final with Order History)
include('db_connect.php');
session_start();

if(empty($_SESSION['cart'])) {
  header("Location: cart.php");
  exit;
}
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
  header("Location: cart.php");
  exit;
}

$fullname = trim($_POST['fullname'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');

$errors = [];
if(!preg_match('/^[A-Za-z\s]{3,50}$/',$fullname)) $errors[] = "Full name should contain only letters and spaces (3–50 characters).";
if(!preg_match('/^[0-9]{10}$/',$phone)) $errors[] = "Phone number must be exactly 10 digits.";
if(strlen($address) < 6) $errors[] = "Please enter a valid address.";

if(!empty($errors)){
  echo "<div style='max-width:600px;margin:80px auto;font-family:Poppins;text-align:left;color:#c00'>";
  echo "<h2>⚠ Invalid Details</h2><ul>";
  foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>";
  echo "</ul><a href='cart.php' style='display:inline-block;margin-top:12px;color:#ff6600;text-decoration:none;font-weight:700'>← Back to Cart</a></div>";
  exit;
}

// compute totals
$subtotal = 0;
foreach($_SESSION['cart'] as $it) $subtotal += $it['price'] * $it['qty'];
$delivery = 30; $tax = round($subtotal * 0.05); $total = $subtotal + $delivery + $tax;
$now = date('Y-m-d H:i:s');

// insert into DB
$inserted = false; $order_id = null;
$check = @mysqli_query($conn,"SHOW TABLES LIKE 'orders'");
if($check && mysqli_num_rows($check)>0){
  $fn = mysqli_real_escape_string($conn,$fullname);
  $ph = mysqli_real_escape_string($conn,$phone);
  $ad = mysqli_real_escape_string($conn,$address);
  $ins = mysqli_query($conn,"INSERT INTO orders (fullname,phone,address,subtotal,delivery,tax,total,created_at)
                             VALUES ('$fn','$ph','$ad',$subtotal,$delivery,$tax,$total,'$now')");
  if($ins){
    $inserted = true;
    $order_id = mysqli_insert_id($conn);
    $q2 = @mysqli_query($conn,"SHOW TABLES LIKE 'order_items'");
    if($q2 && mysqli_num_rows($q2)>0){
      foreach($_SESSION['cart'] as $mid => $it){
        $mname = mysqli_real_escape_string($conn,$it['name']);
        $price = (int)$it['price']; $qty = (int)$it['qty'];
        mysqli_query($conn,"INSERT INTO order_items (order_id,menu_id,name,price,qty)
                            VALUES ($order_id,$mid,'$mname',$price,$qty)");
      }
    }
  }
}

// clear cart
$_SESSION['cart'] = [];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Order Confirmation — QuickBite</title>
<style>
:root{
  --orange:#ff6600;--orange2:#ff944d;--bg:#fff;
  --shadow:0 10px 30px rgba(0,0,0,0.06);--radius:14px;
}
body{font-family:Poppins,system-ui;background:var(--bg);color:#222;margin:0;padding:0;}
.container{max-width:900px;margin:40px auto;padding:20px;}
.card{
  background:#fff;
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  padding:30px;
  text-align:center;
  margin-bottom:20px;
}
.card h1{color:var(--orange);margin-bottom:10px;}
.card p{color:#555;margin:4px 0;}
.total{margin-top:10px;font-weight:800;}
.note{background:#fff8f2;padding:10px;border-radius:8px;margin-top:10px;font-size:13px;color:#555;}
.btns{margin-top:20px;display:flex;justify-content:center;gap:10px;flex-wrap:wrap;}
.btn{background:var(--orange);color:#fff;text-decoration:none;padding:8px 16px;border-radius:8px;font-weight:600;}
.btn:hover{background:var(--orange2);}
.history{
  background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);
  padding:20px;margin-top:20px;
}
.history h2{color:var(--orange);margin-bottom:10px;}
table{width:100%;border-collapse:collapse;}
th,td{padding:8px;border-bottom:1px solid #eee;text-align:left;font-size:14px;}
th{background:#ff6600;color:#fff;}
footer{margin-top:20px;padding:12px;text-align:center;background:linear-gradient(90deg,var(--orange),var(--orange2));color:#fff;font-weight:600;border-radius:10px;}
</style>
</head>
<body>
<div class="container">
  <div class="card">
    <img src="https://cdn-icons-png.flaticon.com/512/845/845646.png" width="70" alt="success">
    <h1>Order Confirmed!</h1>
    <p>Thank you <strong><?php echo htmlspecialchars($fullname); ?></strong>, your order has been placed successfully.</p>
    <p>Order Total: <strong>₹ <?php echo $total; ?></strong></p>
    <?php if($inserted): ?>
      <p>Your Order ID: <strong>#<?php echo $order_id; ?></strong></p>
    <?php else: ?>
      <div class="note">Note: Orders table not found, order not saved in database (demo mode).</div>
    <?php endif; ?>
    <div class="btns">
      <a href="index.php" class="btn">🏠 Home</a>
      <a href="menu.php" class="btn">🍔 Order More</a>
    </div>
    <div class="note">Delivery expected in 30–40 mins 🚚</div>
  </div>

  <?php
  // fetch order history if table exists
  if($inserted && $phone){
    $his = mysqli_query($conn,"SELECT id,total,created_at FROM orders WHERE phone='$phone' ORDER BY id DESC LIMIT 5");
    if($his && mysqli_num_rows($his)>0){
      echo "<div class='history'><h2>📜 Your Recent Orders</h2><table><tr><th>#Order ID</th><th>Date</th><th>Total (₹)</th></tr>";
      while($row = mysqli_fetch_assoc($his)){
        echo "<tr><td>#{$row['id']}</td><td>{$row['created_at']}</td><td>{$row['total']}</td></tr>";
      }
      echo "</table></div>";
    }
  }
  ?>
</div>
<footer>© 2025 QuickBite — Designed by Yashveer Singh</footer>
</body>
</html>