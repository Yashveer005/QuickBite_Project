<?php
include('db_connect.php');
session_start();

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

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
        echo "<div style='max-width:600px;margin:18px auto;font-family:Poppins;text-align:left;color:#e00;'>";
        foreach ($errors as $e) {
            echo "<p>⚠ $e</p>";
        }
        echo "<a href='cart.php' style='display:inline-block;margin-top:12px;color:#f56600;text-decoration:none;font-weight:700;'>Go back</a></div>";
        exit;
    }

    // compute totals
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $it) {
        $subtotal += $it['price'] * $it['qty'];
    }
    $delivery = 30;
    $tax = round($subtotal * 0.05);
    $total = $subtotal + $delivery + $tax;
    $now = date("Y-m-d H:i:s");

    // insert into DB
    $order_id = null;
    $check = mysqli_query($conn, "SHOW TABLES LIKE 'orders'");
    if ($check && mysqli_num_rows($check) > 0) {
        $fullname = mysqli_real_escape_string($conn, $fullname);
        $phone = mysqli_real_escape_string($conn, $phone);
        $address = mysqli_real_escape_string($conn, $address);
        $sql = "INSERT INTO orders (fullname, phone, address, subtotal, delivery, tax, total, status, created_at)
                VALUES ('$fullname', '$phone', '$address', $subtotal, $delivery, $tax, $total, 'Preparing', '$now')";
        mysqli_query($conn, $sql);
        $order_id = mysqli_insert_id($conn);

        foreach ($_SESSION['cart'] as $it) {
            $name = mysqli_real_escape_string($conn, $it['name']);
            $price = $it['price'];
            $qty = $it['qty'];
            mysqli_query($conn, "INSERT INTO order_items (order_id, name, price, qty) VALUES ($order_id, '$name', $price, $qty)");
        }
    }

    $_SESSION['cart'] = [];

    // user-specific order numbering
    $q = "SELECT COUNT(*) AS total_orders FROM orders WHERE phone = '$phone'";
    $r = mysqli_query($conn, $q);
    $rw = mysqli_fetch_assoc($r);
    $user_order_number = $rw['total_orders'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation — QuickBite</title>
    <style>
        :root {
            --orange: #f56600;
            --orange2: #ff944d;
            --bg: #fff;
            --radius: 14px;
            --shadow: 0 0 8px rgba(0,0,0,0.06);
        }
        body {
            font-family: "Poppins", system-ui;
            background: var(--bg);
            color: #222;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            animation: fadeIn 0.8s ease-in-out;
        }
        .card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 30px;
            text-align: center;
        }
        h2 {
            color: var(--orange);
            margin-bottom: 18px;
        }
        p { margin: 6px 0; color: #555; }
        .btns a {
            display: inline-block;
            padding: 10px 22px;
            margin: 6px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
        }
        .btns .home { background: var(--orange); color: #fff; }
        .btns .menu { background: var(--orange2); color: #fff; }
        .note { margin-top: 20px; color: #777; font-size: 14px; }
        .history { margin-top: 25px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 15px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #eee;
            text-align: center;
        }
        th {
            background: var(--orange2);
            color: #fff;
        }
        td.status {
            font-weight: 600;
        }
        .status.Preparing { color: #ff8c00; }
        .status.Delivered { color: green; }
        .status.Cancelled { color: red; }
        footer {
            margin-top: 30px;
            padding: 12px;
            text-align: center;
            background: linear-gradient(90deg, var(--orange), var(--orange2));
            color: #fff;
            font-size: 14px;
            border-radius: 0 0 var(--radius) var(--radius);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <img src="https://cdn-icons-png.flaticon.com/512/845/845646.png" width="70" alt="success">
        <h2>Order Confirmed!</h2>
        <p>Thank you <strong><?php echo $_SESSION['user_name']; ?></strong>, your order has been placed successfully.</p>
        <p>Order Total: ₹<?php echo $total; ?></p>
        <p>Your Order ID: #<?php echo $user_order_number; ?></p>

        <div class="btns">
            <a href="index.php" class="home">🏠 Home</a>
            <a href="menu.php" class="menu">🍔 Order More</a>
        </div>
        <div class="note">Delivery expected in 30–40 mins 🚚</div>
    </div>

    <?php
    // fetch order history per user
    $res = mysqli_query($conn, "SELECT id, total, status, created_at FROM orders WHERE phone='$phone' ORDER BY id ASC");
    if ($res && mysqli_num_rows($res) > 0) {
        echo "<div class='card history'><h3>🧾 Your Recent Orders</h3><table><tr><th>#Order ID</th><th>Date</th><th>Total (₹)</th><th>Status</th></tr>";
        $count = 1;
        while ($row = mysqli_fetch_assoc($res)) {
            echo "<tr>
                    <td>#".$count."</td>
                    <td>".$row['created_at']."</td>
                    <td>".$row['total']."</td>
                    <td class='status ".$row['status']."'>".$row['status']."</td>
                  </tr>";
            $count++;
        }
        echo "</table></div>";
    }
    ?>
</div>

<footer>© 2025 QuickBite — Designed by Yashveer Singh</footer>
</body>
</html>