<?php
// cart.php
include('db_connect.php');
session_start();
if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Add item by GET param ?add=ID
if(isset($_GET['add'])){
  $id = (int)$_GET['add'];
  $q = mysqli_query($conn, "SELECT id,name,price FROM menu_items WHERE id=$id LIMIT 1");
  if($q && mysqli_num_rows($q)){
    $row = mysqli_fetch_assoc($q);
    if(isset($_SESSION['cart'][$id])){
      $_SESSION['cart'][$id]['qty'] += 1;
    } else {
      $_SESSION['cart'][$id] = ['name'=>$row['name'],'price'=>$row['price'],'qty'=>1];
    }
  }
  header("Location: cart.php"); exit;
}

// Remove
if(isset($_GET['remove'])){
  $id = (int)$_GET['remove'];
  if(isset($_SESSION['cart'][$id])) unset($_SESSION['cart'][$id]);
  header("Location: cart.php"); exit;
}

// Update quantities
if(isset($_POST['update'])){
  foreach($_POST['qty'] as $id=>$qty){
    $id = (int)$id; $qty = (int)$qty;
    if($qty > 0 && isset($_SESSION['cart'][$id])){
      $_SESSION['cart'][$id]['qty'] = $qty;
    }
  }
  header("Location: cart.php"); exit;
}
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>QuickBite — Cart</title>
<style>
:root{--orange:#ff6600;--orange2:#ff944d;--muted:#666;--shadow:0 10px 30px rgba(15,15,15,0.06);--radius:12px;--maxw:1000px}
*{box-sizing:border-box;margin:0;padding:0;font-family:Poppins,system-ui}
body{background:#fff;color:#222}
.container{max-width:var(--maxw);margin:0 auto;padding:18px}
.header{display:flex;justify-content:space-between;align-items:center;padding:10px 0}
.brand{font-weight:800;font-size:20px}
.brand span:first-child{color:#007bff;}
.brand span:last-child{color:#ff6600;}
.hero{background:linear-gradient(90deg,var(--orange2),var(--orange));color:#fff;padding:20px;border-radius:10px}
.table{width:100%;border-collapse:collapse;margin-top:16px}
.table th,.table td{padding:12px;border-bottom:1px solid #eee;text-align:center}
.table th{background:var(--orange);color:#fff}
.qty-input{width:70px;padding:6px;border-radius:6px;border:1px solid #ddd;text-align:center}
.btn{background:var(--orange);color:#fff;padding:8px 12px;border-radius:8px;border:0;cursor:pointer;font-weight:700}
.btn-danger{background:crimson}
.summary{margin-top:14px;text-align:right;font-weight:800}
.checkout-box{background:#fff8f2;padding:14px;border-radius:10px;box-shadow:var(--shadow);margin-top:18px}
.checkout-box input, .checkout-box textarea{width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;margin-bottom:10px}
.place{background:#007bff;color:#fff;padding:10px 14px;border-radius:8px;border:0;cursor:pointer}
.note{color:var(--muted);font-size:13px}
</style>
</head>
<body>
  <div class="container">
    <div class="header"><div class="brand"><span>Quick</span><span>Bite</span></div><div><a href="menu.php">Menu</a></div></div>
    <div class="hero"><h2>Your Cart</h2><p style="opacity:.95">Review items and place order</p></div>

    <?php if(empty($_SESSION['cart'])): ?>
      <p style="text-align:center;margin-top:24px;color:var(--muted)">Your cart is empty — <a href="menu.php">Browse menu</a></p>
    <?php else: ?>
      <form method="POST">
        <table class="table">
          <tr><th>Item</th><th>Price</th><th>Qty</th><th>Total</th><th>Action</th></tr>
          <?php
            $grand = 0;
            foreach($_SESSION['cart'] as $id=>$it){
              $total = $it['price'] * $it['qty'];
              $grand += $total;
              $safe = htmlspecialchars($it['name'],ENT_QUOTES);
              echo "<tr>
                      <td>{$safe}</td>
                      <td>₹ {$it['price']}</td>
                      <td><input class='qty-input' type='number' name='qty[{$id}]' value='{$it['qty']}' min='1'></td>
                      <td>₹ {$total}</td>
                      <td><a href='cart.php?remove={$id}'><button type='button' class='btn btn-danger'>Remove</button></a></td>
                    </tr>";
            }
          ?>
        </table>

        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px">
          <button type="submit" name="update" class="btn">Update Cart</button>
          <div class="summary">Subtotal: ₹ <?php echo $grand; ?></div>
        </div>
      </form>

      <?php
      // totals
      $delivery = 30;
      $tax = round($grand * 0.05);
      $final = $grand + $delivery + $tax;
      ?>

      <div class="checkout-box">
        <h3>Delivery Details</h3>
        <form action="checkout.php" method="POST" id="orderForm">
          <input type="text" name="fullname" placeholder="Full name (letters only)" pattern="[A-Za-z\s]{3,50}" title="Only letters and spaces" required>
          <input type="tel" name="phone" placeholder="Phone (10 digits)" pattern="[0-9]{10}" title="10 digit phone" required>
          <textarea name="address" rows="3" placeholder="Full delivery address" required></textarea>

          <div style="display:flex;justify-content:space-between;align-items:center">
            <div>
              <div class="note">Delivery: ₹ <?php echo $delivery; ?> • Tax (5%): ₹ <?php echo $tax; ?></div>
              <div class="note" style="font-weight:800;margin-top:6px">Total: ₹ <?php echo $final; ?></div>
            </div>

            <button type="submit" class="place">Place Order • ₹ <?php echo $final; ?></button>
          </div>
        </form>
      </div>
    <?php endif; ?>

  </div>
</body>
</html>