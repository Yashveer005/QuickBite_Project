<?php
include('db_connect.php');
session_start();

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fullname = trim($_POST['fullname']);
    $phone    = trim($_POST['phone']);
    $address  = trim($_POST['address']);

    $errors = [];
    if (!preg_match("/^[A-Za-z\s]+$/", $fullname)) {
        $errors[] = "Full name should contain only letters and spaces.";
    }
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "Phone number must be exactly 10 digits.";
    }
    if (empty($address)) {
        $errors[] = "Address is required.";
    }

    if (!empty($errors)) {
        echo "<div style='max-width:600px;margin:18px auto;font-family:Poppins;color:red'>";
        foreach ($errors as $e) echo "<p>⚠ $e</p>";
        echo "<a href='cart.php'>Go back</a></div>";
        exit;
    }

    // Calculate totals
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $it) {
        $subtotal += $it['price'] * $it['qty'];
    }
    $delivery = 30;
    $tax = round($subtotal * 0.05);
    $total = $subtotal + $delivery + $tax;
    $now = date("Y-m-d H:i:s");

    // Insert order
    mysqli_query($conn, "
        INSERT INTO orders (fullname, phone, address, subtotal, delivery, tax, total, status, created_at)
        VALUES ('$fullname','$phone','$address',$subtotal,$delivery,$tax,$total,'Preparing','$now')
    ");
    $order_id = mysqli_insert_id($conn);

    foreach ($_SESSION['cart'] as $it) {
        $name = mysqli_real_escape_string($conn, $it['name']);
        mysqli_query($conn, "
            INSERT INTO order_items (order_id, name, price, qty)
            VALUES ($order_id,'$name',{$it['price']},{$it['qty']})
        ");
    }

    $_SESSION['cart'] = [];

    // User-wise order number
    $r = mysqli_query($conn,"SELECT COUNT(*) AS c FROM orders WHERE phone='$phone'");
    $rw = mysqli_fetch_assoc($r);
    $user_order_number = $rw['c'];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Order Confirmed — QuickBite</title>

<style>
body{
    font-family:Poppins,Arial;
    background:#fff;
    margin:0;
    padding:30px;
}
.container{
    max-width:900px;
    margin:auto;
}
.card{
    background:#fff;
    padding:30px;
    border-radius:14px;
    box-shadow:0 0 12px rgba(0,0,0,0.08);
    margin-bottom:25px;
    text-align:center;
}
h2{color:#f56600;margin-bottom:12px}
.btn a{
    display:inline-block;
    margin:8px;
    padding:10px 22px;
    background:#f56600;
    color:white;
    text-decoration:none;
    border-radius:8px;
    font-weight:600;
}
.btn a.menu{background:#ff944d}
.rest-grid{
    display:flex;
    justify-content:center;
    gap:16px;
    flex-wrap:wrap;
    margin-top:15px;
}
.rest{
    width:220px;
    background:#fff;
    border-radius:12px;
    box-shadow:0 0 8px rgba(0,0,0,0.08);
    padding:10px;
}
.rest img{
    width:100%;
    height:120px;
    object-fit:cover;
    border-radius:10px;
}
.history table{
    width:100%;
    border-collapse:collapse;
    margin-top:12px;
}
th,td{
    padding:10px;
    border:1px solid #eee;
}
th{
    background:#ff944d;
    color:white;
}
footer{
    margin-top:30px;
    text-align:center;
    padding:12px;
    background:linear-gradient(90deg,#f56600,#ff944d);
    color:white;
    border-radius:12px;
}
</style>
</head>

<body>

<div class="container">

<!-- HEADER -->
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
  <div style="font-size:22px;font-weight:800">
    <span style="color:#007bff">Quick</span><span style="color:#f56600">Bite</span>
  </div>
  <div>Hi, <b><?php echo $_SESSION['user_name']; ?></b> 👋</div>
</div>

<!-- CONFIRM CARD -->
<div class="card">
  <img src="https://cdn-icons-png.flaticon.com/512/845/845646.png" width="70">
  <h2>Order Confirmed!</h2>

  <p>Thank you <b><?php echo $_SESSION['user_name']; ?></b></p>
  <p><b>Total Paid:</b> ₹<?php echo $total; ?></p>
  <p style="font-size:18px">
    Your Order ID:
    <span style="color:#f56600;font-weight:700">
      #<?php echo $user_order_number; ?>
    </span>
  </p>

  <div class="btn">
    <a href="index.php">🏠 Home</a>
    <a href="menu.php" class="menu">🍔 Order More</a>
  </div>

  <p style="margin-top:15px;color:#777">Delivery expected in 30–40 mins 🚚</p>
</div>

<!-- ORDER HISTORY -->
<?php
$res = mysqli_query($conn,"SELECT total,status,created_at FROM orders WHERE phone='$phone'");
if(mysqli_num_rows($res)>0){
echo "<div class='card history'><h3>🧾 Your Order History</h3>
<table><tr><th>#</th><th>Date</th><th>Total</th><th>Status</th></tr>";
$i=1;
while($row=mysqli_fetch_assoc($res)){
echo "<tr>
<td>$i</td>
<td>{$row['created_at']}</td>
<td>₹ {$row['total']}</td>
<td>{$row['status']}</td>
</tr>";
$i++;
}
echo "</table></div>";
}
?>

</div>

<footer>
© 2025 QuickBite — Designed by Yashveer Singh
</footer>

</body>
</html>