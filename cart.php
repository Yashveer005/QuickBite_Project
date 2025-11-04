
<?php include('db_connect.php'); if(!isset($_SESSION['user_id'])){ /* allow guest cart stored in session */ } 
$user_id = $_SESSION['user_id'] ?? null;
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['add_to_cart'])){
  $item_id = (int)$_POST['item_id'];
  $qty = max(1,(int)($_POST['qty']??1));
  if($user_id){
    $check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id=$user_id AND item_id=$item_id");
    if(mysqli_num_rows($check)) mysqli_query($conn, "UPDATE cart SET quantity=quantity+$qty WHERE user_id=$user_id AND item_id=$item_id");
    else mysqli_query($conn, "INSERT INTO cart (user_id,item_id,quantity) VALUES ($user_id,$item_id,$qty)");
  } else {
    if(!isset($_SESSION['cart'])) $_SESSION['cart'] = array();
    if(isset($_SESSION['cart'][$item_id])) $_SESSION['cart'][$item_id] += $qty;
    else $_SESSION['cart'][$item_id] = $qty;
  }
  header('Location: cart.php'); exit;
}

if(isset($_POST['checkout'])){
  if(!$user_id){ header('Location: login.php'); exit; }
  $res = mysqli_query($conn, "SELECT m.price, c.quantity FROM cart c JOIN menu_items m ON c.item_id=m.id WHERE c.user_id=$user_id");
  $total = 0
  ;
  while($r=mysqli_fetch_assoc($res)) $total += $r['price']*$r['quantity'];
  mysqli_query($conn, "INSERT INTO orders (user_id,total) VALUES ($user_id,$total)");
  mysqli_query($conn, "DELETE FROM cart WHERE user_id=$user_id");
  echo "<script>alert('Order placed successfully');window.location='menu.php';</script>";
}

$items = array();
$grand = 0;
if($user_id){
  $res = mysqli_query($conn, "SELECT m.name,m.price,c.quantity FROM cart c JOIN menu_items m ON c.item_id=m.id WHERE c.user_id=$user_id");
  while($r=mysqli_fetch_assoc($res)){ $items[]=$r; $grand += $r['price']*$r['quantity']; }
} else {
  if(!empty($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $iid=>$q){
      $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name,price FROM menu_items WHERE id=$iid"));
      $r['quantity'] = $q; $items[]=$r; $grand += $r['price']*$q;
    }
  }
}
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Cart</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<?php include 'includes/header.php'; ?>
<div class="container">
  <h2>Your Cart</h2>
  <?php if(empty($items)): ?>
    <p>Cart is empty. <a href="menu.php">Browse menu</a></p>
  <?php else: ?>
    <table class="cart-table">
      <tr><th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
      <?php foreach($items as $it): ?>
        <tr>
          <td><?php echo htmlspecialchars($it['name']); ?></td>
          <td>₹<?php echo number_format($it['price'],2); ?></td>
          <td><?php echo $it['quantity']; ?></td>
          <td>₹<?php echo number_format($it['price']*$it['quantity'],2); ?></td>
        </tr>
      <?php endforeach; ?>
      <tr><td colspan="3" style="text-align:right"><strong>Total:</strong></td><td><strong>₹<?php echo number_format($grand,2); ?></strong></td></tr>
    </table>
    <form method="post"><button name="checkout" class="btn">Checkout</button></form>
  <?php endif; ?>
</div>
</body></html>
